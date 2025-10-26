<?php

namespace App\Services;

use App\Enums\ReportType;
use App\Models\Account;
use App\Models\AccountOpeningBalance;
use App\Models\JournalLine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class FinancialReportService
{
    public function balanceSheet(Carbon $asOf, ?string $branchCode = null): array
    {
        $statement = $this->buildStatement(
            reportType: ReportType::BalanceSheet,
            periodStart: null,
            periodEnd: $asOf,
            filters: ['branch_code' => $branchCode],
            includeOpening: true,
        );

        $netIncome = $this->currentYearNetIncome($asOf, $branchCode);

        if (abs($netIncome) >= 0.01) {
            $currentEarnings = [
                'name' => 'Laba Tahun Berjalan',
                'side' => 'equity',
                'order' => 999,
                'accounts' => [[
                    'code' => 'CURRENT_EARNINGS',
                    'name' => 'Laba Tahun Berjalan',
                    'amount' => round($netIncome, 2),
                    'signed_amount' => round($netIncome, 2),
                ]],
                'total' => round($netIncome, 2),
                'signed_total' => round($netIncome, 2),
            ];

            $statement['groups'][] = $currentEarnings;
            $statement['side_totals']['equity'] = ($statement['side_totals']['equity'] ?? 0.0) + round($netIncome, 2);

            usort($statement['groups'], fn (array $a, array $b): int => $a['order'] <=> $b['order']);
        }

        return [
            'meta' => [
                'report' => 'balance_sheet',
                'as_of' => $asOf->toDateString(),
                'branch_code' => $branchCode,
            ],
            'groups' => $statement['groups'],
            'totals' => [
                'assets' => round($this->sideTotal($statement['side_totals'], 'asset'), 2),
                'liabilities_equity' => round(
                    $this->sideTotal($statement['side_totals'], 'liability')
                    + $this->sideTotal($statement['side_totals'], 'equity'),
                    2
                ),
            ],
        ];
    }

    public function incomeStatement(Carbon $startDate, Carbon $endDate, array $filters = []): array
    {
        $statement = $this->buildStatement(
            reportType: ReportType::IncomeStatement,
            periodStart: $startDate,
            periodEnd: $endDate,
            filters: $filters,
            includeOpening: false,
        );

        $revenue = $this->sideTotal($statement['side_totals'], 'revenue');
        $expenses = $this->sideTotal($statement['side_totals'], 'expense');

        return [
            'meta' => [
                'report' => 'income_statement',
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'filters' => array_filter([
                    'branch_code' => $filters['branch_code'] ?? null,
                    'invoice_id' => $filters['invoice_id'] ?? null,
                    'project_id' => $filters['project_id'] ?? null,
                    'vehicle_id' => $filters['vehicle_id'] ?? null,
                ], fn ($value) => $value !== null && $value !== ''),
            ],
            'groups' => $statement['groups'],
            'totals' => [
                'revenue' => round($revenue, 2),
                'expenses' => round($expenses, 2),
                'net_income' => round($revenue - $expenses, 2),
            ],
        ];
    }

    /**
     * @return array{groups: array<int, array<string, mixed>>, side_totals: array<string, float>}
     */
    private function buildStatement(ReportType $reportType, ?Carbon $periodStart, Carbon $periodEnd, array $filters, bool $includeOpening): array
    {
        $accounts = Account::query()
            ->with(['mappings' => fn ($query) => $query->where('report_type', $reportType->value)])
            ->whereHas('mappings', fn ($query) => $query->where('report_type', $reportType->value))
            ->orderBy('code')
            ->get();

        $branchCode = $filters['branch_code'] ?? null;
        $openingBalances = $includeOpening
            ? $this->openingBalanceSums($periodEnd, $branchCode)
            : collect();

        $lineSums = $this->journalLineSums($periodStart, $periodEnd, $filters);

        $groups = [];
        $sideTotals = [];

        foreach ($accounts as $account) {
            $mapping = $account->mappings->first();

            if (! $mapping) {
                continue;
            }

            $openingNet = $includeOpening ? $this->netAmount($openingBalances->get($account->id)) : 0.0;
            $periodNet = $this->netAmount($lineSums->get($account->id));
            $balance = $openingNet + $periodNet;

            if (! $includeOpening) {
                $balance = $periodNet;
            }

            if (abs($balance) < 0.005) {
                continue;
            }

            $normalized = $balance * $account->normalBalanceMultiplier();

            if (abs($normalized) < 0.005) {
                continue;
            }

            $groupKey = $mapping->group_name;

            $groups[$groupKey] ??= [
                'name' => $mapping->group_name,
                'side' => $mapping->side->value,
                'order' => $mapping->display_order,
                'accounts' => [],
                'total' => 0.0,
                'signed_total' => 0.0,
            ];

            $accountAmount = round($normalized, 2);
            $signedAmount = round($normalized * $mapping->sign, 2);

            $groups[$groupKey]['accounts'][] = [
                'code' => $account->code,
                'name' => $account->name,
                'amount' => $accountAmount,
                'signed_amount' => $signedAmount,
            ];

            $groups[$groupKey]['total'] += $accountAmount;
            $groups[$groupKey]['signed_total'] += $signedAmount;

            $sideTotals[$mapping->side->value] = ($sideTotals[$mapping->side->value] ?? 0.0) + $accountAmount;
        }

        usort($groups, fn (array $a, array $b): int => $a['order'] <=> $b['order']);

        $groups = array_map(function (array $group): array {
            $group['total'] = round($group['total'], 2);
            $group['signed_total'] = round($group['signed_total'], 2);

            return $group;
        }, $groups);

        return [
            'groups' => array_values($groups),
            'side_totals' => array_map(fn ($value) => round($value, 2), $sideTotals),
        ];
    }

    private function openingBalanceSums(Carbon $asOf, ?string $branchCode): Collection
    {
        $query = AccountOpeningBalance::query()
            ->selectRaw('account_id, SUM(debit) as debit_sum, SUM(credit) as credit_sum')
            ->where('as_of_date', '<=', $asOf->toDateString())
            ->groupBy('account_id');

        $this->applyBranchFilter($query, $branchCode);

        return $query->get()->keyBy('account_id');
    }

    private function journalLineSums(?Carbon $startDate, Carbon $endDate, array $filters): Collection
    {
        $query = JournalLine::query()
            ->selectRaw('account_id, SUM(debit) as debit_sum, SUM(credit) as credit_sum')
            ->groupBy('account_id');

        if ($startDate) {
            $query->whereBetween('journal_date', [$startDate->toDateString(), $endDate->toDateString()]);
        } else {
            $query->where('journal_date', '<=', $endDate->toDateString());
        }

        $this->applyBranchFilter($query, $filters['branch_code'] ?? null);

        foreach (['invoice_id', 'project_id', 'vehicle_id'] as $dimension) {
            if (! empty($filters[$dimension])) {
                $query->where($dimension, $filters[$dimension]);
            }
        }

        return $query->get()->keyBy('account_id');
    }

    private function applyBranchFilter(Builder $query, ?string $branchCode): void
    {
        if (! $branchCode) {
            return;
        }

        $query->where(function (Builder $branchQuery) use ($branchCode) {
            $branchQuery->whereNull('branch_code')
                ->orWhere('branch_code', $branchCode);
        });
    }

    private function netAmount(?object $record): float
    {
        if (! $record) {
            return 0.0;
        }

        return (float) $record->debit_sum - (float) $record->credit_sum;
    }

    private function sideTotal(array $sideTotals, string $side): float
    {
        return $sideTotals[$side] ?? 0.0;
    }

    private function currentYearNetIncome(Carbon $asOf, ?string $branchCode): float
    {
        $startOfYear = $asOf->copy()->startOfYear();

        $statement = $this->buildStatement(
            reportType: ReportType::IncomeStatement,
            periodStart: $startOfYear,
            periodEnd: $asOf,
            filters: ['branch_code' => $branchCode],
            includeOpening: false,
        );

        return $this->sideTotal($statement['side_totals'], 'revenue')
            - $this->sideTotal($statement['side_totals'], 'expense');
    }
}
