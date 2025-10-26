<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Neraca Keuangan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Upload Jurnal -->
            <div class="bg-white dark:bg-dark-bg-secondary overflow-hidden shadow-lg rounded-xl border border-gray-100 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4 gap-2">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12v9m0 0l-3-3m3 3l3-3M4 12V7a2 2 0 012-2h5m5 0h2a2 2 0 012 2v5" />
                        </svg>
                        Upload Jurnal Excel
                        </h3>
                        <a href="{{ route('templates.journal.xlsx') }}" class="px-3 py-2 bg-primary-600 text-white rounded">Download Template Excel (XLSX)</a>
                    </div>
                    <form method="POST" action="{{ route('journal-imports.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">File (XLS/XLSX/CSV)</label>
                            <input type="file" name="file" accept=".xls,.xlsx,.csv" required class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Nama Batch</label>
                            <input type="text" name="import_name" value="{{ 'Import '.now()->format('Y-m-d H:i') }}" required class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Tanggal Impor</label>
                            <input type="date" name="imported_at" value="{{ now()->toDateString() }}" class="w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Proses</button>
                        </div>
                    </form>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Kolom minimal: <strong>date</strong> (date), <strong>account_code</strong> (text), <strong>debit</strong> (number), <strong>credit</strong> (number), <strong>description</strong> (text). Opsional: branch (text), invoice_id (text), project_id (text), vehicle_id (text), party_type (customer|vendor), party_code (text), party_name (text). Pastikan total debit = kredit.</p>
                </div>
            </div>
            <!-- Ringkasan Saldo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/10 overflow-hidden shadow-lg rounded-xl border border-green-200 dark:border-green-800 transform transition-all duration-300 hover:scale-105">
                    <div class="p-6 relative">
                        <div class="absolute top-0 right-0 mt-4 mr-4 bg-green-100 dark:bg-green-900/40 rounded-full p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-2">Total Pemasukan</h3>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-300">Rp 5.000.000</p>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/10 overflow-hidden shadow-lg rounded-xl border border-red-200 dark:border-red-800 transform transition-all duration-300 hover:scale-105">
                    <div class="p-6 relative">
                        <div class="absolute top-0 right-0 mt-4 mr-4 bg-red-100 dark:bg-red-900/40 rounded-full p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-2">Total Pengeluaran</h3>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-300">Rp 3.500.000</p>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/10 overflow-hidden shadow-lg rounded-xl border border-blue-200 dark:border-blue-800 transform transition-all duration-300 hover:scale-105">
                    <div class="p-6 relative">
                        <div class="absolute top-0 right-0 mt-4 mr-4 bg-blue-100 dark:bg-blue-900/40 rounded-full p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-2">Saldo</h3>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-300">Rp 1.500.000</p>
                    </div>
                </div>
            </div>

            <!-- Tombol Tambah Transaksi -->
            <div class="flex justify-end mb-4">
                <button type="button" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-lg font-semibold text-sm text-white tracking-wider hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Transaksi
                </button>
            </div>

            <!-- Daftar Transaksi Terbaru -->
            <div class="bg-white dark:bg-dark-bg-secondary overflow-hidden shadow-lg rounded-xl mb-6 border border-gray-100 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Transaksi Terbaru
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg overflow-hidden">
                            <thead class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/30 dark:to-purple-900/30">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 dark:text-indigo-200 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 dark:text-indigo-200 uppercase tracking-wider">Keterangan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 dark:text-indigo-200 uppercase tracking-wider">Kategori</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 dark:text-indigo-200 uppercase tracking-wider">Jumlah</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 dark:text-indigo-200 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-dark-bg-secondary divide-y divide-gray-200 dark:divide-gray-700">
                                <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">01/06/2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-100">Gaji Bulanan</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 rounded-full">Pendapatan</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 dark:text-green-400">+ Rp 5.000.000</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button class="bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-200 px-3 py-1 rounded-md hover:bg-indigo-200 dark:hover:bg-indigo-800/40 transition-colors mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <button class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 px-3 py-1 rounded-md hover:bg-red-200 dark:hover:bg-red-800/40 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">05/06/2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-100">Belanja Bulanan</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 rounded-full">Kebutuhan Pokok</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600 dark:text-red-400">- Rp 1.500.000</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button class="bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-200 px-3 py-1 rounded-md hover:bg-indigo-200 dark:hover:bg-indigo-800/40 transition-colors mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <button class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 px-3 py-1 rounded-md hover:bg-red-200 dark:hover:bg-red-800/40 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">10/06/2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">Bayar Listrik</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Utilitas</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600 dark:text-red-400">- Rp 500.000</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <button class="text-indigo-600 dark:text-indigo-300 hover:text-indigo-900 dark:hover:text-indigo-200 mr-2">Edit</button>
                                        <button class="text-red-600 dark:text-red-300 hover:text-red-900 dark:hover:text-red-200">Hapus</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">15/06/2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">Bayar Internet</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Utilitas</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600 dark:text-red-400">- Rp 350.000</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <button class="text-indigo-600 dark:text-indigo-300 hover:text-indigo-900 dark:hover:text-indigo-200 mr-2">Edit</button>
                                        <button class="text-red-600 dark:text-red-300 hover:text-red-900 dark:hover:text-red-200">Hapus</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">20/06/2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">Makan di Restoran</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Hiburan</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600 dark:text-red-400">- Rp 250.000</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <button class="text-indigo-600 dark:text-indigo-300 hover:text-indigo-900 dark:hover:text-indigo-200 mr-2">Edit</button>
                                        <button class="text-red-600 dark:text-red-300 hover:text-red-900 dark:hover:text-red-200">Hapus</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Grafik Keuangan -->
            <div class="bg-white dark:bg-dark-bg-secondary overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Grafik Keuangan</h3>
                    <div class="h-64 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                        <p class="text-gray-500 dark:text-gray-400">Grafik akan ditampilkan di sini</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
