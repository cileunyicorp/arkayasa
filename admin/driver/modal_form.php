<div id="modal-driver" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl text-left shadow-2xl transition-all border border-slate-200/50 dark:border-slate-800 p-0">
            
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200/60 dark:border-slate-800">
                <h3 id="modal-title" class="text-lg font-bold text-slate-800 dark:text-slate-100">Tambah Sopir</h3>
                <button type="button" onclick="closeModalDriver()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="form-driver" enctype="multipart/form-data">
                <input type="hidden" id="driver-id" name="id" value="">
                
                <div class="p-6 overflow-y-auto max-h-[65vh] space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" id="name" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">No. Handphone / WA <span class="text-rose-500">*</span></label>
                            <input type="text" id="phone" name="phone" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span></label>
                            <input type="text" id="nik" name="nik" required placeholder="16 digit angka KTP" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50 font-mono">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nomor SIM (A / B) <span class="text-rose-500">*</span></label>
                            <input type="text" id="driver_license_number" name="driver_license_number" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50 font-mono">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Biaya Jasa / Hari (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" id="price_per_day" name="price_per_day" required class="number-no-spinner w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status Ketersediaan</label>
                            <select id="status" name="status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50 appearance-none">
                                <option value="Tersedia">Tersedia</option>
                                <option value="Disewa">Disewa (Sedang Jalan)</option>
                                <option value="Nonaktif">Nonaktif / Cuti</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Foto Profil <span id="photo-hint" class="text-amber-500 text-[10px] hidden ml-2">(Opsional saat edit)</span></label>
                        <div class="border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-xl p-4 bg-white/30 dark:bg-slate-900/30">
                            <input type="file" id="photo" name="photo" accept=".jpg, .jpeg, .png, .webp" class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary-600/10 file:text-primary hover:file:bg-primary-600/20 transition cursor-pointer">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 px-6 pb-6 mt-2 border-t border-slate-200/60 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                    <button type="button" onclick="closeModalDriver()" class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all font-medium">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-600 hover:hover:bg-primary-700 text-white shadow-lg shadow-primary-600/30 transition-all font-medium flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> <span>Simpan Data</span>
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>
