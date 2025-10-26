<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCashTransactionRequest;
use App\Models\Account;
use App\Models\JournalImport;
use App\Models\JournalLine;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CashTransactionController extends Controller
{
    public function create(\Illuminate\Http\Request $request): View
    {
        $cashAccounts = Account::query()->where('is_cash_account', true)->orderBy('code')->get(['id', 'code', 'name']);
        $selectedCash = $cashAccounts->count() === 1 ? $cashAccounts->first() : null;

        return view('cash.create', [
            'type' => $request->string('type')->toString() ?: 'in',
            'cashAccounts' => $cashAccounts,
            'selectedCash' => $selectedCash,
            'accounts' => Account::query()->orderBy('code')->get(['id', 'code', 'name']),
        ]);
    }

    public function store(StoreCashTransactionRequest $request): RedirectResponse
    {
        $type = $request->string('type')->toString();
        $date = $request->date('journal_date');
        $cashAccountId = $request->integer('cash_account_id');
        $offsetAccountId = $request->integer('offset_account_id');
        $amount = (float) $request->input('amount');
        $documentNo = $request->input('document_no');
        $description = $request->input('description') ?: ($type === 'in' ? 'Kas/Bank Masuk' : 'Kas/Bank Keluar');
        $branch = $request->input('branch_code');
        $partyType = $request->input('party_type');
        $partyCode = null; // do not collect party code per request
        $partyName = $request->input('party_name');

        $import = DB::transaction(function () use ($request, $type, $date, $cashAccountId, $offsetAccountId, $amount, $documentNo, $description, $branch, $partyType, $partyCode, $partyName): JournalImport {
            // Resolve default cash/bank account if not provided
            $resolvedCashId = $cashAccountId ?: (int) Account::query()->where('is_cash_account', true)->orderBy('code')->value('id');
            if (! $resolvedCashId) {
                throw ValidationException::withMessages([
                    'cash_account_id' => 'Belum ada akun kas/bank aktif. Buat atau tandai satu akun sebagai kas/bank.',
                ]);
            }
            $splitLines = collect($request->input('lines', []))
                ->filter(fn ($l) => ($l['offset_account_id'] ?? null) && (float) ($l['amount'] ?? 0) > 0)
                ->map(fn ($l) => [
                    'offset_account_id' => (int) $l['offset_account_id'],
                    'amount' => round((float) $l['amount'], 2),
                ])
                ->values();

            $useSplit = $splitLines->isNotEmpty();
            $total = $useSplit ? $splitLines->sum('amount') : round($amount, 2);

            // Store attachment if any
            $attachment = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $path = $file->store('attachments', 'public');
                $attachment = [
                    'disk' => 'public',
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ];
            }

            $import = JournalImport::create([
                'batch_name' => ($type === 'in' ? 'Kas Masuk' : 'Kas Keluar').($documentNo ? ' - '.$documentNo : ''),
                'original_filename' => 'manual-cash',
                'user_id' => $request->user()?->id,
                'rows_count' => $useSplit ? (1 + $splitLines->count()) : 2,
                'total_debit' => $total,
                'total_credit' => $total,
                'status' => 'completed',
                'context' => array_filter([
                    'source' => 'manual',
                    'transaction' => 'cash',
                    'type' => $type,
                    'attachment' => $attachment,
                ]),
                'imported_at' => $date,
            ]);

            $timestamp = now();

            $lines = [];

            // Build offset lines (possibly multiple)
            $offsets = $useSplit ? $splitLines : collect([
                ['offset_account_id' => $offsetAccountId, 'amount' => $total],
            ]);

            foreach ($offsets as $idx => $row) {
                if ($type === 'in') {
                    // credit offset
                    $lines[] = [
                        'journal_import_id' => $import->id,
                        'account_id' => (int) $row['offset_account_id'],
                        'journal_date' => $date->toDateString(),
                        'document_no' => $documentNo,
                        'description' => $description,
                        'debit' => 0,
                        'credit' => $row['amount'],
                        'branch_code' => $branch,
                        'party_type' => $partyType,
                        'party_code' => $partyCode,
                        'party_name' => $partyName,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ];
                } else {
                    // debit offset
                    $lines[] = [
                        'journal_import_id' => $import->id,
                        'account_id' => (int) $row['offset_account_id'],
                        'journal_date' => $date->toDateString(),
                        'document_no' => $documentNo,
                        'description' => $description,
                        'debit' => $row['amount'],
                        'credit' => 0,
                        'branch_code' => $branch,
                        'party_type' => $partyType,
                        'party_code' => $partyCode,
                        'party_name' => $partyName,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ];
                }
            }

            // Single cash line (sum of offsets)
            if ($type === 'in') {
                $lines[] = [
                    'journal_import_id' => $import->id,
                    'account_id' => $resolvedCashId,
                    'journal_date' => $date->toDateString(),
                    'document_no' => $documentNo,
                    'description' => $description,
                    'debit' => $total,
                    'credit' => 0,
                    'branch_code' => $branch,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            } else {
                $lines[] = [
                    'journal_import_id' => $import->id,
                    'account_id' => $resolvedCashId,
                    'journal_date' => $date->toDateString(),
                    'document_no' => $documentNo,
                    'description' => $description,
                    'debit' => 0,
                    'credit' => $total,
                    'branch_code' => $branch,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }

            JournalLine::insert($lines);

            return $import;
        });

        return redirect()
            ->route('journals.index', ['import_id' => $import->id])
            ->with('status', 'Transaksi kas/bank berhasil dibuat.');
    }
}
