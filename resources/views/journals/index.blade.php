<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl leading-tight">Jurnal Transaksi</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('cash.create', ['type' => 'in']) }}" class="inline-flex items-center px-3 py-2 rounded bg-emerald-600 hover:bg-emerald-700 text-white text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Kas Masuk
                </a>
                <a href="{{ route('cash.create', ['type' => 'out']) }}" class="inline-flex items-center px-3 py-2 rounded bg-rose-600 hover:bg-rose-700 text-white text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Kas Keluar
                </a>
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm dark:bg-gray-800 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        @if (session('status'))
            <div class="p-4 bg-green-50 dark:bg-emerald-900/30 border border-green-200 dark:border-emerald-800 text-green-700 dark:text-emerald-300 rounded">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white dark:bg-dark-bg-secondary border border-gray-100 dark:border-gray-700 rounded-lg shadow p-4">
            <form method="GET" action="{{ route('journals.index') }}" class="grid md:grid-cols-6 gap-3">
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Batch Import</label>
                    <select name="import_id" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                        <option value="">Semua</option>
                        @foreach($imports as $imp)
                            <option value="{{ $imp->id }}" @selected(request('import_id') == $imp->id)>{{ $imp->id }} — {{ $imp->batch_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Cari</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary" placeholder="Deskripsi/Dokumen"/>
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
                            <th class="px-4 py-3 text-left text-xs font-semibold">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold">Akun</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold">Dokumen</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold">Pihak</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold">Keterangan</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold">Debit</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold">Kredit</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($lines as $line)
                            <tr>
                                <td class="px-4 py-2 text-sm">{{ $line->journal_date->toDateString() }}</td>
                                <td class="px-4 py-2 text-sm">{{ $line->account?->code }} — {{ $line->account?->name }}</td>
                                <td class="px-4 py-2 text-sm">
                                    {{ $line->document_no }}
                                    @php($att = $line->import?->context['attachment'] ?? null)
                                    @if ($att && isset($att['disk'], $att['path']))
                                        <a href="{{ asset('storage/'.$att['path']) }}" target="_blank" class="ml-2 text-indigo-600 dark:text-indigo-300" title="Lihat Lampiran">📎</a>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-sm">{{ $line->party_code }} {{ $line->party_name ? '— '.$line->party_name : '' }}</td>
                                <td class="px-4 py-2 text-sm">{{ $line->description }}</td>
                                <td class="px-4 py-2 text-sm text-right">{{ number_format($line->debit, 2, ',', '.') }}</td>
                                <td class="px-4 py-2 text-sm text-right">{{ number_format($line->credit, 2, ',', '.') }}</td>
                                <td class="px-4 py-2 text-sm text-right">
                                    <a href="{{ route('journals.edit', $line) }}" class="text-indigo-600 dark:text-indigo-300 hover:underline">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3">{{ $lines->links() }}</div>
        </div>
    </div>
</x-app-layout>
