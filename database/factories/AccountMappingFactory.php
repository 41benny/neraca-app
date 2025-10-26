<?php

namespace Database\Factories;

use App\Enums\ReportType;
use App\Enums\StatementSide;
use App\Models\Account;
use App\Models\AccountMapping;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AccountMapping>
 */
class AccountMappingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reportType = fake()->randomElement([ReportType::BalanceSheet, ReportType::IncomeStatement]);
        $side = $reportType === ReportType::BalanceSheet
            ? fake()->randomElement([StatementSide::Asset, StatementSide::Liability, StatementSide::Equity])
            : fake()->randomElement([StatementSide::Revenue, StatementSide::Expense]);

        return [
            'account_id' => Account::factory(),
            'report_type' => $reportType,
            'group_name' => $reportType === ReportType::BalanceSheet
                ? fake()->randomElement(['Aset Lancar', 'Aset Tetap', 'Kewajiban Jangka Pendek', 'Ekuitas'])
                : fake()->randomElement(['Pendapatan', 'Beban Operasional']),
            'side' => $side,
            'sign' => $side === StatementSide::Revenue || $side === StatementSide::Equity ? -1 : 1,
            'display_order' => fake()->numberBetween(1, 200),
        ];
    }
}
