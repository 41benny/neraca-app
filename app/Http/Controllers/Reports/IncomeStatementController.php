<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\IncomeStatementRequest;
use App\Http\Resources\Accounting\IncomeStatementResource;
use App\Services\FinancialReportService;
use Illuminate\Support\Carbon;

class IncomeStatementController extends Controller
{
    public function __construct(private readonly FinancialReportService $financialReportService) {}

    public function __invoke(IncomeStatementRequest $request): IncomeStatementResource
    {
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $filters = $request->only([
            'branch_code',
            'invoice_id',
            'project_id',
            'vehicle_id',
        ]);

        $data = $this->financialReportService->incomeStatement(
            startDate: $startDate,
            endDate: $endDate,
            filters: $filters,
        );

        return new IncomeStatementResource($data);
    }
}
