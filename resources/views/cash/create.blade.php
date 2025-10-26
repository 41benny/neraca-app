<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl leading-tight">Transaksi Kas/Bank</h2>
            <a href="{{ route('journals.index') }}" class="inline-flex items-center px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm dark:bg-gray-800 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Jurnal
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded dark:bg-red-900/30 dark:border-red-800 dark:text-red-300">
                <div class="font-semibold mb-2">Terjadi kesalahan:</div>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('cash.store') }}" enctype="multipart/form-data" class="bg-white dark:bg-dark-bg-secondary border border-gray-100 dark:border-gray-700 rounded-lg shadow p-6 space-y-4">
            @csrf

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Jenis</label>
                    <div class="px-3 py-2 rounded bg-gray-100 dark:bg-gray-800 inline-flex items-center gap-2 text-sm">
                        <span class="inline-block h-2 w-2 rounded-full {{ ($type ?? 'in')==='in' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                        <span>{{ ($type ?? 'in')==='in' ? 'Kas/Bank Masuk' : 'Kas/Bank Keluar' }}</span>
                    </div>
                    <input type="hidden" name="type" value="{{ $type ?? 'in' }}" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Tanggal</label>
                    <input type="date" name="journal_date" value="{{ old('journal_date', now()->toDateString()) }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Akun Kas/Bank</label>
                    @if(isset($selectedCash))
                        <div class="px-3 py-2 rounded bg-gray-100 dark:bg-gray-800 text-sm">
                            {{ $selectedCash->code }} — {{ $selectedCash->name }}
                        </div>
                        <input type="hidden" name="cash_account_id" value="{{ $selectedCash->id }}" />
                    @else
                        <select name="cash_account_id" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                            <option value="">-- Pilih Akun --</option>
                            @foreach($cashAccounts as $acc)
                                <option value="{{ $acc->id }}" @selected(old('cash_account_id')==$acc->id)>{{ $acc->code }} — {{ $acc->name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Jika hanya ada satu akun kas/bank, akan dipilih otomatis.</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Akun Lawan</label>
                    <select name="offset_account_id" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                        <option value="">-- Pilih Akun --</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" @selected(old('offset_account_id')==$acc->id)>{{ $acc->code }} — {{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Nominal</label>
                    <input type="number" name="amount" step="0.01" min="0" value="{{ old('amount') }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">No. Dokumen</label>
                    <input type="text" name="document_no" value="{{ old('document_no') }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Cabang</label>
                    <input type="text" name="branch_code" value="{{ old('branch_code') }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Keterangan</label>
                    <input type="text" name="description" value="{{ old('description') }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Tipe Pihak</label>
                    <select name="party_type" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                        <option value="">-- Pilih --</option>
                        <option value="customer" @selected(old('party_type')==='customer')>Customer</option>
                        <option value="supplier" @selected(old('party_type')==='supplier')>Supplier</option>
                        <option value="other" @selected(old('party_type')==='other')>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Nama Pihak</label>
                    <input type="text" name="party_name" value="{{ old('party_name') }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Lampiran (PDF/JPG/PNG)</label>
                <input type="file" name="attachment" accept=".pdf,image/*" class="block w-full text-sm text-gray-900 dark:text-gray-200" />
            </div>

            <div class="space-y-3">
                <div class="text-sm font-medium text-gray-700 dark:text-gray-200">Split Akun Lawan (opsional)</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Isi beberapa baris di bawah ini jika ingin membagi nominal ke banyak akun lawan. Jika tidak, gunakan bidang "Akun Lawan" dan "Nominal" di atas.</div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left">
                                <th class="px-2 py-2">Akun Lawan</th>
                                <th class="px-2 py-2 w-40">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 5; $i++)
                                <tr>
                                    <td class="px-2 py-1">
                                        <select name="lines[{{ $i }}][offset_account_id]" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                                            <option value="">-- Pilih Akun --</option>
                                            @foreach($accounts as $acc)
                                                <option value="{{ $acc->id }}" @selected(old("lines.$i.offset_account_id")==$acc->id)>{{ $acc->code }} — {{ $acc->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-2 py-1">
                                        <input type="number" step="0.01" min="0" name="lines[{{ $i }}][amount]" value="{{ old("lines.$i.amount") }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary"/>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pt-2">
                <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
