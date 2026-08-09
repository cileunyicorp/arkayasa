<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Data Pelanggan';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>


<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Data Pelanggan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola akun dan identitas (NIK/SIM) pengguna yang menyewa kendaraan.</p>
        </div>
        <button onclick="openModalCustomer('add')" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700-dark text-white rounded-xl shadow-lg shadow-primary/30 transition-all font-medium flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i> <span>Tambah Pelanggan</span>
        </button>
    </div>

    <!-- Tabel Component -->
    <?php include __DIR__ . '/table.php'; ?>

    <!-- Modal Component -->
    <?php include __DIR__ . '/modal_form.php'; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let tableCustomer;

    document.addEventListener('DOMContentLoaded', function() {
        loadTableCustomer();

        // Handler Submit Form (Tambah/Edit)
        document.getElementById('form-customer').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const id = document.getElementById('customer-id').value;
            const apiUrl = id ? `<?= base_url('admin/pelanggan/update/') ?>${id}` : `<?= base_url('admin/pelanggan/store') ?>`;

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
                    closeModalCustomer();
                    tableCustomer.ajax.reload(null, false);
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

    // Inisialisasi DataTables
    function loadTableCustomer() {
        tableCustomer = $('#table-customer').DataTable({
            ajax: {
                url: '<?= base_url('admin/pelanggan/api/get_all') ?>',
                dataSrc: 'data'
            },
            columns: [{
                    data: null,
                    render: function(data, type, row) {
                        return `<div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(row.name)}&background=f8fafc&color=334155" class="w-10 h-10 rounded-xl border border-slate-200 dark:border-slate-700">
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-slate-100">${row.name}</p>
                                        <p class="text-xs text-slate-500">${row.email}</p>
                                    </div>
                                </div>`;
                    }
                },
                {
                    data: 'phone',
                    render: data => `<span class="font-semibold text-slate-600 dark:text-slate-300">${data || '-'}</span>`
                },
                {
                    data: 'nik',
                    render: data => `<span class="font-mono text-sm bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded text-slate-700 dark:text-slate-300">${data}</span>`
                },
                {
                    data: 'driver_license_number',
                    render: data => `<span class="font-mono text-sm bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded text-slate-700 dark:text-slate-300">${data || '-'}</span>`
                },
                {
                    data: 'address',
                    render: data => `<span class="text-xs text-slate-500 dark:text-slate-400 block max-w-xs truncate" title="${data}">${data || '-'}</span>`
                },
                {
                    data: null,
                    className: 'text-center',
                    render: row => `
                    <div class="flex justify-center gap-2">
                        <button onclick="editCustomer(${row.customer_id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 transition-colors shadow-sm" title="Edit">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </button>
                        <button onclick="deleteCustomer(${row.customer_id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-colors shadow-sm" title="Hapus">
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
                zeroRecords: "Pelanggan tidak ditemukan",
                emptyTable: "Belum ada data pelanggan",

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

    function openModalCustomer(type) {
        document.getElementById('form-customer').reset();
        document.getElementById('customer-id').value = '';

        document.getElementById('password').setAttribute('required', 'required');
        document.getElementById('pass-hint-edit').classList.add('hidden');

        document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-user-plus text-primary mr-2"></i> Tambah Pelanggan Baru';
        document.getElementById('modal-customer').classList.remove('hidden');
    }

    function closeModalCustomer() {
        document.getElementById('modal-customer').classList.add('hidden');
    }

    async function editCustomer(id) {
        const res = await fetch(`<?= base_url('admin/pelanggan/api/get_by_id/') ?>${id}`);
        const result = await res.json();

        if (result.status) {
            const data = result.data;
            document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-user-pen text-primary mr-2"></i> Edit Data Pelanggan';

            document.getElementById('customer-id').value = data.customer_id;
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('phone').value = data.phone;
            document.getElementById('nik').value = data.nik;
            document.getElementById('driver_license_number').value = data.driver_license_number;
            document.getElementById('address').value = data.address;

            // Password opsional saat edit
            document.getElementById('password').removeAttribute('required');
            document.getElementById('pass-hint-edit').classList.remove('hidden');

            document.getElementById('modal-customer').classList.remove('hidden');
        }
    }

    function deleteCustomer(id) {
        Swal.fire({
            title: 'Hapus Pelanggan?',
            text: "Data akun dan profil pelanggan akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!'
        }).then(async (res) => {
            if (res.isConfirmed) {
                const formData = new FormData();
                formData.append('id', id);

                const response = await fetch(`<?= base_url('admin/pelanggan/delete/') ?>${id}`, {
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
                    tableCustomer.ajax.reload(null, false);
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