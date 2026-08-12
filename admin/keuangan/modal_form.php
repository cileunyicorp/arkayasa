<div id="modal-finance" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl text-left shadow-2xl transition-all border border-slate-200/50 dark:border-slate-800 p-0">

            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200/60 dark:border-slate-800">
                <h3 id="modal-title" class="text-lg font-bold text-slate-800 dark:text-slate-100">Input Transaksi Keuangan</h3>
                <button type="button" onclick="closeModalFinance()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="form-finance">
                <input type="hidden" id="finance-id" name="id" value="">

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Transaksi <span class="text-rose-500">*</span></label>
                        <input type="date" id="transaction_date" name="transaction_date" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Jenis Transaksi <span class="text-rose-500">*</span></label>
                            <select id="type" name="type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm focus:outline-none appearance-none">
                                <option value="Masuk">Pemasukan (Income)</option>
                                <option value="Keluar">Pengeluaran (Expense)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Kategori <span class="text-rose-500">*</span></label>
                            <input type="text" id="category" name="category" required placeholder="Misal: Sewa Mobil, BBM, Service" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nominal (Rp) <span class="text-rose-500">*</span></label>
                        <input type="text" id="amount" name="amount" required placeholder="0" class="input-rupiah w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm font-semibold focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Keterangan / Deskripsi</label>
                        <textarea id="description" name="description" rows="2" placeholder="Detail catatan transaksi..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm focus:outline-none"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 px-6 py-4 border-t border-slate-200/60 dark:border-slate-800">
                    <button type="button" onclick="closeModalFinance()" class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition font-medium text-sm">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm shadow-md transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
