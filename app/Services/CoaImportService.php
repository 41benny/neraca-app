<?php

namespace App\Services;

use App\Enums\AccountNormalBalance;
use App\Enums\ReportType;
use App\Enums\StatementSide;
use App\Models\Account;
use App\Models\AccountMapping;
use App\Models\AccountOpeningBalance;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RuntimeException;
use Throwable;

class CoaImportService
{
    /**
     * @return array{accounts_created:int,accounts_updated:int,mappings_created:int,mappings_updated:int,openings_created:int,openings_updated:int}
     */
    public function import(UploadedFile $file): array
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        $headers = $this->readHeaders($sheet);
        if (empty($headers)) {
            throw new RuntimeException('File tidak memiliki header yang valid.');
        }

        $stats = [
            'accounts_created' => 0,
            'accounts_updated' => 0,
            'mappings_created' => 0,
            'mappings_updated' => 0,
            'openings_created' => 0,
            'openings_updated' => 0,
        ];

        $highestRow = $sheet->getHighestDataRow();

        // Cache parent ids by code saat berjalan
        $accountIndex = [];

        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = $this->rowToAssoc($sheet, $headers, $row);
            if (! $rowData) {
                continue;
            }

            $code = trim((string) ($rowData['code'] ?? ''));
            $name = trim((string) ($rowData['name'] ?? ''));
            if ($code === '' || $name === '') {
                continue;
            }

            $parentCode = $this->nullableStr($rowData['parent_code'] ?? null);
            $level = $this->nullableInt($rowData['level'] ?? null);
            $normalBalance = $this->parseNormalBalance($rowData['normal_balance'] ?? null);
            $accountType = $this->nullableStr($rowData['account_type'] ?? null) ?? 'general';
            $isCash = $this->parseBool($rowData['is_cash_account'] ?? null);
            $description = $this->nullableStr($rowData['description'] ?? null);

            $parentId = null;
            if ($parentCode) {
                $parentId = $accountIndex[$parentCode] ?? Account::query()->where('code', $parentCode)->value('id');
            }

            // Tentukan level jika tidak ada
            if ($level === null) {
                $level = $parentId ? ((int) (Account::find($parentId)?->level ?? 0) + 1) : 1;
            }

