<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\JournalImport;
use App\Models\JournalLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JournalLine>
 */
class JournalLineFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isDebit = fake()->boolean();
        $amount = fake()->randomFloat(2, 100, 1000);

        return [
            'journal_import_id' => JournalImport::factory(),
            'account_id' => Account::factory(),
            'journal_date' => fake()->dateTimeBetween('-2 months'),
            'document_no' => 'DOC-'.fake()->numerify('#####'),
            'description' => fake()->sentence(),
            'debit' => $isDebit ? $amount : 0,
            'credit' => $isDebit ? 0 : $amount,
            'branch_code' => fake()->randomElement(['JKT', 'SBY', 'BDG']),
            'invoice_id' => fake()->optional()->numerify('INV#####'),
            'project_id' => fake()->optional()->lexify('PROJ???'),
            'vehicle_id' => fake()->optional()->lexify('VH??'),
            'meta' => [
                'source' => 'factory',
            ],
        ];
    }
}
