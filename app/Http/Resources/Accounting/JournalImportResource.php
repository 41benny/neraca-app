<?php

namespace App\Http\Resources\Accounting;

use App\Support\DataTransferObjects\JournalImportResultData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read JournalImportResultData $resource
 */
class JournalImportResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'import' => [
                'id' => $this->resource->import->id,
                'batch_name' => $this->resource->import->batch_name,
                'original_filename' => $this->resource->import->original_filename,
                'rows_count' => $this->resource->import->rows_count,
                'imported_at' => optional($this->resource->import->imported_at)->toIso8601String(),
            ],
            'totals' => [
                'debit' => $this->resource->totalDebit,
                'credit' => $this->resource->totalCredit,
                'is_balanced' => $this->resource->isBalanced,
                'created_lines' => $this->resource->createdLines,
            ],
            'rows' => JournalRowResource::collection($this->resource->rows),
        ];
    }
}
