<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateJournalLineRequest;
use App\Models\Account;
use App\Models\JournalImport;
use App\Models\JournalLine;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JournalLineController extends Controller
{
    public function index(): View
    {
        $importId = request('import_id');
        $q = request('q');

        /** @var LengthAwarePaginator $lines */
        $lines = JournalLine::query()
            ->with(['account', 'import'])
            ->when($importId, fn ($q2) => $q2->where('journal_import_id', $importId))
            ->when($q, function ($w) use ($q) {
                $w->where('description', 'like', "%{$q}%")
                    ->orWhere('document_no', 'like', "%{$q}%");
            })
            ->orderByDesc('journal_date')
            ->paginate(20)
            ->withQueryString();

        $imports = JournalImport::query()->orderByDesc('id')->get(['id', 'batch_name']);

        return view('journals.index', [
            'lines' => $lines,
            'imports' => $imports,
        ]);
    }

    public function edit(JournalLine $journalLine): View
    {
        return view('journals.edit', [
            'line' => $journalLine->load(['account', 'import']),
            'accounts' => Account::query()->orderBy('code')->get(['id', 'code', 'name']),
        ]);
    }

    public function update(UpdateJournalLineRequest $request, JournalLine $journalLine): RedirectResponse
    {
        $journalLine->update([
            'journal_date' => $request->date('journal_date')->toDateString(),
            'account_id' => $request->integer('account_id'),
            'document_no' => $request->input('document_no'),
            'description' => $request->input('description'),
            'debit' => (float) $request->input('debit', 0),
            'credit' => (float) $request->input('credit', 0),
            'branch_code' => $request->input('branch_code'),
            'invoice_id' => $request->input('invoice_id'),
            'project_id' => $request->input('project_id'),
            'vehicle_id' => $request->input('vehicle_id'),
            'party_type' => $request->input('party_type'),
            'party_code' => $request->input('party_code'),
            'party_name' => $request->input('party_name'),
        ]);

        return redirect()->route('journals.index')->with('status', 'Baris jurnal diperbarui.');
    }
}
