<?php

namespace App\Support\DataTransferObjects;

use App\Models\JournalImport;
use Illuminate\Support\Collection;

class JournalImportResultData
{
    /**
     * @param  Collection<int, JournalRowData>  $rows
     */
    public function __construct(
        public readonly JournalImport $import,
        public readonly Collection $rows,
        public readonly int $createdLines,
        public readonly float $totalDebit,
        public readonly float $totalCredit,
        public readonly bool $isBalanced,
    ) {}
}
