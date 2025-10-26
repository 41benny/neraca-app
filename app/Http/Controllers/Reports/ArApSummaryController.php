<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArApSummaryRequest;
use App\Models\Account;
use App\Models\JournalLine;
use Illuminate\Contracts\View\View;

class ArApSummaryController extends Controller
{
    public function __invoke(ArApSummaryRequest $request): View
    {
        $type = $request->input('type', 'ar');
        $start = $request->date('start_date') ?? now()->startOfYear();
        $end = $request->date('end_date') ?? now();

        $controlQuery = Account::query()->orderBy('code');
        if ($type === 'ar') {
            $controlQuery->where('account_type', 'asset.receivable');
        } elseif ($type === 'ap') {
            $controlQuery->where('account_type', 'liability.payable');
        } else {
            $controlQuery->whereIn('account_type', ['asset.receivable', 'liability.payable']);
        }
        $controlAccounts = $controlQuery->get(['id', 'code', 'name', 'normal_balance', 'account_type']);

        $accountIds = $request->filled('account_id')
            ? [(int) $request->input('account_id')]
            : $controlAccounts->pluck('id')->all();

        $query = JournalLine::query()
            ->selectRaw('account_id, party_type, party_code, party_name, invoice_id, SUM(debit) as debit_sum, SUM(credit) as credit_sum')
            ->whereIn('account_id', $accountIds)
            ->whereBetween('journal_date', [$start->toDateString(), $end->toDateString()])
            ->groupBy('account_id', 'party_type', 'party_code', 'party_name', 'invoice_id');

        if ($request->filled('party')) {
            $party = $request->string('party');
            $query->where(function ($w) use ($party) {
                $w->where('party_code', 'like', "%{$party}%")
                    ->orWhere('party_name', 'like', "%{$party}%");
            });
        }
        if ($request->filled('invoice_id')) {
            $query->where('invoice_id', $request->string('invoice_id'));
        }

        $rows = $query->get();

        $accountById = $controlAccounts->keyBy('id');

        $data = $rows->map(function ($r) use ($accountById) {
            $acc = $accountById->get($r->account_id);
            $net = (float) $r->debit_sum - (float) $r->credit_sum;
            $normalized = $acc ? $net * ($acc->normalBalanceMultiplier()) : $net;

            return [
                'account' => $acc,
                'party_type' => $r->party_type,
                'party_code' => $r->party_code,
                'party_name' => $r->party_name,
                'invoice_id' => $r->invoice_id,
                'debit' => (float) $r->debit_sum,
                'credit' => (float) $r->credit_sum,
                'net' => round($net, 2),
                'outstanding' => round($normalized, 2),
            ];
        })->sortByDesc('outstanding')->values();

        $totals = [
            'debit' => round($rows->sum('debit_sum'), 2),
            'credit' => round($rows->sum('credit_sum'), 2),
            'outstanding' => round($data->sum('outstanding'), 2),
        ];

        return view('reports.ar-ap-summary', [
            'data' => $data,
            'totals' => $totals,
            'controls' => $controlAccounts,
            'filters' => [
                'type' => $type,
                'account_id' => $request->input('account_id'),
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'party' => $request->input('party'),
                'invoice_id' => $request->input('invoice_id'),
            ],
        ]);
    }
}
