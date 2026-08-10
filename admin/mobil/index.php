<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Data Mobil';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Master Data Armada</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola spesifikasi kendaraan dan harga sewa harian.</p>
        </div>
        <button type="button" onclick="openModalCar('add')" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold shadow-lg shadow-primary-600/20 hover:shadow-primary-600/30 transition-all duration-200 whitespace-nowrap">
            <i class="fa-solid fa-plus"></i><span>Tambah Armada</span>
        </button>
    </div>

    <!-- Tabel Component -->
    <?php include __DIR__ . '/table.php'; ?>

    <!-- Modal Component -->
    <?php include __DIR__ . '/modal_form.php'; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let tableCar;

    document.addEventListener('DOMContentLoaded', function() {
        loadTableCar();

        // Handler Submit Form
        document.getElementById('form-car').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const id = document.getElementById('car-id').value;

            // Tentukan Endpoint API berdasarkan Mode (Tambah / Edit)
            const apiUrl = id ? `<?= base_url('admin/mobil/update/') ?>${id}` : `<?= base_url('admin/mobil/store') ?>`;

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    body: formData // multipart/form-data otomatis diproses Fetch
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
                    closeModalCar();
                    tableCar.ajax.reload(null, false); // Reload tabel tanpa reset pagination
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
                    text: 'Gagal menghubungi server.'
                });
            }
        });
    });

    // Load DataTables
    function loadTableCar() {
        // Fungsi helper format Rupiah di JavaScript
        function formatRupiah(angka) {
            if (!angka) return 'Rp 0';
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(angka);
        }

        tableCar = $('#table-car').DataTable({
            ajax: {
                url: '<?= base_url('admin/mobil/api/get_all') ?>',
                dataSrc: 'data'
            },
            columns: [{
                    data: null,
                    render: function(data, type, row) {
                        const img = row.image_url ? `<img src="${row.image_url}" class="w-full h-full object-cover">` : `<div class="w-full h-full flex items-center justify-center text-slate-400"><i class="fa-solid fa-car"></i></div>`;
                        return `<div class="flex items-center gap-4">
                                    <div class="w-16 h-12 rounded-xl bg-slate-200/50 dark:bg-slate-800 overflow-hidden flex-shrink-0 shadow-sm">${img}</div>
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-slate-100">${row.name}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">${row.brand} • ${row.category_name}</p>
                                    </div>
                                </div>`;
                    }
                },
                {
                    data: 'plate_number',
                    render: data => `<span class="font-mono font-semibold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-md">${data}</span>`
                },
                {
                    data: null,
                    render: row => `<span class="font-semibold text-slate-700 dark:text-slate-200">${formatRupiah(row.price_per_day)}</span>`
                },
                {
                    data: null,
                    render: row => `<span class="text-xs text-slate-600 dark:text-slate-400">${row.fuel_type} • ${row.transmission}<br>${row.year} • ${row.capacity} Kursi</span>`
                },
                {
                    data: 'status_html'
                },
                {
                    data: null,
                    className: 'text-center',
                    render: row => `
                    <div class="flex justify-center gap-2">
                        <button onclick="editCar(${row.id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 transition-colors shadow-sm" title="Edit">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </button>
                        <button onclick="deleteCar(${row.id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-colors shadow-sm" title="Hapus">
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
                zeroRecords: "Data armada tidak ditemukan",
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

    // Open Modal Tambah
    function openModalCar(type) {
        document.getElementById('form-car').reset();
        document.getElementById('car-id').value = '';

        // Kembalikan gambar jadi required (wajib upload saat tambah baru)
        document.getElementById('input-images').setAttribute('required', 'required');
        document.getElementById('img-hint-edit').classList.add('hidden');

        document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-car text-primary mr-2"></i> Tambah Mobil Baru';
        document.getElementById('modal-car').classList.remove('hidden');

        // Set tahun sekarang setelah modal tampil
        requestAnimationFrame(() => {
            const roller = document.getElementById('year-roller');
            const yearInput = document.getElementById('year');

            if (!roller || !yearInput) return;

            const currentYear = new Date().getFullYear();
            const currentYearEl = roller.querySelector(`[data-value="${currentYear}"]`);

            if (!currentYearEl) return;

            const rollerCenter = roller.clientHeight / 2;
            const itemCenter = currentYearEl.offsetTop + (currentYearEl.clientHeight / 2);

            roller.scrollTop = itemCenter - rollerCenter;
            yearInput.value = currentYear;
        });
    }

    // Close Modal
    function closeModalCar() {
        document.getElementById('modal-car').classList.add('hidden');
    }

    // Helper Format Angka ke Rupiah yang Aman dari Desimal DB (.00)
    function formatRupiahNumber(val) {
        if (val === null || val === undefined || val === '') return '';

        let valStr = val.toString().trim();

        // Jika data berasal dari DB MySQL bertipe decimal (contoh: "500000.00")
        if (valStr.includes('.') && !valStr.includes(',')) {
            let parts = valStr.split('.');
            if (parts.length === 2 && parts[1].length <= 2) {
                valStr = parts[0];
            }
        }

        let cleanVal = valStr.replace(/[^0-9]/g, '');
        return cleanVal ? new Intl.NumberFormat('id-ID').format(cleanVal) : '';
    }

    // Listener Event 'input' untuk semua elemen .input-rupiah secara real-time
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('input-rupiah')) {
            let cursorPosition = e.target.selectionStart;
            let originalLength = e.target.value.length;

            e.target.value = formatRupiahNumber(e.target.value);

            let newLength = e.target.value.length;
            cursorPosition = cursorPosition + (newLength - originalLength);
            e.target.setSelectionRange(cursorPosition, cursorPosition);
        }
    });

    // Update Fungsi editCar()
    // Update Fungsi editCar() dengan Konversi Math.round untuk Keamanan Ekstra
    async function editCar(id) {
        const res = await fetch(`<?= base_url('admin/mobil/api/get_by_id/') ?>${id}`);
        const result = await res.json();

        if (result.status) {
            const data = result.data;
            document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-pen-to-square text-primary mr-2"></i> Edit Data Mobil';

            document.getElementById('car-id').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('brand').value = data.brand;
            document.getElementById('category_id').value = data.category_id;

            // Konversi nilai desimal DB ke integer murni sebelum diformat
            document.getElementById('price_per_day').value = formatRupiahNumber(Math.round(parseFloat(data.price_per_day || 0)));

            document.getElementById('plate_number').value = data.plate_number;

            // Set nilai tahun dan sesuaikan posisi rol tahun sesuai data mobil
            document.getElementById('year').value = data.year;
            requestAnimationFrame(() => {
                const roller = document.getElementById('year-roller');
                if (roller) {
                    const targetYearEl = roller.querySelector(`[data-value="${data.year}"]`);
                    if (targetYearEl) {
                        roller.scrollTop = targetYearEl.offsetTop - roller.clientHeight / 2 + targetYearEl.clientHeight / 2;
                    }
                }
            });

            document.getElementById('capacity').value = data.capacity;
            document.getElementById('transmission').value = data.transmission;
            document.getElementById('fuel_type').value = data.fuel_type;
            document.getElementById('status').value = data.status;
            document.getElementById('features').value = data.features;
            document.getElementById('description').value = data.description;

            document.getElementById('input-images').removeAttribute('required');
            document.getElementById('img-hint-edit').classList.remove('hidden');

            document.getElementById('modal-car').classList.remove('hidden');
        }
    }

    // Hapus via Fetch API
    function deleteCar(id) {
        Swal.fire({
            title: 'Hapus Armada?',
            text: "Data dan foto fisik akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#0f172a'
        }).then(async (res) => {
            if (res.isConfirmed) {
                const response = await fetch(`<?= base_url('admin/mobil/delete/') ?>${id}`, {
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
                    tableCar.ajax.reload(null, false);
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
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>