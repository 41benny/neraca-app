<?php

namespace Database\Seeders;

use App\Enums\AccountNormalBalance;
use App\Enums\ReportType;
use App\Enums\StatementSide;
use App\Models\Account;
use App\Models\AccountMapping;
use App\Models\AccountOpeningBalance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'code' => '1000',
                'name' => 'Aset Lancar',
                'level' => 1,
                'normal_balance' => AccountNormalBalance::Debit,
                'account_type' => 'asset.current',
                'description' => 'Kelompok aset lancar',
            ],
            [
                'code' => '1100',
                'name' => 'Kas dan Bank',
                'parent_code' => '1000',
                'normal_balance' => AccountNormalBalance::Debit,
                'account_type' => 'asset.cash',
                'is_cash_account' => true,
                'mapping' => [
                    'report_type' => ReportType::BalanceSheet,
                    'group_name' => 'Kas dan Setara Kas',
                    'side' => StatementSide::Asset,
                    'sign' => 1,
                    'display_order' => 10,
                ],
                'opening_balance' => [
                    'debit' => 250_000_000,
                ],
            ],
            [
                'code' => '1200',
                'name' => 'Piutang Usaha',
                'parent_code' => '1000',
                'normal_balance' => AccountNormalBalance::Debit,
                'account_type' => 'asset.receivable',
                'mapping' => [
                    'report_type' => ReportType::BalanceSheet,
                    'group_name' => 'Piutang Usaha',
                    'side' => StatementSide::Asset,
                    'sign' => 1,
                    'display_order' => 20,
                ],
                'opening_balance' => [
                    'debit' => 125_000_000,
                ],
            ],
            [
                'code' => '2000',
                'name' => 'Kewajiban Lancar',
                'level' => 1,
                'normal_balance' => AccountNormalBalance::Credit,
                'account_type' => 'liability.current',
            ],
            [
                'code' => '2100',
                'name' => 'Hutang Usaha',
                'parent_code' => '2000',
                'normal_balance' => AccountNormalBalance::Credit,
                'account_type' => 'liability.payable',
                'mapping' => [
                    'report_type' => ReportType::BalanceSheet,
                    'group_name' => 'Hutang Usaha',
                    'side' => StatementSide::Liability,
                    'sign' => -1,
                    'display_order' => 40,
                ],
                'opening_balance' => [
                    'credit' => 80_000_000,
                ],
            ],
            [
                'code' => '3000',
                'name' => 'Ekuitas',
                'level' => 1,
                'normal_balance' => AccountNormalBalance::Credit,
                'account_type' => 'equity',
            ],
            [
                'code' => '3100',
                'name' => 'Modal Disetor',
                'parent_code' => '3000',
                'normal_balance' => AccountNormalBalance::Credit,
                'account_type' => 'equity.capital',
                'mapping' => [
                    'report_type' => ReportType::BalanceSheet,
                    'group_name' => 'Ekuitas',
                    'side' => StatementSide::Equity,
                    'sign' => -1,
                    'display_order' => 60,
                ],
                'opening_balance' => [
                    'credit' => 295_000_000,
                ],
            ],
            [
                'code' => '3200',
                'name' => 'Laba Ditahan',
                'parent_code' => '3000',
                'normal_balance' => AccountNormalBalance::Credit,
                'account_type' => 'equity.retained',
                'mapping' => [
                    'report_type' => ReportType::BalanceSheet,
                    'group_name' => 'Ekuitas',
                    'side' => StatementSide::Equity,
                    'sign' => -1,
                    'display_order' => 70,
                ],
                'opening_balance' => [
                    'credit' => 0,
                ],
            ],
            [
                'code' => '4000',
                'name' => 'Pendapatan',
                'level' => 1,
                'normal_balance' => AccountNormalBalance::Credit,
                'account_type' => 'revenue',
            ],
            [
                'code' => '4100',
                'name' => 'Pendapatan Penjualan',
                'parent_code' => '4000',
                'normal_balance' => AccountNormalBalance::Credit,
                'account_type' => 'revenue.sales',
                'mapping' => [
                    'report_type' => ReportType::IncomeStatement,
                    'group_name' => 'Pendapatan',
                    'side' => StatementSide::Revenue,
                    'sign' => -1,
                    'display_order' => 10,
                ],
            ],
            [
                'code' => '5000',
                'name' => 'Beban Operasional',
                'level' => 1,
                'normal_balance' => AccountNormalBalance::Debit,
                'account_type' => 'expense',
            ],
            [
                'code' => '5100',
                'name' => 'Beban Operasional Umum',
                'parent_code' => '5000',
                'normal_balance' => AccountNormalBalance::Debit,
                'account_type' => 'expense.operational',
                'mapping' => [
                    'report_type' => ReportType::IncomeStatement,
                    'group_name' => 'Beban Operasional',
                    'side' => StatementSide::Expense,
                    'sign' => 1,
                    'display_order' => 20,
                ],
            ],
        ];

        $accountIndex = [];
        $asOfDate = Carbon::now()->startOfYear();

        foreach ($accounts as $data) {
            $parentId = null;
            if (! empty($data['parent_code']) && isset($accountIndex[$data['parent_code']])) {
                $parentId = $accountIndex[$data['parent_code']];
            }

            $level = $data['level'] ?? ($parentId ? Account::find($parentId)?->level + 1 : 1);

            $account = Account::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name' => $data['name'],
                    'level' => $level,
                    'parent_id' => $parentId,
                    'normal_balance' => $data['normal_balance'],
                    'account_type' => $data['account_type'],
                    'is_cash_account' => $data['is_cash_account'] ?? false,
                    'description' => $data['description'] ?? null,
                    'is_active' => true,
                ]
            );

            $accountIndex[$account->code] = $account->id;

            if (! empty($data['mapping'])) {
                AccountMapping::updateOrCreate(
                    [
                        'account_id' => $account->id,
                        'report_type' => $data['mapping']['report_type'],
                    ],
                    [
                        'group_name' => $data['mapping']['group_name'],
                        'side' => $data['mapping']['side'],
                        'sign' => $data['mapping']['sign'],
                        'display_order' => $data['mapping']['display_order'],
                    ]
                );
            }

            if (! empty($data['opening_balance'])) {
                AccountOpeningBalance::updateOrCreate(
                    [
                        'account_id' => $account->id,
                        'branch_code' => null,
                        'as_of_date' => $asOfDate,
                    ],
                    [
                        'debit' => $data['opening_balance']['debit'] ?? 0,
                        'credit' => $data['opening_balance']['credit'] ?? 0,
                        'memo' => 'Saldo awal seeded',
                    ]
                );
            }
        }
    }
}
