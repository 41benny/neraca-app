<?php

namespace Database\Factories;

use App\Models\JournalImport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JournalImport>
 */
class JournalImportFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rows = fake()->numberBetween(5, 50);
        $total = $rows * fake()->randomFloat(2, 100, 500);

        return [
            'batch_name' => 'Import '.fake()->monthName(),
            'original_filename' => 'journal_'.fake()->uuid().'.xlsx',
            'user_id' => User::factory(),
            'rows_count' => $rows,
            'total_debit' => $total,
            'total_credit' => $total,
            'status' => 'completed',
            'context' => ['notes' => 'Factory generated'],
            'imported_at' => now(),
        ];
    }
}
