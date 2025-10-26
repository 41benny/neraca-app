<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl leading-tight">Import COA & Saldo Awal</h2>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm dark:bg-gray-800 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        @if (session('status'))
            <div class="p-4 bg-green-50 dark:bg-emerald-900/30 border border-green-200 dark:border-emerald-800 text-green-700 dark:text-emerald-300 rounded">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white dark:bg-dark-bg-secondary border border-gray-100 dark:border-gray-700 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4 gap-2">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Unggah file Excel (XLS/XLSX/CSV) berisi COA, mapping laporan, dan saldo awal.
                </p>
                <a href="{{ route('templates.coa.xlsx') }}" class="px-3 py-2 bg-primary-600 text-white rounded">Download Template Excel (XLSX)</a>
            </div>
            <form method="POST" action="{{ route('accounts.imports.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">File COA</label>
                    <input type="file" name="file" accept=".xls,.xlsx,.csv" required class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Import</button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-dark-bg-secondary border border-gray-100 dark:border-gray-700 rounded-lg shadow p-6">
            <h3 class="font-semibold mb-2">Kolom & Tipe Data</h3>
            <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-300">
                <div>
                    <p class="font-medium">Wajib</p>
                    <ul class="list-disc ml-5">
                        <li><strong>code</strong> — text (unik)</li>
                        <li><strong>name</strong> — text</li>
                        <li><strong>normal_balance</strong> — enum: debit|credit</li>
                        <li><strong>account_type</strong> — text (mis. asset.cash, liability.payable)</li>
                    </ul>
                </div>
                <div>
                    <p class="font-medium">Opsional</p>
                    <ul class="list-disc ml-5">
                        <li>parent_code — text; level — number</li>
                        <li>is_cash_account — boolean (1/0)</li>
                        <li>description — text</li>
                        <li>report_type — enum: balance_sheet|income_statement|cash_flow</li>
                        <li>group_name — text; side — enum: asset|liability|equity|revenue|expense; sign — number (1/-1); display_order — number</li>
                        <li>opening_debit — number; opening_credit — number; opening_as_of — date (YYYY-MM-DD); branch_code — text</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
