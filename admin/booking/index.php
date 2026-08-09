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
        <button onclick="openModalBookingForm('add')" class="px-5 py-2.5 bg-primary-600 hover:hover:bg-primary-700 text-white rounded-xl shadow-lg shadow-primary-600/30 transition-all font-medium flex items-center gap-2">
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

        // Handler Submit Form Booking (Tambah/Edit)
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
                    text: 'Gagal memproses data.'
                });
            }
        });

        // Trigger Kalkulasi Otomatis ketika Form Berubah
        const inputDates = ['start_date', 'end_date', 'car_id', 'rate_type'];
        inputDates.forEach(id => {
            document.getElementById(id).addEventListener('change', hitungBiayaOtomatis);
        });
    });

    // Inisialisasi DataTables
    function loadTableBooking() {
        tableBooking = $('#table-booking').DataTable({
            ajax: {
                url: '<?= base_url('admin/booking/api/get_all') ?>',
                dataSrc: 'data'
            },
            columns: [{
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
                        <!-- Tombol Cetak Invoice -->
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

        document.getElementById('modal-title-form').innerHTML = '<i class="fa-solid fa-calendar-plus text-primary mr-2"></i> Input Booking Baru';
        document.getElementById('modal-booking-form').classList.remove('hidden');
    }

    function closeModalBookingForm() {
        document.getElementById('modal-booking-form').classList.add('hidden');
    }

    // Fetch untuk Form Edit
    async function editBooking(id) {
        const res = await fetch(`<?= base_url('admin/booking/api/get_by_id/') ?>${id}`);
        const result = await res.json();

        if (result.status) {
            const data = result.data;
            document.getElementById('modal-title-form').innerHTML = '<i class="fa-solid fa-pen-to-square text-primary mr-2"></i> Edit Data Booking';

            document.getElementById('booking-id-form').value = data.id;
            document.getElementById('customer_id').value = data.customer_id;
            document.getElementById('car_id').value = data.car_id;
            document.getElementById('start_date').value = data.start_date;
            document.getElementById('end_date').value = data.end_date;
            document.getElementById('total_days').value = data.total_days;
            document.getElementById('total_price').value = data.total_price;
            document.getElementById('status_form').value = data.status;
            document.getElementById('notes_form').value = data.notes;

            document.getElementById('modal-booking-form').classList.remove('hidden');
        }
    }

    // Kalkulator Otomatis durasi hari dan mengalikan harga sesuai tipe tarif
    function hitungBiayaOtomatis() {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        const carSelect = document.getElementById('car_id');
        const rateType = document.getElementById('rate_type').value;

        if (start && end && carSelect.value !== "") {
            const date1 = new Date(start);
            const date2 = new Date(end);

            const diffTime = Math.abs(date2 - date1);
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if (diffDays === 0) diffDays = 1;

            document.getElementById('total_days').value = diffDays;

            const activeOption = carSelect.options[carSelect.selectedIndex];
            const pDay = parseFloat(activeOption.getAttribute('data-p-day') || 0);
            const pWeekend = parseFloat(activeOption.getAttribute('data-p-weekend') || 0);
            const pWeek = parseFloat(activeOption.getAttribute('data-p-week') || 0);
            const pMonth = parseFloat(activeOption.getAttribute('data-p-month') || 0);

            let pricePerUnit = pDay;
            if (rateType === 'weekend') pricePerUnit = pWeekend;
            if (rateType === 'week') pricePerUnit = pWeek / 7;
            if (rateType === 'month') pricePerUnit = pMonth / 30;

            const grandTotal = Math.round(pricePerUnit * diffDays);
            document.getElementById('total_price').value = grandTotal;
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

    // ==========================================
    // JS UNTUK MODAL DETAIL & APPROVAL (BARU & LENGKAP)
    // ==========================================
    async function showDetailBooking(id) {
        const res = await fetch(`<?= base_url('admin/booking/api/get_by_id/') ?>${id}`);
        const result = await res.json();

        if (result.status) {
            const data = result.data;

            // Set ID ke hidden field modal detail
            document.getElementById('booking-id').value = data.id;

            // Mapping Data Pelanggan & Mobil
            document.getElementById('det-code').textContent = data.booking_code;
            document.getElementById('det-customer').textContent = `${data.customer_name} (NIK: ${data.customer_nik})`;
            document.getElementById('det-phone').textContent = data.customer_phone;
            document.getElementById('det-email').textContent = data.customer_email;
            document.getElementById('det-address').textContent = data.customer_address;
            document.getElementById('det-car').textContent = `${data.car_brand} ${data.car_name} [${data.car_plate}]`;

            // Sewa info
            document.getElementById('det-date').textContent = `${data.start_date_format} s/d ${data.end_date_format} (${data.total_days} Hari)`;
            document.getElementById('det-price').textContent = data.total_price_format;
            document.getElementById('det-status').innerHTML = `<span class="px-2.5 py-1 text-xs font-bold uppercase tracking-wide rounded-lg bg-indigo-50 dark:bg-neutral-900 text-primary">${data.status}</span>`;
            document.getElementById('det-notes').textContent = data.notes ? data.notes : '-';

            // Logika Tombol Aksi Dinamis berdasarkan status terkini
            const containerBtn = document.getElementById('wrapper-action-btn');
            containerBtn.innerHTML = ''; // Reset tombol

            if (data.status === 'Menunggu Pembayaran') {
                containerBtn.innerHTML = `
                    <button type="button" onclick="processStatus('Reject')" class="px-4 py-2.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition font-semibold text-sm">Tolak Pesanan</button>
                    <button type="button" onclick="processStatus('Approve')" class="px-5 py-2.5 rounded-xl bg-primary-600 hover:hover:bg-primary-700 text-white shadow-lg transition font-semibold text-sm">Setujui Pembayaran</button>
                `;
            } else if (data.status === 'Approve') {
                containerBtn.innerHTML = `
                    <button type="button" onclick="processStatus('Dipinjam')" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-lg transition font-semibold text-sm">Mulai Pinjam (Jalan)</button>
                `;
            } else if (data.status === 'Dipinjam') {
                containerBtn.innerHTML = `
                    <button type="button" onclick="processStatus('Selesai')" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg transition font-semibold text-sm">Selesai / Mobil Kembali</button>
                `;
            } else {
                containerBtn.innerHTML = `<span class="text-xs text-slate-400 italic">Pesanan ini sudah selesai dan tidak butuh aksi lanjutan.</span>`;
            }

            // Tampilkan Modal Detail
            document.getElementById('modal-detail-booking').classList.remove('hidden');
        }
    }

    function closeModalBooking() {
        document.getElementById('modal-detail-booking').classList.add('hidden');
    }

    // Proses Perubahan Status
    async function processStatus(statusName) {
        const id = document.getElementById('booking-id').value;
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
            }
        });
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>