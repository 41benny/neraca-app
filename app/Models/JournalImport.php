<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalImport extends Model
{
    /** @use HasFactory<\Database\Factories\JournalImportFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'batch_name',
        'original_filename',
        'user_id',
        'rows_count',
        'total_debit',
        'total_credit',
        'status',
        'context',
        'imported_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_debit' => 'decimal:2',
            'total_credit' => 'decimal:2',
            'context' => 'array',
            'imported_at' => 'datetime',
        ];
    }

    public function importer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }

    public function isBalanced(): bool
    {
        return (float) $this->total_debit === (float) $this->total_credit;
    }
}
