<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Perawatan Mobil';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>


<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Riwayat Perawatan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola catatan servis, perbaikan armada, dan pengeluaran operasional.</p>
        </div>
        <button onclick="openModalMaintenance('add')" class="px-5 py-2.5 bg-primary-600 hover:hover:bg-primary-700 text-white rounded-xl shadow-lg shadow-primary-600/30 transition-all font-medium flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> <span>Catat Perawatan</span>
        </button>
    </div>

    <?php include __DIR__ . '/table.php'; ?>
    <?php include __DIR__ . '/modal_form.php'; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let tableMaint;

    document.addEventListener('DOMContentLoaded', function() {
        loadTableMaint();

        document.getElementById('form-maintenance').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = document.getElementById('maintenance-id').value;
            const apiUrl = id ? `<?= base_url('admin/perawatan/update/') ?>${id}` : `<?= base_url('admin/perawatan/store') ?>`;

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
                    closeModalMaintenance();
                    tableMaint.ajax.reload(null, false);
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

    function loadTableMaint() {
        tableMaint = $('#table-maintenance').DataTable({
            ajax: {
                url: '<?= base_url('admin/perawatan/api/get_all') ?>',
                dataSrc: 'data'
            },
            columns: [{
                    data: 'date_format',
                    render: data => `<span class="font-semibold text-slate-700 dark:text-slate-300">${data}</span>`
                },
                {
                    data: null,
                    render: row => `<div>
                                        <p class="font-bold text-slate-800 dark:text-slate-100">${row.car_name}</p>
                                        <p class="text-xs font-mono text-slate-500">${row.plate_number}</p>
                                    </div>`
                },
                {
                    data: null,
                    render: row => `<div>
                                        <p class="font-semibold text-slate-700 dark:text-slate-200">${row.title}</p>
                                        <p class="text-xs text-slate-500 block max-w-xs truncate" title="${row.description}">${row.description || '-'}</p>
                                    </div>`
                },
                {
                    data: 'cost_format',
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
                        <button onclick="editMaintenance(${row.id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 transition-colors shadow-sm" title="Edit">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </button>
                        <button onclick="deleteMaintenance(${row.id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-colors shadow-sm" title="Hapus">
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

                zeroRecords: "Belum ada riwayat perawatan",
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

    function openModalMaintenance(type) {
        document.getElementById('form-maintenance').reset();
        document.getElementById('maintenance-id').value = '';
        document.getElementById('maintenance_date').value = new Date().toISOString().split('T')[0]; // Set Hari Ini

        document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-plus text-primary mr-2"></i> Catat Perawatan Baru';
        document.getElementById('modal-maintenance').classList.remove('hidden');
    }

    function closeModalMaintenance() {
        document.getElementById('modal-maintenance').classList.add('hidden');
    }

    async function editMaintenance(id) {
        const res = await fetch(`<?= base_url('admin/perawatan/api/get_by_id/') ?>${id}`);
        const result = await res.json();

        if (result.status) {
            const data = result.data;
            document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-pen-to-square text-primary mr-2"></i> Edit Data Perawatan';

            document.getElementById('maintenance-id').value = data.id;
            document.getElementById('car_id').value = data.car_id;
            document.getElementById('maintenance_date').value = data.maintenance_date;
            document.getElementById('title').value = data.title;
            document.getElementById('description').value = data.description;
            document.getElementById('cost').value = data.cost;
            document.getElementById('status').value = data.status;

            document.getElementById('modal-maintenance').classList.remove('hidden');
        }
    }

    function deleteMaintenance(id) {
        Swal.fire({
            title: 'Hapus Catatan?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!'
        }).then(async (res) => {
            if (res.isConfirmed) {
                const response = await fetch(`<?= base_url('admin/perawatan/delete/') ?>${id}`, {
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
                    tableMaint.ajax.reload(null, false);
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