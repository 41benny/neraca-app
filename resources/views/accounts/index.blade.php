<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">Master Akun (COA)</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        @if (session('status'))
            <div class="p-4 bg-green-50 dark:bg-emerald-900/30 border border-green-200 dark:border-emerald-800 text-green-700 dark:text-emerald-300 rounded">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white dark:bg-dark-bg-secondary border border-gray-100 dark:border-gray-700 rounded-lg shadow p-4">
            <form method="GET" action="{{ route('accounts.index') }}" class="flex gap-3 items-end">
                <div class="flex-1">
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Cari</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary" placeholder="Kode atau Nama">
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('accounts.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Tambah Akun</a>
                    <a href="{{ route('accounts.imports.create') }}" class="px-4 py-2 bg-primary-600 text-white rounded">Import COA</a>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-dark-bg-secondary border border-gray-100 dark:border-gray-700 rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold">Normal</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold">Debit</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold">Kredit</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold">Mapping</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($accounts as $acc)
                            <tr>
                                <td class="px-4 py-2 text-sm">{{ $acc->code }}</td>
                                <td class="px-4 py-2 text-sm">{{ $acc->name }}</td>
                                <td class="px-4 py-2 text-sm capitalize">{{ $acc->normal_balance }}</td>
                                <td class="px-4 py-2 text-sm text-right">{{ number_format($acc->opening_debit_sum ?? 0, 2, ',', '.') }}</td>
                                <td class="px-4 py-2 text-sm text-right">{{ number_format($acc->opening_credit_sum ?? 0, 2, ',', '.') }}</td>
                                <td class="px-4 py-2 text-sm">
                                    @php($m = $acc->mappings->first())
                                    @if($m)
                                        <span class="text-xs text-gray-600 dark:text-gray-300">{{ $m->report_type }} • {{ $m->group_name }} • {{ $m->side }}</span>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-sm text-right">
                                    <a href="{{ route('accounts.edit', $acc) }}" class="text-indigo-600 dark:text-indigo-300 hover:underline">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada akun</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3">{{ $accounts->links() }}</div>
        </div>
    </div>
</x-app-layout>
