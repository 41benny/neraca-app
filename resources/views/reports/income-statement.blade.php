<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl leading-tight">Laporan Laba Rugi</h2>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm dark:bg-gray-800 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        <div class="bg-white dark:bg-dark-bg-secondary border border-gray-100 dark:border-gray-700 rounded-lg shadow p-4">
            <form method="GET" action="{{ route('reports.income-statement.view') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->toDateString()) }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date', now()->toDateString()) }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Cabang</label>
                    <input type="text" name="branch_code" value="{{ request('branch_code') }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary placeholder-gray-400 dark:placeholder-gray-500" placeholder="CTH: CBG01">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Invoice</label>
                    <input type="text" name="invoice_id" value="{{ request('invoice_id') }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Proyek</label>
                    <input type="text" name="project_id" value="{{ request('project_id') }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Tampilkan</button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-dark-bg-secondary border border-gray-100 dark:border-gray-700 rounded-lg shadow p-4">
            @php($meta = $data['meta'] ?? [])
            @php($groups = $data['groups'] ?? [])
            @php($totals = $data['totals'] ?? [])

            <div class="mb-4 text-sm text-gray-600 dark:text-gray-300">Periode {{ $meta['start_date'] ?? '-' }} s/d {{ $meta['end_date'] ?? '-' }}</div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold mb-2">Pendapatan</h3>
                    <div class="space-y-2">
                        @foreach($groups as $g)
                            @if(($g['side'] ?? '') === 'revenue')
                                <div class="border border-gray-200 dark:border-gray-700 rounded p-3">
                                    <div class="font-medium mb-2">{{ $g['name'] }}</div>
                                    <ul class="text-sm space-y-1">
                                        @foreach(($g['accounts'] ?? []) as $acc)
                                            <li class="flex justify-between">
                                                <span>{{ $acc['code'] }} — {{ $acc['name'] }}</span>
                                                <span>{{ number_format($acc['amount'], 2, ',', '.') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-2 flex justify-between font-semibold">
                                        <span>Subtotal</span>
                                        <span>{{ number_format($g['total'] ?? 0, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">Beban</h3>
                    <div class="space-y-2">
                        @foreach($groups as $g)
                            @if(($g['side'] ?? '') === 'expense')
                                <div class="border border-gray-200 dark:border-gray-700 rounded p-3">
                                    <div class="font-medium mb-2">{{ $g['name'] }}</div>
                                    <ul class="text-sm space-y-1">
                                        @foreach(($g['accounts'] ?? []) as $acc)
                                            <li class="flex justify-between">
                                                <span>{{ $acc['code'] }} — {{ $acc['name'] }}</span>
                                                <span>{{ number_format($acc['amount'], 2, ',', '.') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-2 flex justify-between font-semibold">
                                        <span>Subtotal</span>
                                        <span>{{ number_format($g['total'] ?? 0, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-6 grid md:grid-cols-3 gap-6">
                <div class="flex justify-between text-lg font-bold">
                    <span>Total Pendapatan</span>
                    <span>Rp {{ number_format($totals['revenue'] ?? 0, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold">
                    <span>Total Beban</span>
                    <span>Rp {{ number_format($totals['expenses'] ?? 0, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold">
                    <span>Laba Bersih</span>
                    <span>Rp {{ number_format($totals['net_income'] ?? 0, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
