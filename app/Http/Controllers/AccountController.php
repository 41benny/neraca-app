<?php

namespace App\Http\Controllers;

use App\Enums\AccountNormalBalance;
use App\Enums\ReportType;
use App\Enums\StatementSide;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use App\Models\AccountMapping;
use App\Models\AccountOpeningBalance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $q = request('q');

        /** @var LengthAwarePaginator $accounts */
        $accounts = Account::query()
            ->with('mappings')
            ->withSum('openingBalances as opening_debit_sum', 'debit')
            ->withSum('openingBalances as opening_credit_sum', 'credit')
            ->when($q, fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('code', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%");
            }))
            ->orderBy('code')
            ->paginate(20)
            ->withQueryString();

        return view('accounts.index', [
            'accounts' => $accounts,
        ]);
    }

    public function create(): View
    {
        return view('accounts.create', [
            'normalBalances' => AccountNormalBalance::cases(),
            'reportTypes' => ReportType::cases(),
            'sides' => StatementSide::cases(),
            'parents' => Account::query()->orderBy('code')->get(['id', 'code', 'name']),
        ]);
    }

    public function store(StoreAccountRequest $request): RedirectResponse
    {
        $parentId = $request->integer('parent_id') ?: null;
        $level = $request->integer('level') ?: ($parentId ? (optional(Account::find($parentId))->level + 1) : 1);

        $account = Account::create([
            'code' => $request->string('code'),
            'name' => $request->string('name'),
            'level' => $level,
            'parent_id' => $parentId,
            'normal_balance' => $request->enum('normal_balance', AccountNormalBalance::class)->value,
            'account_type' => $request->string('account_type')->toString(),
            'is_cash_account' => $request->boolean('is_cash_account'),
            'description' => $request->input('description'),
            'is_active' => true,
        ]);

        if ($request->filled('report_type') && $request->filled('group_name') && $request->filled('side')) {
            AccountMapping::updateOrCreate(
                ['account_id' => $account->id, 'report_type' => $request->enum('report_type', ReportType::class)->value],
                [
                    'group_name' => $request->string('group_name'),
                    'side' => $request->enum('side', StatementSide::class)->value,
                    'sign' => $request->integer('sign', 1),
                    'display_order' => $request->integer('display_order', 0),
                ]
            );
        }

        if ($request->filled('opening_debit') || $request->filled('opening_credit')) {
            AccountOpeningBalance::updateOrCreate(
                [
                    'account_id' => $account->id,
                    'branch_code' => $request->input('branch_code'),
                    'as_of_date' => $request->date('opening_as_of', now()->startOfYear())->toDateString(),
                ],
                [
                    'debit' => (float) $request->input('opening_debit', 0),
                    'credit' => (float) $request->input('opening_credit', 0),
                    'memo' => 'Saldo awal (manual)',
                ]
            );
        }

        return redirect()->route('accounts.index')->with('status', 'Akun berhasil dibuat.');
    }

    public function edit(Account $account): View
    {
        $mapping = $account->mappings()->first();
        $opening = $account->openingBalances()->latest('as_of_date')->first();

        return view('accounts.edit', [
            'account' => $account,
            'mapping' => $mapping,
            'opening' => $opening,
            'normalBalances' => AccountNormalBalance::cases(),
            'reportTypes' => ReportType::cases(),
            'sides' => StatementSide::cases(),
            'parents' => Account::query()->where('id', '!=', $account->id)->orderBy('code')->get(['id', 'code', 'name']),
        ]);
    }

    public function update(UpdateAccountRequest $request, Account $account): RedirectResponse
    {
        $parentId = $request->integer('parent_id') ?: null;
        $level = $request->integer('level') ?: ($parentId ? (optional(Account::find($parentId))->level + 1) : 1);

        $account->update([
            'code' => $request->string('code'),
            'name' => $request->string('name'),
            'level' => $level,
            'parent_id' => $parentId,
            'normal_balance' => $request->enum('normal_balance', AccountNormalBalance::class)->value,
            'account_type' => $request->string('account_type')->toString(),
            'is_cash_account' => $request->boolean('is_cash_account'),
            'description' => $request->input('description'),
        ]);

        if ($request->filled('report_type') && $request->filled('group_name') && $request->filled('side')) {
            AccountMapping::updateOrCreate(
                ['account_id' => $account->id, 'report_type' => $request->enum('report_type', ReportType::class)->value],
                [
                    'group_name' => $request->string('group_name'),
                    'side' => $request->enum('side', StatementSide::class)->value,
                    'sign' => $request->integer('sign', 1),
                    'display_order' => $request->integer('display_order', 0),
                ]
            );
        }

        if ($request->filled('opening_debit') || $request->filled('opening_credit')) {
            AccountOpeningBalance::updateOrCreate(
                [
                    'account_id' => $account->id,
                    'branch_code' => $request->input('branch_code'),
                    'as_of_date' => $request->date('opening_as_of', now()->startOfYear())->toDateString(),
                ],
                [
                    'debit' => (float) $request->input('opening_debit', 0),
                    'credit' => (float) $request->input('opening_credit', 0),
                    'memo' => 'Saldo awal (manual)',
                ]
            );
        }

        return redirect()->route('accounts.index')->with('status', 'Akun berhasil diperbarui.');
    }
}
