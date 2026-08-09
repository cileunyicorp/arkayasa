<div id="modal-booking-form" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl text-left shadow-2xl transition-all border border-slate-200/50 dark:border-slate-800 p-0">
            
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200/60 dark:border-slate-800">
                <h3 id="modal-title-form" class="text-lg font-bold text-slate-800 dark:text-slate-100">Input Booking Baru</h3>
                <button type="button" onclick="closeModalBookingForm()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="form-booking">
                <input type="hidden" id="booking-id-form" name="id" value="">
                
                <div class="p-6 overflow-y-auto max-h-[65vh] space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <!-- Pilihan Pelanggan -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Pelanggan <span class="text-rose-500">*</span></label>
                            <select id="customer_id" name="customer_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50 appearance-none">
                                <option value="">-- Pilih Pelanggan --</option>
                                <?php foreach($customers as $cust): ?>
                                    <option value="<?= $cust['customer_id'] ?>"><?= escape($cust['name']) ?> (<?= escape($cust['phone']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Pilihan Mobil + Menempelkan Atribut Harga -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Armada Mobil <span class="text-rose-500">*</span></label>
                            <select id="car_id" name="car_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50 appearance-none">
                                <option value="">-- Pilih Armada --</option>
                                <?php foreach($cars as $car): ?>
                                    <option value="<?= $car['id'] ?>" 
                                            data-p-day="<?= $car['price_per_day'] ?>"
                                            data-p-weekend="<?= $car['price_per_weekend'] ?>"
                                            data-p-week="<?= $car['price_per_week'] ?>"
                                            data-p-month="<?= $car['price_per_month'] ?>">
                                        <?= escape($car['name']) ?> (<?= escape($car['plate_number']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Tanggal Mulai -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal Mulai <span class="text-rose-500">*</span></label>
                            <input type="date" id="start_date" name="start_date" required min="<?= date('Y-m-d') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none">
                        </div>

                        <!-- Tanggal Selesai -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal Selesai <span class="text-rose-500">*</span></label>
                            <input type="date" id="end_date" name="end_date" required min="<?= date('Y-m-d') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none">
                        </div>

                        <!-- Jenis Tarif -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Pilihan Tarif Biaya <span class="text-rose-500">*</span></label>
                            <select id="rate_type" name="rate_type" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50 appearance-none">
                                <option value="weekday">Harian / Weekday</option>
                                <option value="weekend">Harian / Weekend</option>
                                <option value="week">Mingguan</option>
                                <option value="month">Bulanan</option>
                            </select>
                        </div>

                        <!-- Status Transaksi -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status Awal</label>
                            <select id="status_form" name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none appearance-none">
                                <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                                <option value="Approve">Disetujui (Lunas)</option>
                                <option value="Dipinjam">Dipinjam (Jalan)</option>
                            </select>
                        </div>

                        <!-- Durasi Sewa (Dihitung JS, Read-Only) -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Durasi Sewa (Hari)</label>
                            <input type="number" id="total_days" name="total_days" required readonly class="number-no-spinner w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-none font-bold">
                        </div>

                        <!-- Total Biaya (Dihitung JS, Read-Only) -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Total Biaya (Rp)</label>
                            <input type="number" id="total_price" name="total_price" required readonly class="number-no-spinner w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 focus:outline-none font-extrabold">
                        </div>

                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan Sewa</label>
                        <textarea id="notes_form" name="notes" rows="2" placeholder="Catatan pengantaran mobil atau penambahan sopir..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/50"></textarea>
                    </div>

                </div>

                <!-- Footer Modal -->
                <div class="flex justify-end gap-3 pt-4 px-6 pb-6 mt-2 border-t border-slate-200/60 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                    <button type="button" onclick="closeModalBookingForm()" class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all font-medium">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-600 hover:hover:bg-primary-700 text-white shadow-lg shadow-primary-600/30 transition-all font-medium flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> <span>Simpan Transaksi</span>
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>
