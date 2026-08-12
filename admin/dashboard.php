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
            <h2 class="text-3xl font-black text-slate-800 dark:text-white"><?= escape((string)($totalCars ?? 0)) ?></h2>
            <p class="text-[11px] text-slate-400 mt-1">Unit Kendaraan Terdaftar</p>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center text-2xl shadow-inner">
            <i class="fa-solid fa-car"></i>
        </div>
    </div>
    
    <!-- Card Booking -->
    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-none border border-slate-200/60 dark:border-slate-800 p-6 flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1">Total Booking</p>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white"><?= escape((string)($totalBookings ?? 0)) ?></h2>
            <p class="text-[11px] text-slate-400 mt-1">Transaksi Pemesanan</p>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center text-2xl shadow-inner">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
    </div>
    
    <!-- Card Pelanggan -->
    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-none border border-slate-200/60 dark:border-slate-800 p-6 flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1">Total Pelanggan</p>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white"><?= escape((string)($totalCustomers ?? 0)) ?></h2>
            <p class="text-[11px] text-slate-400 mt-1">Pelanggan Aktif</p>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-sky-100 dark:bg-sky-900/30 text-sky-600 flex items-center justify-center text-2xl shadow-inner">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>
    
    <!-- Card Saldo Kas (Real-time dari Keuangan) -->
    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-none border border-slate-200/60 dark:border-slate-800 p-6 flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1">Saldo Kas Aktif</p>
            <h2 class="text-2xl font-black text-emerald-600 dark:text-emerald-400">Rp <?= number_format($financeSummary['balance'] ?? 0, 0, ',', '.') ?></h2>
            <p class="text-[11px] text-slate-400 mt-1">Arus Kas Terkini</p>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center text-2xl shadow-inner">
            <i class="fa-solid fa-wallet"></i>
        </div>
    </div>
</div>

<!-- Ringkasan Status Armada & Aktivitas -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Kolom 1 & 2: Transaksi Booking Terbaru -->
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
                        <th class="py-3 px-2">Total Bayar</th>
                        <th class="py-3 px-2">Sisa Tagihan</th>
                        <th class="py-3 px-2">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    <?php if(empty($recentBookings)): ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 italic">Belum ada transaksi sewa.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($recentBookings as $booking): ?>
                            <?php 
                                $grandTotal = (float)($booking['total_price'] ?? 0);
                                $deposit    = (float)($booking['deposit'] ?? 0);
                                $sisaBayar  = max(0, $grandTotal - $deposit);
                            ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="py-3 px-2 font-mono font-semibold text-slate-700 dark:text-slate-300"><?= escape($booking['booking_code']) ?></td>
                                <td class="py-3 px-2 font-bold text-slate-800 dark:text-slate-100"><?= escape($booking['customer_name']) ?></td>
                                <td class="py-3 px-2 text-xs">
                                    <span class="block font-semibold"><?= escape($booking['car_name']) ?></span>
                                    <span class="text-slate-400"><?= escape($booking['car_plate'] ?? '-') ?></span>
                                </td>
                                <td class="py-3 px-2 font-bold text-slate-800 dark:text-slate-200">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
                                <td class="py-3 px-2 font-bold">
                                    <?php if ($sisaBayar <= 0): ?>
                                        <span class="text-emerald-600 dark:text-emerald-400 text-xs">Lunas</span>
                                    <?php else: ?>
                                        <span class="text-rose-600 dark:text-rose-400 text-xs">Rp <?= number_format($sisaBayar, 0, ',', '.') ?></span>
                                    <?php endif; ?>
                                </td>
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

    <!-- Kolom 3: Grafik Aktivitas & Status Ketersediaan Armada -->
    <div class="space-y-6 lg:col-span-1">
        
        <!-- Widget Status Armada -->
        <div class="bg-white/70 dark:bg-slate-900/70 backdrop-blur-md rounded-2xl shadow-lg border border-slate-200/60 dark:border-slate-800 p-6">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider mb-4"><i class="fa-solid fa-[#B30427] fa-car-side mr-2"></i> Status Armada</h3>
            <div class="grid grid-cols-3 gap-3 text-center">
                <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/40 rounded-xl">
                    <span class="block text-xl font-extrabold text-emerald-600 dark:text-emerald-400"><?= $carStatusSummary['available'] ?? 0 ?></span>
                    <span class="text-[10px] font-bold uppercase text-emerald-700 dark:text-emerald-300">Tersedia</span>
                </div>
                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/40 rounded-xl">
                    <span class="block text-xl font-extrabold text-amber-600 dark:text-amber-400"><?= $carStatusSummary['rented'] ?? 0 ?></span>
                    <span class="text-[10px] font-bold uppercase text-amber-700 dark:text-amber-300">Disewa</span>
                </div>
                <div class="p-3 bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800/40 rounded-xl">
                    <span class="block text-xl font-extrabold text-rose-600 dark:text-rose-400"><?= $carStatusSummary['maintenance'] ?? 0 ?></span>
                    <span class="text-[10px] font-bold uppercase text-rose-700 dark:text-rose-300">Servis</span>
                </div>
            </div>
        </div>

        <!-- Grafik Aktivitas Transaksi Bulanan -->
        <div class="bg-white/70 dark:bg-slate-900/70 backdrop-blur-md rounded-2xl shadow-lg border border-slate-200/60 dark:border-slate-800 p-6">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider mb-4"><i class="fa-solid fa-chart-line text-primary mr-2"></i> Tren Transaksi</h3>
            <div class="relative h-48 w-full">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>

    </div>
</div>

<!-- Script Chart.js -->
<script>
    const ctx = document.getElementById('dashboardChart').getContext('2d');
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 200);
    gradient.addColorStop(0, 'rgba(179, 4, 39, 0.4)');
    gradient.addColorStop(1, 'rgba(179, 4, 39, 0.0)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chartLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) ?>,
            datasets: [{
                label: 'Transaksi',
                data: <?= json_encode($chartData ?? [0, 0, 0, 0, 0, 0]) ?>,
                backgroundColor: gradient,
                borderColor: '#B30427',
                borderWidth: 2,
                borderRadius: 6,
                hoverBackgroundColor: '#8E031F'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(226, 232, 240, 0.1)' }, ticks: { color: '#94a3b8', stepSize: 1 } },
                x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
            }
        }
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
