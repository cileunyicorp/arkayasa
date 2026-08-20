<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Rent Partner';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Data Rent Partner</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola pemilik armada titipan / mitra penyedia kendaraan.</p>
        </div>
        <button onclick="openModalPartner('add')" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl shadow-lg shadow-primary-600/30 transition-all font-medium flex items-center gap-2">
            <i class="fa-solid fa-handshake"></i> <span>Tambah Mitra</span>
        </button>
    </div>

    <?php include __DIR__ . '/table.php'; ?>
    <?php include __DIR__ . '/modal_form.php'; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let tablePartner;

    document.addEventListener('DOMContentLoaded', function() {
        loadTablePartner();

        document.getElementById('form-partner').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = document.getElementById('partner-id').value;
            const apiUrl = id ? `<?= base_url('admin/partner/update/') ?>${id}` : `<?= base_url('admin/partner/store') ?>`;

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
                    closeModalPartner();
                    tablePartner.ajax.reload(null, false);
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

    function loadTablePartner() {
        tablePartner = $('#table-partner').DataTable({
            ajax: {
                url: '<?= base_url('admin/partner/api/get_all') ?>',
                dataSrc: 'data'
            },
            columns: [{
                    data: null,
                    render: row => `<div>
                                        <p class="font-bold text-slate-800 dark:text-slate-100">${row.name}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">${row.company_name || '-'}</p>
                                    </div>`
                },
                {
                    data: null,
                    render: row => `<div>
                                        <p class="font-semibold text-slate-700 dark:text-slate-300">${row.phone}</p>
                                        <p class="text-xs text-slate-400">${row.email || '-'}</p>
                                    </div>`
                },
                {
                    data: 'address',
                    render: data => `<span class="text-xs text-slate-500 block max-w-xs truncate" title="${data}">${data || '-'}</span>`
                },
                {
                    data: 'status_html'
                },
                {
                    data: null,
                    className: 'text-center',
                    render: row => `
                    <div class="flex justify-center gap-2">
                        <button onclick="editPartner(${row.id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 transition-colors shadow-sm" title="Edit">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </button>
                        <button onclick="deletePartner(${row.id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-colors shadow-sm" title="Hapus">
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
                zeroRecords: "Belum ada mitra rental terdaftar",
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

    function openModalPartner(type) {
        document.getElementById('form-partner').reset();
        document.getElementById('partner-id').value = '';
        document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-handshake text-primary mr-2"></i> Tambah Mitra Baru';
        document.getElementById('modal-partner').classList.remove('hidden');
    }

    function closeModalPartner() {
        document.getElementById('modal-partner').classList.add('hidden');
    }

    async function editPartner(id) {
        const res = await fetch(`<?= base_url('admin/partner/api/get_by_id/') ?>${id}`);
        const result = await res.json();

        if (result.status) {
            const data = result.data;
            document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-pen-to-square text-primary mr-2"></i> Edit Data Mitra';

            document.getElementById('partner-id').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('company_name').value = data.company_name || '';
            document.getElementById('phone').value = data.phone;
            document.getElementById('email').value = data.email || '';
            document.getElementById('status').value = data.status;
            document.getElementById('address').value = data.address || '';

            document.getElementById('modal-partner').classList.remove('hidden');
        }
    }

    function deletePartner(id) {
        Swal.fire({
            title: 'Hapus Mitra?',
            text: 'Data mitra akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!'
        }).then(async (res) => {
            if (res.isConfirmed) {
                const response = await fetch(`<?= base_url('admin/partner/delete/') ?>${id}`, {
                    method: 'POST'
                });
                const result = await response.json();

                if (result.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    tablePartner.ajax.reload(null, false);
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