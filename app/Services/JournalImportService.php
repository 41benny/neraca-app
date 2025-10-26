<?php

namespace App\Services;

use App\Models\Account;
use App\Models\JournalImport;
use App\Models\JournalLine;
use App\Support\DataTransferObjects\JournalImportResultData;
use App\Support\DataTransferObjects\JournalRowData;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RuntimeException;
use Throwable;

class JournalImportService
{
    public function import(UploadedFile $file, string $batchName, ?int $userId = null, ?string $importedAt = null): JournalImportResultData
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        $rows = $this->parseSheet($sheet);

        if ($rows->isEmpty()) {
            throw new RuntimeException('Tidak ada baris valid pada file yang diunggah.');
        }

        $totalDebit = $rows->sum(fn (JournalRowData $row): float => $row->debit);
        $totalCredit = $rows->sum(fn (JournalRowData $row): float => $row->credit);

        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            throw new RuntimeException('Total debit dan kredit tidak seimbang.');
        }

        $import = DB::transaction(function () use ($rows, $batchName, $file, $userId, $totalDebit, $totalCredit, $importedAt): JournalImport {
            $journalImport = JournalImport::create([
                'batch_name' => $batchName,
                'original_filename' => $file->getClientOriginalName(),
                'user_id' => $userId,
                'rows_count' => $rows->count(),
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'status' => 'completed',
                'context' => ['source' => 'excel'],
                'imported_at' => $this->resolveImportedAt($importedAt),
            ]);

            $accounts = Account::query()
                ->whereIn('code', $rows->pluck('accountCode')->unique())
                ->get()
                ->keyBy('code');

            $payload = [];
            $timestamp = now();

            foreach ($rows as $row) {
                $account = $accounts->get($row->accountCode);

                if (! $account) {
                    throw new RuntimeException("Akun dengan kode {$row->accountCode} tidak ditemukan.");
                }

                $payload[] = [
                    'journal_import_id' => $journalImport->id,
                    'account_id' => $account->id,
                    'journal_date' => $row->journalDate,
                    'document_no' => $row->invoiceId,
                    'description' => $row->description,
                    'debit' => $row->debit,
                    'credit' => $row->credit,
                    'branch_code' => $row->branchCode,
                    'invoice_id' => $row->invoiceId,
                    'project_id' => $row->projectId,
                    'vehicle_id' => $row->vehicleId,
                    'meta' => json_encode($row->meta),
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }

            JournalLine::insert($payload);

            return $journalImport;
        });

        return new JournalImportResultData(
            import: $import,
            rows: $rows,
            createdLines: $rows->count(),
            totalDebit: $totalDebit,
            totalCredit: $totalCredit,
            isBalanced: true,
        );
    }

    /**
     * @return Collection<int, JournalRowData>
     */
    private function parseSheet(Worksheet $sheet): Collection
    {
        $rows = collect();
        $highestRow = $sheet->getHighestDataRow();

        for ($rowIndex = 2; $rowIndex <= $highestRow; $rowIndex++) {
            $dateValue = $sheet->getCell("A{$rowIndex}")->getValue();
            $journalDate = $this->parseDate($dateValue);
            $accountCode = $this->nullableString($sheet->getCell("B{$rowIndex}")->getValue());

            if (! $journalDate || ! $accountCode) {
                continue;
            }

            $debit = (float) $sheet->getCell("C{$rowIndex}")->getCalculatedValue();
            $credit = (float) $sheet->getCell("D{$rowIndex}")->getCalculatedValue();

            if (round($debit, 2) === 0.0 && round($credit, 2) === 0.0) {
                continue;
            }

            $rows->push(new JournalRowData(
                journalDate: $journalDate,
                accountCode: $accountCode,
                description: $this->nullableString($sheet->getCell("E{$rowIndex}")->getValue()),
                debit: $debit,
                credit: $credit,
                branchCode: $this->nullableString($sheet->getCell("F{$rowIndex}")->getValue()),
                invoiceId: $this->nullableString($sheet->getCell("G{$rowIndex}")->getValue()),
                projectId: $this->nullableString($sheet->getCell("H{$rowIndex}")->getValue()),
                vehicleId: $this->nullableString($sheet->getCell("I{$rowIndex}")->getValue()),
                meta: [
                    'row_index' => $rowIndex,
                ],
            ));
        }

        return $rows;
    }

    private function parseDate(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return Carbon::createFromTimestamp(ExcelDate::excelToTimestamp((float) $value));
            }

            return Carbon::parse((string) $value);
        } catch (Throwable) {
            return null;
        }
    }

    private function nullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $string = trim((string) $value);

        return $string === '' ? null : $string;
    }

    private function resolveImportedAt(?string $importedAt): Carbon
    {
        if (! $importedAt) {
            return now();
        }

        try {
            return Carbon::parse($importedAt);
        } catch (Throwable) {
            return now();
        }
    }
}
