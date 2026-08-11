<div id="modal-booking-form" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl text-left shadow-2xl transition-all border border-slate-200/50 dark:border-slate-800 p-0">

            <!-- Header Modal -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200/60 dark:border-slate-800">
                <h3 id="modal-title-form" class="text-lg font-bold text-slate-800 dark:text-slate-100">Buat Booking</h3>
                <button type="button" onclick="closeModalBookingForm()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="form-booking">
                <input type="hidden" id="booking-id-form" name="id" value="">

                <div class="p-6 overflow-y-auto max-h-[70vh] space-y-4">

                    <!-- Baris 1: Pelanggan & Kendaraan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Pelanggan <span class="text-rose-500">*</span></label>
                            <select id="customer_id" name="customer_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 appearance-none cursor-pointer">
                                <option value="">Pilih pelanggan</option>
                                <?php foreach($customers as $cust): ?>
                                    <option value="<?= $cust['customer_id'] ?>"><?= escape($cust['name']) ?> (<?= escape($cust['phone']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Kendaraan <span class="text-rose-500">*</span></label>
                            <select id="car_id" name="car_id" required onchange="updateCarPrice()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 appearance-none cursor-pointer">
                                <option value="">Pilih kendaraan</option>
                                <?php foreach($cars as $car): ?>
                                    <option value="<?= $car['id'] ?>" data-price="<?= $car['price_per_day'] ?>">
                                        <?= escape($car['name']) ?> (<?= escape($car['plate_number']) ?>) - Rp <?= number_format($car['price_per_day'], 0, ',', '.') ?>/hari
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Baris 2: Opsi Layanan & Driver -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Layanan Layanan <span class="text-rose-500">*</span></label>
                            <div class="flex items-center gap-4 mt-2">
                                <label class="inline-flex items-center cursor-pointer text-xs font-medium text-slate-700 dark:text-slate-300">
                                    <input type="radio" name="with_driver" value="0" checked onclick="toggleDriverOption(false)" class="form-radio text-primary focus:ring-primary">
                                    <span class="ml-2">Lepas Kunci</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer text-xs font-medium text-slate-700 dark:text-slate-300">
                                    <input type="radio" name="with_driver" value="1" onclick="toggleDriverOption(true)" class="form-radio text-primary focus:ring-primary">
                                    <span class="ml-2">Dengan Driver</span>
                                </label>
                            </div>
                        </div>

                        <div id="driver-select-container" class="hidden">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Pilih Driver <span class="text-rose-500">*</span></label>
                            <select id="driver_id" name="driver_id" onchange="updateDriverFee()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 appearance-none cursor-pointer">
                                <option value="">Pilih driver</option>
                                <?php foreach($drivers as $drv): ?>
                                    <option value="<?= $drv['id'] ?>" data-price="<?= $drv['price_per_day'] ?>">
                                        <?= escape($drv['name']) ?> (Rp <?= number_format($drv['price_per_day'], 0, ',', '.') ?>/hari)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Baris 3: Tanggal Ambil & Tanggal Kembali -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Ambil <span class="text-rose-500">*</span></label>
                            <input type="datetime-local" id="start_date" name="start_date" required onchange="calculateBookingTotal()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Kembali <span class="text-rose-500">*</span></label>
                            <input type="datetime-local" id="end_date" name="end_date" required onchange="calculateBookingTotal()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm focus:outline-none">
                        </div>
                    </div>

                    <!-- Baris 4: Harga/Hari & Fee Driver (Formatted Rupiah) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Harga Sewa / Hari <span class="text-rose-500">*</span></label>
                            <input type="text" id="price_per_day" name="price_per_day" required placeholder="0" class="input-rupiah w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm font-semibold focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Fee Driver / Hari</label>
                            <input type="text" id="driver_fee" name="driver_fee" placeholder="0" readonly class="input-rupiah w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-sm font-semibold focus:outline-none">
                        </div>
                    </div>

                    <!-- Baris 5: Diskon & Deposit (Formatted Rupiah) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Diskon (Rp)</label>
                            <input type="text" id="discount" name="discount" class="input-rupiah w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm focus:outline-none" placeholder="0">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Deposit / Uang Muka (DP)</label>
                            <input type="text" id="deposit" name="deposit" class="input-rupiah w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm focus:outline-none" placeholder="0">
                        </div>
                    </div>

                    <!-- Baris 6: Jaminan & Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Jaminan / Agunan <span class="text-slate-400 font-normal text-xs">(Opsional)</span></label>
                            <input type="text" id="guarantee" name="guarantee" placeholder="Misal: KTP, Sepeda Motor + STNK" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Status Booking</label>
                            <select id="status_form" name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm focus:outline-none appearance-none cursor-pointer">
                                <option value="Reservasi">Reservasi</option>
                                <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                                <option value="Approve">Disetujui (Lunas)</option>
                                <option value="Dipinjam">Dipinjam (Jalan)</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Batal">Batal</option>
                            </select>
                        </div>
                    </div>

                    <!-- Baris 7: Catatan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Catatan Tambahan</label>
                        <input type="text" id="notes_form" name="notes" placeholder="Catatan khusus pemesanan..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200 text-sm focus:outline-none">
                    </div>

                    <!-- BOX RINGKASAN BIAYA & DURASI REAL-TIME -->
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/80 border border-slate-200/60 dark:border-slate-800/80 grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-slate-500 dark:text-slate-400">Durasi: </span>
                            <span id="disp-durasi" class="font-bold text-slate-800 dark:text-slate-100">0 hari</span>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-500 dark:text-slate-400">Biaya Sewa: </span>
                            <span id="disp-sewa" class="font-bold text-slate-800 dark:text-slate-100">Rp 0</span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 font-bold">Total: </span>
                            <span id="disp-total" class="font-extrabold text-slate-900 dark:text-white text-base">Rp 0</span>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-500 dark:text-slate-400 font-bold">Sisa Bayar: </span>
                            <span id="disp-sisa" class="font-extrabold text-primary text-base">Rp 0</span>
                        </div>
                    </div>

                </div>

                <!-- Footer Modal -->
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-slate-200/60 dark:border-slate-800">
                    <button type="button" onclick="closeModalBookingForm()" class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all font-medium text-sm">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 text-white font-semibold text-sm shadow-md shadow-sky-500/20 transition-all">
                        Simpan Booking
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
// Toggle Tampilan Driver
function toggleDriverOption(isWithDriver) {
    const driverContainer = document.getElementById('driver-select-container');
    const driverSelect = document.getElementById('driver_id');
    const driverFeeInput = document.getElementById('driver_fee');

    if (isWithDriver) {
        driverContainer.classList.remove('hidden');
        driverSelect.setAttribute('required', 'required');
        driverFeeInput.removeAttribute('readonly');
        driverFeeInput.classList.remove('bg-slate-100', 'dark:bg-slate-800');
    } else {
        driverContainer.classList.add('hidden');
        driverSelect.removeAttribute('required');
        driverSelect.value = '';
        driverFeeInput.value = '0';
        driverFeeInput.setAttribute('readonly', 'readonly');
        driverFeeInput.classList.add('bg-slate-100', 'dark:bg-slate-800');
    }
    calculateBookingTotal();
}

// Auto-fill Harga Mobil per Hari saat Mobil Dipilih
function updateCarPrice() {
    const carSelect = document.getElementById('car_id');
    const selectedOption = carSelect.options[carSelect.selectedIndex];
    const price = selectedOption ? selectedOption.getAttribute('data-price') : 0;
    
    document.getElementById('price_per_day').value = formatRupiahNumber(price || 0);
    calculateBookingTotal();
}

// Auto-fill Fee Driver per Hari saat Driver Dipilih
function updateDriverFee() {
    const driverSelect = document.getElementById('driver_id');
    const selectedOption = driverSelect.options[driverSelect.selectedIndex];
    const price = selectedOption ? selectedOption.getAttribute('data-price') : 0;
    
    document.getElementById('driver_fee').value = formatRupiahNumber(price || 0);
    calculateBookingTotal();
}

// Kalkulasi Total Biaya & Durasi
function calculateBookingTotal() {
    const startDateVal = document.getElementById('start_date').value;
    const endDateVal = document.getElementById('end_date').value;

    let days = 0;
    if (startDateVal && endDateVal) {
        const start = new Date(startDateVal);
        const end = new Date(endDateVal);
        const diffTime = end - start;
        if (diffTime > 0) {
            days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        }
    }

    const pricePerDay = parseFloat((document.getElementById('price_per_day').value || '0').replace(/[^0-9]/g, '')) || 0;
    const driverFee = parseFloat((document.getElementById('driver_fee').value || '0').replace(/[^0-9]/g, '')) || 0;
    const discount = parseFloat((document.getElementById('discount').value || '0').replace(/[^0-9]/g, '')) || 0;
    const deposit = parseFloat((document.getElementById('deposit').value || '0').replace(/[^0-9]/g, '')) || 0;

    const sewaTotal = days * pricePerDay;
    const driverTotal = days * driverFee;
    const total = (sewaTotal + driverTotal) - discount;
    const sisa = Math.max(0, total - deposit);

    document.getElementById('disp-durasi').innerText = `${days} hari`;
    document.getElementById('disp-sewa').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(sewaTotal)}`;
    document.getElementById('disp-total').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(total > 0 ? total : 0)}`;
    document.getElementById('disp-sisa').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(sisa)}`;
}

// Trigger Listener Kalkulasi pada Input Rupiah & Tanggal
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('input-rupiah') || e.target.id === 'discount' || e.target.id === 'deposit' || e.target.id === 'price_per_day' || e.target.id === 'driver_fee') {
        calculateBookingTotal();
    }
});
</script>
