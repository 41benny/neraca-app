<?php

namespace App\Http\Resources\Accounting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatementAccountResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->resource['code'],
            'name' => $this->resource['name'],
            'amount' => $this->resource['amount'],
            'signed_amount' => $this->resource['signed_amount'],
        ];
    }
}
