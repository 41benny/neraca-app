<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl leading-tight">Edit Akun: {{ $account->code }}</h2>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm dark:bg-gray-800 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <form method="POST" action="{{ route('accounts.update', $account) }}" class="bg-white dark:bg-dark-bg-secondary border border-gray-100 dark:border-gray-700 rounded-lg shadow p-6 space-y-6">
            @csrf
            @method('PUT')
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Kode</label>
                    <input name="code" required value="{{ $account->code }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Nama</label>
                    <input name="name" required value="{{ $account->name }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Induk</label>
                    <select name="parent_id" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                        <option value="">—</option>
                        @foreach($parents as $p)
                            <option value="{{ $p->id }}" @selected($account->parent_id == $p->id)>{{ $p->code }} — {{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Level</label>
                    <input type="number" name="level" min="1" value="{{ $account->level }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Normal Balance</label>
                    <select name="normal_balance" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary" required>
                        @foreach($normalBalances as $nb)
                            <option value="{{ $nb->value }}" @selected($account->normal_balance->value === $nb->value)>{{ ucfirst($nb->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Tipe Akun</label>
                    <input name="account_type" value="{{ $account->account_type }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_cash_account" value="1" @checked($account->is_cash_account) class="h-4 w-4 border-gray-300 dark:border-gray-700"> <span class="text-sm text-gray-700 dark:text-gray-300">Akun Kas/Bank</span>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Deskripsi</label>
                    <input name="description" value="{{ $account->description }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
            </div>

            <hr class="border-gray-200 dark:border-gray-700">
            <div class="grid md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Report</label>
                    <select name="report_type" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                        <option value="">—</option>
                        @foreach($reportTypes as $rt)
                            <option value="{{ $rt->value }}" @selected(optional($mapping)->report_type?->value === $rt->value)>{{ str_replace('_',' ', ucfirst($rt->value)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Group</label>
                    <input name="group_name" value="{{ optional($mapping)->group_name }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Side</label>
                    <select name="side" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                        <option value="">—</option>
                        @foreach($sides as $sd)
                            <option value="{{ $sd->value }}" @selected(optional($mapping)->side?->value === $sd->value)>{{ ucfirst($sd->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Sign</label>
                    <select name="sign" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                        <option value="1" @selected(optional($mapping)->sign === 1)>+1</option>
                        <option value="-1" @selected(optional($mapping)->sign === -1)>-1</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Urutan</label>
                    <input type="number" name="display_order" min="0" value="{{ optional($mapping)->display_order ?? 0 }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
            </div>

            <hr class="border-gray-200 dark:border-gray-700">
            <div class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Saldo Awal Debit</label>
                    <input type="number" step="0.01" name="opening_debit" value="{{ optional($opening)->debit }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Saldo Awal Kredit</label>
                    <input type="number" step="0.01" name="opening_credit" value="{{ optional($opening)->credit }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Per Tanggal</label>
                    <input type="date" name="opening_as_of" value="{{ optional($opening)->as_of_date?->toDateString() ?? now()->startOfYear()->toDateString() }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Cabang</label>
                    <input name="branch_code" value="{{ optional($opening)->branch_code }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