            $existing = Account::query()->where('code', $code)->first();
            $account = Account::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'level' => $level,
                    'parent_id' => $parentId,
                    'normal_balance' => $normalBalance->value,
                    'account_type' => $accountType,
                    'is_cash_account' => $isCash,
                    'description' => $description,
                    'is_active' => true,
                ],
            );

            $accountIndex[$code] = $account->id;
            $existing ? $stats['accounts_updated']++ : $stats['accounts_created']++;

            // Mapping laporan (opsional)
            $reportType = $this->nullableStr($rowData['report_type'] ?? null);
            $groupName = $this->nullableStr($rowData['group_name'] ?? null);
            $sideStr = $this->nullableStr($rowData['side'] ?? null);
            $sign = $this->nullableInt($rowData['sign'] ?? null) ?? 1;
            $displayOrder = $this->nullableInt($rowData['display_order'] ?? null) ?? 0;

            if ($reportType && $groupName && $sideStr) {
                $rt = $this->parseReportType($reportType);
                $side = $this->parseSide($sideStr);
                $mappingExists = AccountMapping::query()
                    ->where('account_id', $account->id)
                    ->where('report_type', $rt->value)
                    ->exists();

                AccountMapping::updateOrCreate(
                    ['account_id' => $account->id, 'report_type' => $rt->value],
                    [
                        'group_name' => $groupName,
                        'side' => $side->value,
                        'sign' => $sign,
                        'display_order' => $displayOrder,
                    ],
                );

                $mappingExists ? $stats['mappings_updated']++ : $stats['mappings_created']++;
            }

            // Saldo awal (opsional)
            $openingDebit = $this->nullableFloat($rowData['opening_debit'] ?? null) ?? 0.0;
            $openingCredit = $this->nullableFloat($rowData['opening_credit'] ?? null) ?? 0.0;
            $openingDate = $this->parseDate($rowData['opening_as_of'] ?? null) ?? now()->startOfYear();
            $branchCode = $this->nullableStr($rowData['branch_code'] ?? null);
            $hasOpening = ($openingDebit != 0.0) || ($openingCredit != 0.0);

            if ($hasOpening) {
                $opening = AccountOpeningBalance::updateOrCreate(
                    [
                        'account_id' => $account->id,
                        'branch_code' => $branchCode,
                        'as_of_date' => $openingDate->toDateString(),
                    ],
                    [
                        'debit' => $openingDebit,
                        'credit' => $openingCredit,
                        'memo' => 'Saldo awal (import)',
                    ],
                );

                if ($opening->wasRecentlyCreated) {
                    $stats['openings_created']++;
                } else {
                    $stats['openings_updated']++;
                }
            }
        }

        return $stats;
    }

    /**
     * @return array<int, string>
     */
    private function readHeaders(Worksheet $sheet): array
    {
        $highestColumn = $sheet->getHighestDataColumn();
        $headers = [];
        $colIndex = 1;
        while (true) {
            $cell = $sheet->getCellByColumnAndRow($colIndex, 1);
            $value = trim((string) $cell->getValue());
            if ($value === '' && $colIndex > 50) {
                break;
            }
            if ($value !== '') {
                $headers[$colIndex] = $this->normalizeHeader($value);
            }
            if (\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex) === $highestColumn) {
                break;
            }
            $colIndex++;
        }

        return $headers;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function rowToAssoc(Worksheet $sheet, array $headers, int $row): ?array
    {
        $data = [];
        $isEmpty = true;
        foreach ($headers as $col => $key) {
            $value = $sheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
            if ($value !== null && $value !== '') {
                $isEmpty = false;
            }
            $data[$key] = $value;
        }

        return $isEmpty ? null : $data;
    }

    private function normalizeHeader(string $value): string
    {
        $key = strtolower(trim($value));
        $key = str_replace([' ', '-', '.'], '_', $key);

        return $key;
    }

    private function nullableStr(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $s = trim((string) $value);

        return $s === '' ? null : $s;
    }

    private function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    private function nullableFloat(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }

    private function parseBool(mixed $value): bool
    {
        $v = strtolower((string) $value);

        return in_array($v, ['1', 'true', 'ya', 'yes', 'y'], true);
    }

    private function parseNormalBalance(mixed $value): AccountNormalBalance
    {
        $v = strtolower(trim((string) $value));

        return $v === 'credit' || $v === 'kredit' ? AccountNormalBalance::Credit : AccountNormalBalance::Debit;
    }

    private function parseReportType(string $value): ReportType
    {
        $v = strtolower(trim($value));

        return match ($v) {
            'income_statement', 'laba_rugi', 'labarugi', 'laba-rugi' => ReportType::IncomeStatement,
            'cash_flow', 'arus_kas', 'arus-kas' => ReportType::CashFlow,
            default => ReportType::BalanceSheet,
        };
    }

    private function parseSide(string $value): StatementSide
    {
        $v = strtolower(trim($value));

        return match ($v) {
            'asset', 'aset' => StatementSide::Asset,
            'liability', 'kewajiban', 'utang' => StatementSide::Liability,
            'equity', 'ekuitas' => StatementSide::Equity,
            'revenue', 'pendapatan' => StatementSide::Revenue,
            'expense', 'beban' => StatementSide::Expense,
            default => StatementSide::Asset,
        };
    }

    private function parseDate(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }
        try {
            if (is_numeric($value)) {
                return Carbon::createFromTimestamp(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((float) $value));
            }

            return Carbon::parse((string) $value);
        } catch (Throwable) {
            return null;
        }
    }
}
