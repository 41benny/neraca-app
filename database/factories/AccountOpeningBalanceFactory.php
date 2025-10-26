<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\AccountOpeningBalance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AccountOpeningBalance>
 */
class AccountOpeningBalanceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isDebit = fake()->boolean();
        $amount = fake()->randomFloat(2, 1000, 200000);

        return [
            'account_id' => Account::factory(),
            'branch_code' => fake()->randomElement([null, 'JKT', 'SBY', 'BDG']),
            'as_of_date' => now()->startOfYear(),
            'debit' => $isDebit ? $amount : 0,
            'credit' => $isDebit ? 0 : $amount,
            'memo' => 'Saldo awal',
        ];
    }
}
