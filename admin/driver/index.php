<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Data Sopir';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Master Data Sopir</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola identitas, lisensi pengemudi, dan tarif layanan sopir.</p>
        </div>
        <button onclick="openModalDriver('add')" class="px-5 py-2.5 bg-primary-600 hover:hover:bg-primary-700 text-white rounded-xl shadow-lg shadow-primary-600/30 transition-all font-medium flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i> <span>Tambah Sopir</span>
        </button>
    </div>

    <!-- Tabel Component -->
    <?php include __DIR__ . '/table.php'; ?>

    <!-- Modal Component -->
    <?php include __DIR__ . '/modal_form.php'; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let tableDriver;

    document.addEventListener('DOMContentLoaded', function() {
        loadTableDriver();

        document.getElementById('form-driver').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = document.getElementById('driver-id').value;
            const apiUrl = id ? `<?= base_url('admin/driver/update/') ?>${id}` : `<?= base_url('admin/driver/store') ?>`;

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
                    closeModalDriver();
                    tableDriver.ajax.reload(null, false);
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
    });

    function loadTableDriver() {
        tableDriver = $('#table-driver').DataTable({
            ajax: {
                url: '<?= base_url('admin/driver/api/get_all') ?>',
                dataSrc: 'data'
            },
            columns: [{
                    data: null,
                    render: function(data, type, row) {
                        const img = row.photo_url ? `<img src="${row.photo_url}" class="w-full h-full object-cover">` : `<div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-200 dark:bg-slate-800"><i class="fa-solid fa-user-tie"></i></div>`;
                        return `<div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 flex-shrink-0">${img}</div>
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-slate-100">${row.name}</p>
                                        <p class="text-xs text-slate-500">${row.phone}</p>
                                    </div>
                                </div>`;
                    }
                },
                {
                    data: 'nik',
                    render: data => `<span class="font-mono text-sm bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded text-slate-700 dark:text-slate-300">${data}</span>`
                },
                {
                    data: 'driver_license_number',
                    render: data => `<span class="font-mono text-sm font-semibold text-slate-700 dark:text-slate-300">${data}</span>`
                },
                {
                    data: 'price_format',
                    render: data => `<span class="font-bold text-primary">${data}</span>`
                },
                {
                    data: 'status_html'
                },
                {
                    data: null,
                    className: 'text-center',
                    render: row => `
                    <div class="flex justify-center gap-2">
                        <button onclick="editDriver(${row.id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 transition-colors shadow-sm" title="Edit">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </button>
                        <button onclick="deleteDriver(${row.id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-colors shadow-sm" title="Hapus">
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

                zeroRecords: "Belum ada data sopir",
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

    function openModalDriver(type) {
        document.getElementById('form-driver').reset();
        document.getElementById('driver-id').value = '';
        document.getElementById('photo-hint').classList.add('hidden');

        document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-user-plus text-primary mr-2"></i> Tambah Sopir Baru';
        document.getElementById('modal-driver').classList.remove('hidden');
    }

    function closeModalDriver() {
        document.getElementById('modal-driver').classList.add('hidden');
    }

    async function editDriver(id) {
        const res = await fetch(`<?= base_url('admin/driver/api/get_by_id/') ?>${id}`);
        const result = await res.json();

        if (result.status) {
            const data = result.data;
            document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-user-pen text-primary mr-2"></i> Edit Data Sopir';

            document.getElementById('driver-id').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('phone').value = data.phone;
            document.getElementById('nik').value = data.nik;
            document.getElementById('driver_license_number').value = data.driver_license_number;
            document.getElementById('price_per_day').value = data.price_per_day;
            document.getElementById('status').value = data.status;

            document.getElementById('photo-hint').classList.remove('hidden');
            document.getElementById('modal-driver').classList.remove('hidden');
        }
    }

    function deleteDriver(id) {
        Swal.fire({
            title: 'Hapus Sopir?',
            text: "Data dan foto profil akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!'
        }).then(async (res) => {
            if (res.isConfirmed) {
                const response = await fetch(`<?= base_url('admin/driver/delete/') ?>${id}`, {
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
                    tableDriver.ajax.reload(null, false);
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