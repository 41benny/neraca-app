<?php

namespace App\Models;

use App\Enums\ReportType;
use App\Enums\StatementSide;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountMapping extends Model
{
    /** @use HasFactory<\Database\Factories\AccountMappingFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'account_id',
        'report_type',
        'group_name',
        'side',
        'sign',
        'display_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'report_type' => ReportType::class,
            'side' => StatementSide::class,
            'sign' => 'integer',
            'display_order' => 'integer',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
