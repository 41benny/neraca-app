<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Neraca Keuangan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Ringkasan Saldo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gradient-to-br from-green-50 to-green-100 overflow-hidden shadow-lg rounded-xl border border-green-200 transform transition-all duration-300 hover:scale-105">
                    <div class="p-6 relative">
                        <div class="absolute top-0 right-0 mt-4 mr-4 bg-green-100 rounded-full p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">Total Pemasukan</h3>
                        <p class="text-3xl font-bold text-green-600">Rp 5.000.000</p>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-red-100 overflow-hidden shadow-lg rounded-xl border border-red-200 transform transition-all duration-300 hover:scale-105">
                    <div class="p-6 relative">
                        <div class="absolute top-0 right-0 mt-4 mr-4 bg-red-100 rounded-full p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">Total Pengeluaran</h3>
                        <p class="text-3xl font-bold text-red-600">Rp 3.500.000</p>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 overflow-hidden shadow-lg rounded-xl border border-blue-200 transform transition-all duration-300 hover:scale-105">
                    <div class="p-6 relative">
                        <div class="absolute top-0 right-0 mt-4 mr-4 bg-blue-100 rounded-full p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">Saldo</h3>
                        <p class="text-3xl font-bold text-blue-600">Rp 1.500.000</p>
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
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6 border border-gray-100">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Transaksi Terbaru
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                            <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">Keterangan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">Kategori</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">Jumlah</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-indigo-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">01/06/2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Gaji Bulanan</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Pendapatan</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">+ Rp 5.000.000</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-md hover:bg-indigo-200 transition-colors mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <button class="bg-red-100 text-red-700 px-3 py-1 rounded-md hover:bg-red-200 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-indigo-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">05/06/2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Belanja Bulanan</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Kebutuhan Pokok</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">- Rp 1.500.000</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-md hover:bg-indigo-200 transition-colors mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <button class="bg-red-100 text-red-700 px-3 py-1 rounded-md hover:bg-red-200 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10/06/2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bayar Listrik</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Utilitas</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">- Rp 500.000</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</button>
                                        <button class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15/06/2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bayar Internet</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Utilitas</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">- Rp 350.000</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</button>
                                        <button class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">20/06/2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Makan di Restoran</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Hiburan</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">- Rp 250.000</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</button>
                                        <button class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Grafik Keuangan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Grafik Keuangan</h3>
                    <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                        <p class="text-gray-500">Grafik akan ditampilkan di sini</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
