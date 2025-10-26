<?php

namespace Tests\Feature;

use Database\Seeders\ChartOfAccountsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class JournalImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_journal_rows_via_csv(): void
    {
        $this->seed(ChartOfAccountsSeeder::class);

        $csv = <<<'CSV'
date,account_code,debit,credit,description,branch,invoice_id,project_id,vehicle_id
2025-01-05,1100,1000000,0,"Kas bertambah","JKT","INV-1001","PRJ-01","VH-01"
2025-01-05,4100,0,1000000,"Pendapatan penjualan","JKT","INV-1001","PRJ-01","VH-01"
CSV;

        $file = UploadedFile::fake()->createWithContent('journal.csv', $csv);

        $response = $this->post(route('journal-imports.store'), [
            'file' => $file,
            'import_name' => 'Batch Uji',
            'imported_at' => '2025-01-05 08:00:00',
        ], ['Accept' => 'application/json']);

        $response
            ->assertCreated()
            ->assertJsonPath('data.import.batch_name', 'Batch Uji')
            ->assertJsonPath('data.totals.debit', 1000000)
            ->assertJsonPath('data.totals.debit', $response->json('data.totals.credit'));

        $this->assertDatabaseHas('journal_imports', [
            'batch_name' => 'Batch Uji',
            'rows_count' => 2,
        ]);

        $this->assertDatabaseCount('journal_lines', 2);
    }
}
