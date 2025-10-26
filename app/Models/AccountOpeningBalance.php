<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountOpeningBalance extends Model
{
    /** @use HasFactory<\Database\Factories\AccountOpeningBalanceFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'account_id',
        'branch_code',
        'as_of_date',
        'debit',
        'credit',
        'memo',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'as_of_date' => 'date',
            'debit' => 'decimal:2',
            'credit' => 'decimal:2',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function netAmount(): float
    {
        return (float) $this->debit - (float) $this->credit;
    }
}
