<?php

namespace App\Http\Resources\Accounting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatementGroupResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource['name'],
            'side' => $this->resource['side'],
            'total' => $this->resource['total'],
            'signed_total' => $this->resource['signed_total'],
            'accounts' => collect($this->resource['accounts'])
                ->map(fn (array $account) => (new StatementAccountResource($account))->toArray($request))
                ->all(),
        ];
    }
}
