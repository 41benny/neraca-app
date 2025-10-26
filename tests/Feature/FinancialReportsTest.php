<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class FinancialReportsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2025-01-31');
        $this->seed(DatabaseSeeder::class);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_balance_sheet_returns_assets_equal_liabilities_equity(): void
    {
        $response = $this->getJson(route('reports.balance-sheet', [
            'as_of' => '2025-01-31',
        ]));

        $response->assertOk()->assertJson(fn (AssertableJson $json) => $json
            ->where('meta.report', 'balance_sheet')
            ->has('groups')
            ->has('totals.assets')
            ->has('totals.liabilities_equity')
            ->etc()
        );

        $assets = $response->json('totals.assets');
        $liabilitiesEquity = $response->json('totals.liabilities_equity');

        $this->assertEqualsWithDelta($assets, $liabilitiesEquity, 0.01);
    }

    public function test_income_statement_returns_expected_net_income(): void
    {
        $response = $this->getJson(route('reports.income-statement', [
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-31',
        ]));

        $response->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('meta.report', 'income_statement')
                ->where('totals.net_income', 30000000)
                ->etc()
            );
    }
}
