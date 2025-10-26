<?php

namespace App\Http\Resources\Accounting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeStatementResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'meta' => $this->resource['meta'],
            'groups' => collect($this->resource['groups'])
                ->map(fn (array $group) => (new StatementGroupResource($group))->toArray($request))
                ->all(),
            'totals' => $this->resource['totals'],
        ];
    }
}
