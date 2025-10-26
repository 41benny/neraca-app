<?php

namespace Database\Factories;

use App\Enums\AccountNormalBalance;
use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $normalBalance = fake()->randomElement(AccountNormalBalance::cases());

        return [
            'code' => (string) fake()->unique()->numerify('1###'),
            'name' => fake()->company().' Account',
            'level' => 1,
            'parent_id' => null,
            'normal_balance' => $normalBalance,
            'account_type' => fake()->randomElement(['asset', 'liability', 'equity', 'revenue', 'expense']),
            'is_cash_account' => false,
            'is_active' => true,
            'description' => fake()->sentence(),
        ];
    }
}
