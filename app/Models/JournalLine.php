<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalLine extends Model
{
    /** @use HasFactory<\Database\Factories\JournalLineFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'journal_import_id',
        'account_id',
        'journal_date',
        'document_no',
        'description',
        'debit',
        'credit',
        'branch_code',
        'invoice_id',
        'project_id',
        'vehicle_id',
        'meta',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'journal_date' => 'date',
            'debit' => 'decimal:2',
            'credit' => 'decimal:2',
            'meta' => 'array',
        ];
    }

    public function import(): BelongsTo
    {
        return $this->belongsTo(JournalImport::class, 'journal_import_id');
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
