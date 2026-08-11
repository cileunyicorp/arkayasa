<div id="modal-detail-booking" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl text-left shadow-2xl transition-all border border-slate-200/50 dark:border-slate-800 p-0">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200/60 dark:border-slate-800">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-primary-600/10 text-primary flex items-center justify-center">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <span>Detail & Approval Transaksi</span>
                </h3>
                <button type="button" onclick="closeModalBooking()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <input type="hidden" id="booking-id" value="">
            
            <div class="p-6 overflow-y-auto max-h-[70vh] grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data Detail Pelanggan & Armada -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b pb-1">Detail Pelanggan</h4>
                    <div>
                        <p class="text-xs text-slate-400">Nama Lengkap & NIK</p>
                        <p id="det-customer" class="text-sm font-bold text-slate-700 dark:text-slate-200"></p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-xs text-slate-400">No. Telepon / WA</p>
                            <p id="det-phone" class="text-sm font-semibold text-slate-700 dark:text-slate-200"></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Alamat Email</p>
                            <p id="det-email" class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate"></p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Alamat Lengkap</p>
                        <p id="det-address" class="text-sm font-medium text-slate-700 dark:text-slate-200"></p>
                    </div>

                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b pb-1 pt-3">Spesifikasi Armada Kendaraan</h4>
                    <div>
                        <p class="text-xs text-slate-400">Unit Mobil & Nomor Plat</p>
                        <p id="det-car" class="text-sm font-bold text-slate-700 dark:text-slate-200"></p>
                    </div>

                    <!-- Informasi Driver jika dipilih -->
                    <div id="wrapper-det-driver" class="hidden">
                        <p class="text-xs text-slate-400">Layanan Driver</p>
                        <p id="det-driver" class="text-sm font-bold text-slate-700 dark:text-slate-200"></p>
                    </div>
                </div>

                <!-- Data Durasi Sewa, Jaminan, Pembayaran, dan Tombol Aksi -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b pb-1">Rincian Sewa</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-xs text-slate-400">Kode Booking</p>
                            <p id="det-code" class="text-sm font-mono font-bold text-primary"></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Status Transaksi</p>
                            <p id="det-status" class="mt-1"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-xs text-slate-400">Durasi Sewa</p>
                            <p id="det-date" class="text-sm font-semibold text-slate-700 dark:text-slate-200"></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Total Pembayaran</p>
                            <p id="det-price" class="text-sm font-extrabold text-emerald-600 dark:text-emerald-400"></p>
                        </div>
                    </div>

                    <!-- Dokumen / Foto Jaminan -->
                    <div>
                        <p class="text-xs text-slate-400 mb-1">Berkas / Foto Jaminan</p>
                        <div id="det-guarantee-container" class="p-3 rounded-xl bg-slate-100 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/60">
                            <span id="det-guarantee-none" class="text-xs text-slate-400 italic hidden">Tidak ada berkas jaminan yang diunggah.</span>
                            <a id="det-guarantee-link" href="" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-primary hover:underline hidden">
                                <i class="fa-solid fa-file-shield text-sm"></i>
                                <span>Buka / Unduh Berkas Jaminan</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400">Catatan Pelanggan</p>
                        <p id="det-notes" class="text-sm font-medium text-slate-600 dark:text-slate-400 italic"></p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer (Tombol Aksi Dinamis) -->
            <div class="px-6 py-4 border-t border-slate-200/60 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/30">
                <button type="button" onclick="closeModalBooking()" class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition font-medium text-sm">Kembali</button>
                <div id="wrapper-action-btn" class="flex gap-2">
                    <!-- Tombol diisi otomatis secara dinamis oleh JavaScript -->
                </div>
            </div>
            
        </div>
    </div>
</div>
