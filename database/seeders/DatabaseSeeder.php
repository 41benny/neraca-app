<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\JournalImport;
use App\Models\JournalLine;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ChartOfAccountsSeeder::class,
        ]);

        $user = User::firstOrCreate(
            [
                'email' => 'admin@neraca-app.test',
            ],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        $entries = [
            [
                'account_code' => '1200',
                'debit' => 50_000_000,
                'credit' => 0,
                'description' => 'Penjualan kredit Januari',
                'journal_date' => Carbon::now()->startOfMonth(),
                'branch_code' => 'JKT',
                'invoice_id' => 'INV-001',
                'project_id' => null,
                'vehicle_id' => null,
            ],
            [
                'account_code' => '4100',
                'debit' => 0,
                'credit' => 50_000_000,
                'description' => 'Pendapatan penjualan Januari',
                'journal_date' => Carbon::now()->startOfMonth(),
                'branch_code' => 'JKT',
                'invoice_id' => 'INV-001',
                'project_id' => null,
                'vehicle_id' => null,
            ],
            [
                'account_code' => '5100',
                'debit' => 20_000_000,
                'credit' => 0,
                'description' => 'Beban operasional bulanan',
                'journal_date' => Carbon::now()->startOfMonth()->addDays(5),
                'branch_code' => 'JKT',
                'invoice_id' => 'BEB-001',
                'project_id' => null,
                'vehicle_id' => 'VH-01',
            ],
            [
                'account_code' => '1100',
                'debit' => 0,
                'credit' => 20_000_000,
                'description' => 'Pembayaran beban operasional',
                'journal_date' => Carbon::now()->startOfMonth()->addDays(5),
                'branch_code' => 'JKT',
                'invoice_id' => 'BEB-001',
                'project_id' => null,
                'vehicle_id' => 'VH-01',
            ],
        ];

        $totalDebit = collect($entries)->sum('debit');

        $import = JournalImport::updateOrCreate(
            [
                'batch_name' => 'Contoh Upload Januari',
            ],
            [
                'original_filename' => 'contoh_jurnal.xlsx',
                'user_id' => $user->id,
                'rows_count' => count($entries),
                'total_debit' => $totalDebit,
                'total_credit' => $totalDebit,
                'status' => 'completed',
                'context' => ['seeded' => true],
                'imported_at' => now(),
            ]
        );

        foreach ($entries as $entry) {
            $account = Account::where('code', $entry['account_code'])->first();

            if (! $account) {
                continue;
            }

            JournalLine::updateOrCreate(
                [
                    'journal_import_id' => $import->id,
                    'account_id' => $account->id,
                    'journal_date' => $entry['journal_date'],
                    'document_no' => $entry['invoice_id'],
                ],
                [
                    'description' => $entry['description'],
                    'debit' => $entry['debit'],
                    'credit' => $entry['credit'],
                    'branch_code' => $entry['branch_code'],
                    'invoice_id' => $entry['invoice_id'],
                    'project_id' => $entry['project_id'],
                    'vehicle_id' => $entry['vehicle_id'],
                    'meta' => ['seeded' => true],
                ]
            );
        }
    }
}
