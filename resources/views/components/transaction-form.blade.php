<div class="fixed inset-0 overflow-y-auto" x-show="open" x-cloak>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Tambah Transaksi Baru
                        </h3>
                        <div class="mt-4">
                            <form>
                                <div class="mb-4">
                                    <label for="transaction_date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <input type="date" name="transaction_date" id="transaction_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Keterangan</label>
                                    <input type="text" name="description" id="description" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Masukkan keterangan transaksi">
                                </div>

                                <div class="mb-4">
                                    <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                                    <select id="category" name="category" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Pilih Kategori</option>
                                        <option value="pendapatan">Pendapatan</option>
                                        <option value="kebutuhan_pokok">Kebutuhan Pokok</option>
                                        <option value="utilitas">Utilitas</option>
                                        <option value="hiburan">Hiburan</option>
                                        <option value="transportasi">Transportasi</option>
                                        <option value="kesehatan">Kesehatan</option>
                                        <option value="pendidikan">Pendidikan</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="type" class="block text-sm font-medium text-gray-700">Jenis Transaksi</label>
                                    <div class="mt-2">
                                        <div class="flex items-center">
                                            <input id="type_income" name="type" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" value="income">
                                            <label for="type_income" class="ml-3 block text-sm font-medium text-gray-700">
                                                Pemasukan
                                            </label>
                                        </div>
                                        <div class="flex items-center mt-2">
                                            <input id="type_expense" name="type" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" value="expense">
                                            <label for="type_expense" class="ml-3 block text-sm font-medium text-gray-700">
                                                Pengeluaran
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">
                                                Rp
                                            </span>
                                        </div>
                                        <input type="text" name="amount" id="amount" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                    <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Tambahkan catatan jika diperlukan"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Simpan
                </button>
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="open = false">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>