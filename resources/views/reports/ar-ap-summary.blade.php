<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl leading-tight">Ringkasan AR/AP</h2>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm dark:bg-gray-800 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        <div class="bg-white dark:bg-dark-bg-secondary border border-gray-100 dark:border-gray-700 rounded-lg shadow p-4">
            <form method="GET" action="{{ route('reports.ar-ap') }}" class="grid grid-cols-1 md:grid-cols-8 gap-3">
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Jenis</label>
                    <select name="type" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                        <option value="ar" @selected(($filters['type'] ?? 'ar') === 'ar')>Piutang (AR)</option>
                        <option value="ap" @selected(($filters['type'] ?? 'ar') === 'ap')>Hutang (AP)</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Akun Kontrol</label>
                    <select name="account_id" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                        <option value="">Semua</option>
                        @foreach($controls as $acc)
                            <option value="{{ $acc->id }}" @selected(($filters['account_id'] ?? null) == $acc->id)>{{ $acc->code }} — {{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Dari</label>
                    <input type="date" name="start_date" value="{{ $filters['start_date'] ?? now()->startOfYear()->toDateString() }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Sampai</label>
                    <input type="date" name="end_date" value="{{ $filters['end_date'] ?? now()->toDateString() }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Pihak</label>
                    <input type="text" name="party" value="{{ $filters['party'] ?? '' }}" placeholder="Kode/Nama" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Invoice</label>
                    <input type="text" name="invoice_id" value="{{ $filters['invoice_id'] ?? '' }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                </div>
                <div class="flex items-end">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded">Terapkan</button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-dark-bg-secondary border border-gray-100 dark:border-gray-700 rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold">Pihak</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold">Invoice</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold">Akun</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold">Total Debit</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold">Total Kredit</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold">Outstanding</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($data as $row)
                            <tr>
                                <td class="px-4 py-2 text-sm">{{ $row['party_code'] }} {{ $row['party_name'] ? '— '.$row['party_name'] : '' }}</td>
                                <td class="px-4 py-2 text-sm">{{ $row['invoice_id'] }}</td>
                                <td class="px-4 py-2 text-sm">{{ $row['account']?->code }} — {{ $row['account']?->name }}</td>
                                <td class="px-4 py-2 text-sm text-right">{{ number_format($row['debit'], 2, ',', '.') }}</td>
                                <td class="px-4 py-2 text-sm text-right">{{ number_format($row['credit'], 2, ',', '.') }}</td>
                                <td class="px-4 py-2 text-sm text-right font-semibold">{{ number_format($row['outstanding'], 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <td class="px-4 py-3 text-sm font-semibold" colspan="3">Total</td>
                            <td class="px-4 py-3 text-sm text-right font-semibold">{{ number_format($totals['debit'] ?? 0, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-right font-semibold">{{ number_format($totals['credit'] ?? 0, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-right font-semibold">{{ number_format($totals['outstanding'] ?? 0, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
