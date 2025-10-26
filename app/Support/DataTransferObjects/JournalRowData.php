<?php

namespace App\Support\DataTransferObjects;

use Illuminate\Support\Carbon;

class JournalRowData
{
    /**
     * @param  array<string, mixed>  $meta
     */
    public function __construct(
        public readonly Carbon $journalDate,
        public readonly string $accountCode,
        public readonly ?string $description,
        public readonly float $debit,
        public readonly float $credit,
        public readonly ?string $branchCode = null,
        public readonly ?string $invoiceId = null,
        public readonly ?string $projectId = null,
        public readonly ?string $vehicleId = null,
        public readonly array $meta = [],
    ) {}
}
