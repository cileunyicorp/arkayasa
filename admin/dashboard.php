<?php
require_once __DIR__ . '/includes/auth.php';
$title = 'Dashboard Overview';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Header Halaman -->
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">Dashboard Arkayasa</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Ringkasan aktivitas armada, transaksi sewa, dan arus kas terkini.</p>
</div>

<!-- 4 Kartu Statistik Utama -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Card Mobil -->
    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-none border border-slate-200/60 dark:border-slate-800 p-6 flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1">Total Armada</p>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white"><?= escape((string)$totalCars) ?></h2>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center text-2xl shadow-inner">
            <i class="fa-solid fa-car"></i>
        </div>
    </div>
    
    <!-- Card Booking -->
    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-none border border-slate-200/60 dark:border-slate-800 p-6 flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1">Total Booking</p>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white"><?= escape((string)$totalBookings) ?></h2>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center text-2xl shadow-inner">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
    </div>
    
    <!-- Card Pelanggan -->
    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-none border border-slate-200/60 dark:border-slate-800 p-6 flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1">Total Pelanggan</p>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white"><?= escape((string)$totalCustomers) ?></h2>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-sky-100 dark:bg-sky-900/30 text-sky-600 flex items-center justify-center text-2xl shadow-inner">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>
    
    <!-- Card Saldo Kas (Real-time dari Keuangan) -->
    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-none border border-slate-200/60 dark:border-slate-800 p-6 flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1">Saldo Kas Aktif</p>
            <h2 class="text-2xl font-black text-emerald-600 dark:text-emerald-400">Rp <?= number_format($financeSummary['balance'], 0, ',', '.') ?></h2>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center text-2xl shadow-inner">
            <i class="fa-solid fa-wallet"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Kolom 1: Transaksi Booking Terbaru -->
    <div class="bg-white/70 dark:bg-slate-900/70 backdrop-blur-md rounded-2xl shadow-lg border border-slate-200/60 dark:border-slate-800 p-6 lg:col-span-2 flex flex-col">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white"><i class="fa-solid fa-clock-rotate-left text-primary mr-2"></i> Booking Terbaru</h3>
            <a href="<?= base_url('admin/booking') ?>" class="text-xs font-semibold text-primary hover:text-primary-dark transition">Lihat Semua &rarr;</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400 border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-xs uppercase tracking-wider text-slate-500">
                        <th class="py-3 px-2">Kode</th>
                        <th class="py-3 px-2">Pelanggan</th>
                        <th class="py-3 px-2">Kendaraan</th>
                        <th class="py-3 px-2">Harga</th>
                        <th class="py-3 px-2">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    <?php if(empty($recentBookings)): ?>
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 italic">Belum ada transaksi sewa.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($recentBookings as $booking): ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="py-3 px-2 font-mono font-semibold text-slate-700 dark:text-slate-300"><?= escape($booking['booking_code']) ?></td>
                                <td class="py-3 px-2 font-bold text-slate-800 dark:text-slate-100"><?= escape($booking['customer_name']) ?></td>
                                <td class="py-3 px-2 text-xs">
                                    <span class="block font-semibold"><?= escape($booking['car_name']) ?></span>
                                    <span class="text-slate-400"><?= escape($booking['car_plate']) ?></span>
                                </td>
                                <td class="py-3 px-2 font-bold text-emerald-600 dark:text-emerald-400">Rp <?= number_format($booking['total_price'], 0, ',', '.') ?></td>
                                <td class="py-3 px-2">
                                    <?php
                                        $bClass = 'bg-slate-100 text-slate-600';
                                        if ($booking['status'] === 'Menunggu Pembayaran') $bClass = 'bg-blue-100 text-blue-700';
                                        if ($booking['status'] === 'Approve') $bClass = 'bg-emerald-100 text-emerald-700';
                                        if ($booking['status'] === 'Dipinjam') $bClass = 'bg-amber-100 text-amber-700';
                                        if ($booking['status'] === 'Selesai' || $booking['status'] === 'Dikembalikan') $bClass = 'bg-indigo-100 text-indigo-700';
                                    ?>
                                    <span class="px-2 py-1 text-[10px] font-bold uppercase rounded-md <?= $bClass ?>"><?= escape($booking['status']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kolom 2: Grafik Ringkasan Pendapatan (Placeholder Statis Tema Selaras) -->
    <div class="bg-white/70 dark:bg-slate-900/70 backdrop-blur-md rounded-2xl shadow-lg border border-slate-200/60 dark:border-slate-800 p-6 lg:col-span-1">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-6"><i class="fa-solid fa-chart-line text-primary mr-2"></i> Grafik Aktivitas</h3>
        <div class="relative h-64 w-full">
            <canvas id="dashboardChart"></canvas>
        </div>
    </div>
</div>

<!-- Script Inisialisasi Chart.js Tema Marun -->
<script>
    const ctx = document.getElementById('dashboardChart').getContext('2d');
    
    // Gradasi Merah Marun
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(179, 4, 39, 0.4)');
    gradient.addColorStop(1, 'rgba(179, 4, 39, 0.0)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Transaksi',
                data: [12, 19, 13, 25, 20, 28],
                backgroundColor: gradient,
                borderColor: '#B30427',
                borderWidth: 2,
                borderRadius: 4,
                hoverBackgroundColor: '#8E031F'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(226, 232, 240, 0.1)' }, ticks: { color: '#94a3b8' } },
                x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
            }
        }
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
