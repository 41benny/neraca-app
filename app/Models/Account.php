<?php

namespace App\Models;

use App\Enums\AccountNormalBalance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    /** @use HasFactory<\Database\Factories\AccountFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'level',
        'parent_id',
        'normal_balance',
        'account_type',
        'is_cash_account',
        'is_active',
        'description',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_cash_account' => 'bool',
            'is_active' => 'bool',
            'normal_balance' => AccountNormalBalance::class,
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function openingBalances(): HasMany
    {
        return $this->hasMany(AccountOpeningBalance::class);
    }

    public function journalLines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }

    public function mappings(): HasMany
    {
        return $this->hasMany(AccountMapping::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function normalBalanceMultiplier(): int
    {
        $balance = $this->normal_balance instanceof AccountNormalBalance
            ? $this->normal_balance
            : AccountNormalBalance::from($this->normal_balance);

        return $balance->multiplier();
    }
}
