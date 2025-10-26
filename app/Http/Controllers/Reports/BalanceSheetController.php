<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\BalanceSheetRequest;
use App\Http\Resources\Accounting\BalanceSheetResource;
use App\Services\FinancialReportService;
use Illuminate\Support\Carbon;

class BalanceSheetController extends Controller
{
    public function __construct(private readonly FinancialReportService $financialReportService) {}

    public function __invoke(BalanceSheetRequest $request): BalanceSheetResource
    {
        $asOf = Carbon::parse($request->input('as_of'));

        $data = $this->financialReportService->balanceSheet(
            asOf: $asOf,
            branchCode: $request->input('branch_code')
        );

        return new BalanceSheetResource($data);
    }
}
