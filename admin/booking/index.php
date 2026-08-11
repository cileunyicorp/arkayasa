<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Data Pemesanan';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Data Pemesanan Kendaraan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Verifikasi pembayaran, kelola peminjaman, dan penyerahan unit armada.</p>
        </div>
        <button onclick="openModalBookingForm('add')" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl shadow-lg shadow-primary-600/30 transition-all font-medium flex items-center gap-2">
            <i class="fa-solid fa-calendar-plus"></i> <span>Input Booking</span>
        </button>
    </div>

    <!-- Tabel Booking -->
    <?php include __DIR__ . '/table.php'; ?>

    <!-- Modal Form (Input/Edit) -->
    <?php include __DIR__ . '/modal_form.php'; ?>

    <!-- Modal Detail & Approval -->
    <?php include __DIR__ . '/modal_detail.php'; ?>
</div>

<!-- Library Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let tableBooking;

    document.addEventListener('DOMContentLoaded', function() {
        loadTableBooking();

        // Handler Submit Form Booking
        document.getElementById('form-booking').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const id = document.getElementById('booking-id-form').value;
            const apiUrl = id ? `<?= base_url('admin/booking/update/') ?>${id}` : `<?= base_url('admin/booking/store') ?>`;

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: result.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    closeModalBookingForm();
                    tableBooking.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: result.message
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Gagal memproses transaksi.'
                });
            }
        });
    });

    // Helper Format Angka ke Rupiah
    function formatRupiahNumber(val) {
        if (val === null || val === undefined || val === '') return '';
        let valStr = val.toString().trim();
        if (valStr.includes('.') && !valStr.includes(',')) {
            let parts = valStr.split('.');
            if (parts.length === 2 && parts[1].length <= 2) valStr = parts[0];
        }
        let cleanVal = valStr.replace(/[^0-9]/g, '');
        return cleanVal ? new Intl.NumberFormat('id-ID').format(cleanVal) : '';
    }

    function getUnformattedNum(id) {
        let el = document.getElementById(id);
        let val = el ? el.value : '0';
        return parseFloat(val.toString().replace(/[^0-9]/g, '')) || 0;
    }

    // Toggle Tampilan Input Driver
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
        hitungRingkasanBooking();
    }

    // Event listener untuk kalkulasi real-time di Modal Booking
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('input-rupiah')) {
            let cursorPosition = e.target.selectionStart;
            let originalLength = e.target.value.length;

            e.target.value = formatRupiahNumber(e.target.value);

            let newLength = e.target.value.length;
            cursorPosition = cursorPosition + (newLength - originalLength);
            e.target.setSelectionRange(cursorPosition, cursorPosition);
        }

        if (['start_date', 'end_date', 'car_id', 'driver_id', 'price_per_day', 'driver_fee', 'discount', 'deposit'].includes(e.target.id)) {
            hitungRingkasanBooking();
        }
    });

    document.addEventListener('change', function(e) {
        if (['car_id', 'driver_id'].includes(e.target.id)) {
            if (e.target.id === 'car_id') {
                const opt = e.target.options[e.target.selectedIndex];
                if (opt && opt.getAttribute('data-price')) {
                    document.getElementById('price_per_day').value = formatRupiahNumber(opt.getAttribute('data-price'));
                }
            }
            if (e.target.id === 'driver_id') {
                const opt = e.target.options[e.target.selectedIndex];
                if (opt && opt.getAttribute('data-price')) {
                    document.getElementById('driver_fee').value = formatRupiahNumber(opt.getAttribute('data-price'));
                } else {
                    document.getElementById('driver_fee').value = '0';
                }
            }
            hitungRingkasanBooking();
        }
    });

    function hitungRingkasanBooking() {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;

        let totalDays = 0;
        if (start && end) {
            const d1 = new Date(start);
            const d2 = new Date(end);
            const diffMs = d2 - d1;
            let totalHours = Math.ceil(diffMs / (1000 * 60 * 60));
            totalDays = Math.ceil(totalHours / 24);
            if (totalDays <= 0) totalDays = 1;
        }

        const priceDay = getUnformattedNum('price_per_day');
        const driverFee = getUnformattedNum('driver_fee');
        const discount = getUnformattedNum('discount');
        const deposit = getUnformattedNum('deposit');

        const biayaSewa = (priceDay * totalDays) + (driverFee * totalDays);
        const grandTotal = Math.max(0, biayaSewa - discount);
        const sisaBayar = Math.max(0, grandTotal - deposit);

        document.getElementById('disp-durasi').textContent = `${totalDays} hari`;
        document.getElementById('disp-sewa').textContent = `Rp ${formatRupiahNumber(biayaSewa)}`;
        document.getElementById('disp-total').textContent = `Rp ${formatRupiahNumber(grandTotal)}`;
        document.getElementById('disp-sisa').textContent = `Rp ${formatRupiahNumber(sisaBayar)}`;
        document.getElementById('total_days').value = totalDays;
    }

    // Inisialisasi DataTables
    function loadTableBooking() {
        tableBooking = $('#table-booking').DataTable({
            ajax: {
                url: '<?= base_url('admin/booking/api/get_all') ?>',
                dataSrc: 'data'
            },
            columns: [
                {
                    data: 'booking_code',
                    render: data => `<span class="font-mono font-bold text-primary">${data}</span>`
                },
                {
                    data: null,
                    render: row => `<div>
                                        <p class="font-bold text-slate-800 dark:text-slate-100">${row.customer_name}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">${row.customer_phone}</p>
                                    </div>`
                },
                {
                    data: null,
                    render: row => `<div>
                                        <p class="font-bold text-slate-800 dark:text-slate-100">${row.car_name}</p>
                                        <p class="text-[11px] font-mono font-semibold text-slate-500">${row.car_plate}</p>
                                    </div>`
                },
                {
                    data: 'dates_format'
                },
                {
                    data: 'total_price_format',
                    render: data => `<span class="font-bold text-emerald-600 dark:text-emerald-400">${data}</span>`
                },
                {
                    data: 'status_html'
                },
                {
                    data: null,
                    className: 'text-center',
                    render: row => `
                    <div class="flex justify-center gap-1.5">
                        <a href="<?= base_url('admin/booking/invoice/') ?>${row.id}" target="_blank" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-teal-600 hover:bg-teal-50 dark:hover:bg-teal-900/30 transition-all shadow-sm flex items-center justify-center" title="Cetak Invoice">
                            <i class="fa-solid fa-print text-xs"></i>
                        </a>
                        <button onclick="showDetailBooking(${row.id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all shadow-sm" title="Approval & Detail">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                        <button onclick="editBooking(${row.id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 transition-all shadow-sm" title="Edit Transaksi">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </button>
                        <button onclick="deleteBooking(${row.id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-all shadow-sm" title="Hapus Transaksi">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>`
                }
            ],
            language: {
                lengthMenu: "Tampilkan _MENU_ data",
                search: "Cari:",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ data)",
                zeroRecords: "Belum ada transaksi sewa",
                emptyTable: "Belum ada data armada",
                paginate: {
                    first: "«",
                    last: "»",
                    next: "›",
                    previous: "‹"
                },
                processing: "Memuat data..."
            }
        });
    }

    // Modal Control: Tambah Booking
    function openModalBookingForm(type) {
        document.getElementById('form-booking').reset();
        document.getElementById('booking-id-form').value = '';

        toggleDriverOption(false);

        const now = new Date();
        const nowIso = new Date(now.getTime() - (now.getTimezoneOffset() * 60000)).toISOString().slice(0, 16);
        const tomorrow = new Date(now.getTime() + (24 * 60 * 60 * 1000));
        const tomorrowIso = new Date(tomorrow.getTime() - (tomorrow.getTimezoneOffset() * 60000)).toISOString().slice(0, 16);

        document.getElementById('start_date').value = nowIso;
        document.getElementById('end_date').value = tomorrowIso;

        document.getElementById('modal-title-form').innerHTML = '<i class="fa-solid fa-calendar-plus text-primary mr-2"></i> Input Booking Baru';
        document.getElementById('modal-booking-form').classList.remove('hidden');

        hitungRingkasanBooking();
    }

    function closeModalBookingForm() {
        document.getElementById('modal-booking-form').classList.add('hidden');
    }

    // Populate data untuk Edit Transaksi
    async function editBooking(id) {
        const res = await fetch(`<?= base_url('admin/booking/api/get_by_id/') ?>${id}`);
        const result = await res.json();

        if (result.status) {
            const data = result.data;
            document.getElementById('modal-title-form').innerHTML = '<i class="fa-solid fa-pen-to-square text-primary mr-2"></i> Edit Data Booking';

            document.getElementById('booking-id-form').value = data.id;
            document.getElementById('customer_id').value = data.customer_id;
            document.getElementById('car_id').value = data.car_id;

            // Set Pilihan Driver
            if (data.driver_id && parseInt(data.driver_id) > 0) {
                document.querySelectorAll('input[name="with_driver"]')[1].checked = true;
                toggleDriverOption(true);
                document.getElementById('driver_id').value = data.driver_id;
            } else {
                document.querySelectorAll('input[name="with_driver"]')[0].checked = true;
                toggleDriverOption(false);
            }

            document.getElementById('start_date').value = data.start_date_raw;
            document.getElementById('end_date').value = data.end_date_raw;

            const hargaSewa = data.price_per_day || data.car_price || 0;
            document.getElementById('price_per_day').value = formatRupiahNumber(Math.round(parseFloat(hargaSewa)));
            document.getElementById('driver_fee').value = formatRupiahNumber(Math.round(parseFloat(data.driver_fee || 0)));
            document.getElementById('discount').value = formatRupiahNumber(Math.round(parseFloat(data.discount || 0)));
            document.getElementById('deposit').value = formatRupiahNumber(Math.round(parseFloat(data.deposit || 0)));

            const previewGuarantee = document.getElementById('preview-guarantee');
            const linkGuarantee = document.getElementById('link-preview-guarantee');
            if (previewGuarantee && linkGuarantee) {
                if (data.guarantee_file_url) {
                    linkGuarantee.href = data.guarantee_file_url;
                    previewGuarantee.classList.remove('hidden');
                } else {
                    previewGuarantee.classList.add('hidden');
                }
            }

            document.getElementById('status_form').value = data.status;
            document.getElementById('notes_form').value = data.notes || '';

            document.getElementById('modal-booking-form').classList.remove('hidden');
            hitungRingkasanBooking();
        }
    }

    // Hapus Booking
    function deleteBooking(id) {
        Swal.fire({
            title: 'Hapus Transaksi?',
            text: "Status mobil terkait otomatis dikembalikan menjadi 'Tersedia'!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!'
        }).then(async (res) => {
            if (res.isConfirmed) {
                const response = await fetch(`<?= base_url('admin/booking/delete/') ?>${id}`, {
                    method: 'POST'
                });
                const result = await response.json();

                if (result.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: result.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    tableBooking.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: result.message
                    });
                }
            }
        });
    }

    // Modal Detail & Approval
    async function showDetailBooking(id) {
        const res = await fetch(`<?= base_url('admin/booking/api/get_by_id/') ?>${id}`);
        const result = await res.json();

        if (result.status) {
            const data = result.data;

            document.getElementById('booking-id').value = data.id;

            document.getElementById('det-code').textContent = data.booking_code;
            document.getElementById('det-customer').textContent = `${data.customer_name} (NIK: ${data.customer_nik || '-'})`;
            document.getElementById('det-phone').textContent = data.customer_phone || '-';
            document.getElementById('det-email').textContent = data.customer_email || '-';
            document.getElementById('det-address').textContent = data.customer_address || '-';
            document.getElementById('det-car').textContent = `${data.car_brand || ''} ${data.car_name} [${data.car_plate}]`;

            // Driver Info
            const wrapperDriver = document.getElementById('wrapper-det-driver');
            if (wrapperDriver) {
                if (data.driver_name) {
                    document.getElementById('det-driver').textContent = `${data.driver_name} (${data.driver_phone || '-'})`;
                    wrapperDriver.classList.remove('hidden');
                } else {
                    wrapperDriver.classList.add('hidden');
                }
            }

            // Guarantee File Link
            const guaranteeLink = document.getElementById('det-guarantee-link');
            const guaranteeNone = document.getElementById('det-guarantee-none');
            if (guaranteeLink && guaranteeNone) {
                if (data.guarantee_file_url) {
                    guaranteeLink.href = data.guarantee_file_url;
                    guaranteeLink.classList.remove('hidden');
                    guaranteeNone.classList.add('hidden');
                } else {
                    guaranteeLink.classList.add('hidden');
                    guaranteeNone.classList.remove('hidden');
                }
            }

            document.getElementById('det-date').textContent = `${data.start_date_format} s/d ${data.end_date_format} (${data.total_days} Hari)`;
            document.getElementById('det-price').textContent = data.total_price_format;
            document.getElementById('det-status').innerHTML = `<span class="px-2.5 py-1 text-xs font-bold uppercase tracking-wide rounded-lg bg-indigo-50 dark:bg-neutral-900 text-primary">${data.status}</span>`;
            document.getElementById('det-notes').textContent = data.notes ? data.notes : '-';

            // GENERATE TOMBOL AKSI DINAMIS BERSAMA DENGAN ID LANGSUNG
            const containerBtn = document.getElementById('wrapper-action-btn');
            containerBtn.innerHTML = '';

            const bookingId = data.id;

            if (data.status === 'Menunggu Pembayaran') {
                containerBtn.innerHTML = `
                    <button type="button" onclick="processStatus(${bookingId}, 'Reject')" class="px-4 py-2.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition font-semibold text-sm">Tolak Pesanan</button>
                    <button type="button" onclick="processStatus(${bookingId}, 'Approve')" class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white shadow-lg transition font-semibold text-sm">Setujui Pembayaran</button>
                `;
            } else if (data.status === 'Approve' || data.status === 'Reservasi') {
                containerBtn.innerHTML = `
                    <button type="button" onclick="processStatus(${bookingId}, 'Dipinjam')" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-lg transition font-semibold text-sm">Mulai Pinjam (Jalan)</button>
                `;
            } else if (data.status === 'Dipinjam') {
                containerBtn.innerHTML = `
                    <button type="button" onclick="processStatus(${bookingId}, 'Selesai')" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg transition font-semibold text-sm">Selesai / Mobil Kembali</button>
                `;
            } else {
                containerBtn.innerHTML = `<span class="text-xs text-slate-400 italic">Pesanan ini sudah selesai/batal.</span>`;
            }

            document.getElementById('modal-detail-booking').classList.remove('hidden');
        }
    }

    function closeModalBooking() {
        document.getElementById('modal-detail-booking').classList.add('hidden');
    }

    // TUNGGAL & TEPAT: Fungsi untuk Memproses Perubahan Status Booking
    async function processStatus(id, statusName) {
        if (!id) {
            id = document.getElementById('booking-id').value;
        }

        if (!id) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'ID transaksi tidak ditemukan.'
            });
            return;
        }

        const formData = new FormData();
        formData.append('id', id);
        formData.append('status', statusName);

        Swal.fire({
            title: 'Lanjutkan Tindakan?',
            text: `Apakah Anda yakin ingin memproses status transaksi ini menjadi '${statusName}'?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#B30427',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Proses'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch('<?= base_url('admin/booking/update_status') ?>', {
                        method: 'POST',
                        body: formData
                    });
                    const resData = await response.json();

                    if (resData.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: resData.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        closeModalBooking();
                        tableBooking.ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: resData.message
                        });
                    }
                } catch (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Sistem',
                        text: 'Gagal menghubungi server.'
                    });
                }
            }
        });
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
