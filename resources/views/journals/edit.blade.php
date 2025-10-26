<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl leading-tight">Edit Jurnal #{{ $line->id }}</h2>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm dark:bg-gray-800 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white dark:bg-dark-bg-secondary border border-gray-100 dark:border-gray-700 rounded-lg shadow p-6">
            <form method="POST" action="{{ route('journals.update', $line) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Tanggal</label>
                        <input type="date" name="journal_date" value="{{ $line->journal_date->toDateString() }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Akun</label>
                        <select name="account_id" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary" required>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}" @selected($line->account_id === $acc->id)>{{ $acc->code }} — {{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Dokumen</label>
                        <input name="document_no" value="{{ $line->document_no }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Keterangan</label>
                        <input name="description" value="{{ $line->description }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Debit</label>
                        <input type="number" step="0.01" name="debit" value="{{ $line->debit }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Kredit</label>
                        <input type="number" step="0.01" name="credit" value="{{ $line->credit }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                    </div>
                </div>

                <div class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Cabang</label>
                        <input name="branch_code" value="{{ $line->branch_code }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Invoice</label>
                        <input name="invoice_id" value="{{ $line->invoice_id }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Proyek</label>
                        <input name="project_id" value="{{ $line->project_id }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Kendaraan</label>
                        <input name="vehicle_id" value="{{ $line->vehicle_id }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Tipe Pihak</label>
                        <select name="party_type" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                            <option value="">—</option>
                            <option value="customer" @selected($line->party_type === 'customer')>Customer</option>
                            <option value="vendor" @selected($line->party_type === 'vendor')>Vendor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Kode Pihak</label>
                        <input name="party_code" value="{{ $line->party_code }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Nama Pihak</label>
                        <input name="party_name" value="{{ $line->party_name }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
