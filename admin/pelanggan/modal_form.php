<div id="modal-customer" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl text-left shadow-2xl transition-all border border-slate-200/50 dark:border-slate-800 p-0">
            
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200/60 dark:border-slate-800">
                <h3 id="modal-title" class="text-lg font-bold text-slate-800 dark:text-slate-100">Tambah Pelanggan Baru</h3>
                <button type="button" onclick="closeModalCustomer()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="form-customer">
                <input type="hidden" id="customer-id" name="id" value="">
                
                <div class="p-6 overflow-y-auto max-h-[65vh] space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" id="name" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email <span class="text-rose-500">*</span></label>
                            <input type="email" id="email" name="email" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">No. Handphone / WA <span class="text-rose-500">*</span></label>
                            <input type="text" id="phone" name="phone" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password <span id="pass-hint-edit" class="text-amber-500 text-[10px] hidden ml-1">(Kosongkan jika tidak diganti)</span></label>
                            <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span></label>
                            <input type="text" id="nik" name="nik" required placeholder="16 digit angka KTP" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50 font-mono">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nomor SIM <span class="text-slate-400 font-normal text-xs">(Opsional)</span></label>
                            <input type="text" id="driver_license_number" name="driver_license_number" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50 font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="2" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 px-6 pb-6 mt-2 border-t border-slate-200/60 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                    <button type="button" onclick="closeModalCustomer()" class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all font-medium">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-600 hover:hover:bg-primary-700 text-white shadow-lg shadow-primary-600/30 transition-all font-medium flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> <span>Simpan Data</span>
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>
