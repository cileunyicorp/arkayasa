<?php
require_once __DIR__ . '/../includes/auth.php';
$title = 'Keuangan';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Catatan Keuangan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Pantau arus kas, pendapatan sewa, dan pengeluaran operasional.</p>
        </div>
        <button onclick="openModalFinance('add')" class="px-5 py-2.5 bg-primary-600 hover:hover:bg-primary-700 text-white rounded-xl shadow-lg shadow-primary-600/30 transition-all font-medium flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> <span>Input Transaksi</span>
        </button>
    </div>

    <!-- Ringkasan Kartu -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white/70 dark:bg-slate-900/70 backdrop-blur-md rounded-2xl border border-emerald-200/50 dark:border-emerald-800/50 p-6 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Total Pemasukan</p>
                <h3 class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">Rp <?= number_format($summary['income'], 0, ',', '.') ?></h3>
            </div>
            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-arrow-trend-up"></i></div>
        </div>
        <div class="bg-white/70 dark:bg-slate-900/70 backdrop-blur-md rounded-2xl border border-rose-200/50 dark:border-rose-800/50 p-6 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Total Pengeluaran</p>
                <h3 class="text-2xl font-extrabold text-rose-600 dark:text-rose-400">Rp <?= number_format($summary['expense'], 0, ',', '.') ?></h3>
            </div>
            <div class="w-12 h-12 bg-rose-100 dark:bg-rose-900/30 text-rose-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-arrow-trend-down"></i></div>
        </div>
        <div class="bg-white/70 dark:bg-slate-900/70 backdrop-blur-md rounded-2xl border border-indigo-200/50 dark:border-indigo-800/50 p-6 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Saldo Kas Tersedia</p>
                <h3 class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">Rp <?= number_format($summary['balance'], 0, ',', '.') ?></h3>
            </div>
            <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-wallet"></i></div>
        </div>
    </div>

    <?php include __DIR__ . '/table.php'; ?>
    <?php include __DIR__ . '/modal_form.php'; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let tableFinance;

    document.addEventListener('DOMContentLoaded', function() {
        loadTableFinance();

        document.getElementById('form-finance').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = document.getElementById('finance-id').value;
            const apiUrl = id ? `<?= base_url('admin/keuangan/update/') ?>${id}` : `<?= base_url('admin/keuangan/store') ?>`;

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
                    }).then(() => {
                        window.location.reload(); // Reload untuk memperbarui 3 Kartu Saldo
                    });
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

    function loadTableFinance() {
        tableFinance = $('#table-finance').DataTable({
            ajax: {
                url: '<?= base_url('admin/keuangan/api/get_all') ?>',
                dataSrc: 'data'
            },
            columns: [{
                    data: 'date_format',
                    render: data => `<span class="font-semibold text-slate-700 dark:text-slate-300">${data}</span>`
                },
                {
                    data: 'type_html'
                },
                {
                    data: null,
                    render: row => `<div>
                                        <p class="font-bold text-slate-800 dark:text-slate-100">${row.category}</p>
                                        <p class="text-xs text-slate-500 block max-w-xs truncate" title="${row.description}">${row.description || '-'}</p>
                                    </div>`
                },
                {
                    data: 'amount_format'
                },
                {
                    data: null,
                    className: 'text-center',
                    render: row => `
                    <div class="flex justify-center gap-2">
                        <button onclick="editFinance(${row.id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 transition-colors shadow-sm" title="Edit">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </button>
                        <button onclick="deleteFinance(${row.id})" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-colors shadow-sm" title="Hapus">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>`
                }
            ],
            order: [
                [0, 'desc']
            ], // Urutkan dari tanggal terbaru
            language: {
                lengthMenu: "Tampilkan _MENU_ data",
                search: "Cari:",

                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ data)",

                zeroRecords: "Belum ada transaksi keuangan",
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

    function openModalFinance(type) {
        document.getElementById('form-finance').reset();
        document.getElementById('finance-id').value = '';
        document.getElementById('transaction_date').value = new Date().toISOString().split('T')[0];

        document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-plus text-primary mr-2"></i> Input Transaksi Baru';
        document.getElementById('modal-finance').classList.remove('hidden');
    }

    function closeModalFinance() {
        document.getElementById('modal-finance').classList.add('hidden');
    }

    async function editFinance(id) {
        const res = await fetch(`<?= base_url('admin/keuangan/api/get_by_id/') ?>${id}`);
        const result = await res.json();

        if (result.status) {
            const data = result.data;
            document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-pen-to-square text-primary mr-2"></i> Edit Data Keuangan';

            document.getElementById('finance-id').value = data.id;
            document.getElementById('transaction_date').value = data.transaction_date;
            document.getElementById('type').value = data.type;
            document.getElementById('category').value = data.category;
            document.getElementById('amount').value = data.amount;
            document.getElementById('description').value = data.description;

            document.getElementById('modal-finance').classList.remove('hidden');
        }
    }

    function deleteFinance(id) {
        Swal.fire({
            title: 'Hapus Transaksi?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!'
        }).then(async (res) => {
            if (res.isConfirmed) {
                const response = await fetch(`<?= base_url('admin/keuangan/delete/') ?>${id}`, {
                    method: 'POST'
                });
                const result = await response.json();

                if (result.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
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