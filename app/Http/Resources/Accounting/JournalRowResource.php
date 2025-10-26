<?php

namespace App\Http\Resources\Accounting;

use App\Support\DataTransferObjects\JournalRowData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read JournalRowData $resource
 */
class JournalRowResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'journal_date' => $this->resource->journalDate->toDateString(),
            'account_code' => $this->resource->accountCode,
            'description' => $this->resource->description,
            'debit' => $this->resource->debit,
            'credit' => $this->resource->credit,
            'branch_code' => $this->resource->branchCode,
            'invoice_id' => $this->resource->invoiceId,
            'project_id' => $this->resource->projectId,
            'vehicle_id' => $this->resource->vehicleId,
            'meta' => $this->resource->meta,
        ];
    }
}
