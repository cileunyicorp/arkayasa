<?php
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<!-- 1. HERO BANNER SECTION -->
<section class="relative bg-slate-900 text-white overflow-hidden py-24 sm:py-32">
    <!-- Gambar Background blur estetik -->
    <div class="absolute inset-0 opacity-40 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=1920');"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/70 to-transparent"></div>

    <div class="relative max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary-600/20 text-primary-600 text-xs font-bold uppercase tracking-wider border border-primary/30">
                <i class="fa-solid fa-award"></i> Sewa Mobil Terbaik Bandung
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                Armada Prima Untuk <br>
                Perjalanan <span class="text-primary-600">Istimewa Anda</span>
            </h1>
            <p class="text-base sm:text-lg text-slate-300 leading-relaxed">
                Temukan kepuasan berkendara dengan kenyamanan maksimal bersama <?= escape(APP_NAME) ?>. Tersedia berbagai unit mobil mewah siap pakai dengan layanan lepas kunci atau plus pengemudi handal.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 pt-2">
                <a href="<?= base_url('cars') ?>" class="px-6 py-3.5 text-center rounded-xl bg-primary-600 hover:hover:bg-primary-700 font-bold text-white shadow-lg shadow-primary/35 transition">
                    Lihat Pilihan Mobil
                </a>
                <a href="#cara-booking" class="px-6 py-3.5 text-center rounded-xl border border-slate-500 hover:bg-white hover:text-slate-900 font-bold transition">
                    Cara Reservasi
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 1. CLIENTS SECTION -->
<section class="relative py-1 overflow-hidden bg-white dark:bg-slate-950 transition-colors duration-300">

    <!-- Background Decoration -->
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[500px] h-[250px] rounded-full bg-primary-50 dark:bg-primary-950/20 blur-3xl"></div>
    </div>

    <!-- Container -->
    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        <div class="mb-4 flex justify-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary-600/20 text-primary-600 text-xs font-bold uppercase tracking-wider border border-primary/30">
                <!-- Animated Dot -->
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full rounded-full bg-primary-500 opacity-75 animate-ping"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-primary-600 dark:bg-primary-400"></span>
                </span>
                Mitra Terpercaya
            </span>
        </div>

        <?php if (!empty($clients)) : ?>

            <div class="relative">

                <!-- Left Fade -->
                <div class="pointer-events-none absolute z-10 left-0 top-0 bottom-0 w-24 sm:w-36 bg-linear-to-r from-white dark:from-slate-950 to-transparent"></div>

                <!-- Right Fade -->
                <div class="pointer-events-none absolute z-10 right-0 top-0 bottom-0 w-24 sm:w-36 bg-linear-to-l from-white dark:from-slate-950 to-transparent"></div>

                <!-- Marquee Window -->
                <div class="overflow-hidden group py-4">

                    <!-- Track -->
                    <div class="flex w-max animate-client-marquee group-hover:[animation-play-state:paused]">

                        <!-- =================================================
                             SET 1
                        ================================================== -->
                        <div class="flex items-center gap-4 sm:gap-6 lg:gap-8 pr-4 sm:pr-6 lg:pr-8">
                            <?php foreach ($clients as $client) : ?>
                                <div class="shrink-0 flex items-center justify-center px-2 py-1 transition-all duration-300 hover:-translate-y-1" title="<?= escape($client['name']) ?>">
                                    <img src="<?= base_url('assets/images/clients/' . escape($client['logo'])) ?>" alt="<?= escape($client['name']) ?>" loading="lazy" class="max-h-20 sm:max-h-24 max-w-full w-auto object-contain grayscale opacity-70 drop-shadow-[0_4px_6px_rgba(0,0,0,0.08)] dark:drop-shadow-[0_4px_6px_rgba(255,255,255,0.05)] hover:grayscale-0 hover:opacity-100 hover:drop-shadow-[0_10px_12px_rgba(0,0,0,0.15)] dark:hover:drop-shadow-[0_10px_12px_rgba(255,255,255,0.12)] transition-all duration-500">
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- =================================================
                             SET 2 (Duplicate untuk looping tanpa putus)
                        ================================================== -->
                        <div class="flex items-center gap-4 sm:gap-6 lg:gap-8 pr-4 sm:pr-6 lg:pr-8" aria-hidden="true">
                            <?php foreach ($clients as $client) : ?>
                                <div class="shrink-0 flex items-center justify-center px-2 py-1 transition-all duration-300 hover:-translate-y-1">
                                    <img src="<?= base_url('assets/images/clients/' . escape($client['logo'])) ?>" alt="" loading="lazy" class="max-h-20 sm:max-h-24 max-w-full w-auto object-contain grayscale opacity-70 drop-shadow-[0_4px_6px_rgba(0,0,0,0.08)] dark:drop-shadow-[0_4px_6px_rgba(255,255,255,0.05)] hover:grayscale-0 hover:opacity-100 hover:drop-shadow-[0_10px_12px_rgba(0,0,0,0.15)] dark:hover:drop-shadow-[0_10px_12px_rgba(255,255,255,0.12)] transition-all duration-500">
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>

                </div>

            </div>

        <?php else : ?>

            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-900 text-slate-400 dark:text-slate-600 mb-3">
                    <i class="fa-solid fa-building"></i>
                </div>
                <p class="text-sm text-slate-400 dark:text-slate-500">
                    Belum ada data mitra.
                </p>
            </div>

        <?php endif; ?>

        <!-- =====================================================
             TRUST INDICATOR
        ====================================================== -->
        <?php if (!empty($clients)) : ?>
            <div class="flex items-center justify-center gap-3 mt-6 text-xs font-medium text-slate-400 dark:text-slate-500">
                <span class="w-8 h-px bg-slate-200 dark:bg-slate-800"></span>
                <span>Dipercaya oleh <?= count($clients) ?>+ mitra</span>
                <span class="w-8 h-px bg-slate-200 dark:bg-slate-800"></span>
            </div>
        <?php endif; ?>

    </div>

</section>

<!-- 2. FORM FILTER CARI CEPAT -->
<section class="max-w-6xl mx-auto px-6 -mt-10 relative z-20">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xl p-6 sm:p-8">
        <form action="<?= base_url('cars') ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Merek / Tipe Mobil</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400"><i class="fa-solid fa-magnifying-glass text-xs"></i></span>
                    <input type="text" name="search" placeholder="Contoh: Innova, Avanza" class="w-full pl-9 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:border-primary transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Transmisi</label>
                <select name="transmission" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none appearance-none cursor-pointer">
                    <option value="">Semua Transmisi</option>
                    <option value="Manual">Manual</option>
                    <option value="Automatic">Automatic</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tipe BBM</label>
                <select name="fuel" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none appearance-none cursor-pointer">
                    <option value="">Semua Bahan Bakar</option>
                    <option value="Bensin">Bensin</option>
                    <option value="Solar">Solar</option>
                    <option value="Listrik">Listrik</option>
                </select>
            </div>
            <button type="submit" class="w-full py-3.5 bg-primary-600 hover:hover:bg-primary-700 text-white font-bold rounded-xl shadow-md shadow-primary-600/30 transition">
                <i class="fa-solid fa-filter mr-2"></i> Cari Mobil
            </button>
        </form>
    </div>
</section>

<!-- 3. KATALOG TERBARU / REKOMENDASI -->
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center max-w-xl mx-auto mb-12">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Katalog Armada Terpopuler</h2>
        <p class="text-sm text-slate-500 mt-2">Daftar mobil unggulan berstatus siap pakai untuk mendampingi mobilitas Anda hari ini.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php if (empty($cars)): ?>
            <div class="col-span-4 text-center py-12">
                <p class="text-slate-400 italic">Maaf, belum ada armada mobil yang tersedia saat ini.</p>
            </div>
        <?php else: ?>
            <?php foreach ($cars as $car): ?>
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-md overflow-hidden hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                    <!-- Foto Mobil -->
                    <div class="h-44 bg-slate-100 overflow-hidden relative">
                        <?php if ($car['primary_image']): ?>
                            <img src="<?= base_url('admin/assets/uploads/cars/' . escape($car['primary_image'])) ?>" alt="<?= escape($car['name']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fa-solid fa-car text-5xl"></i></div>
                        <?php endif; ?>
                        <span class="absolute top-3 right-3 px-2.5 py-1 text-[9px] font-bold uppercase rounded-lg bg-emerald-100 text-emerald-700 tracking-wider">
                            Ready
                        </span>
                    </div>

                    <!-- Detail Mobil -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-widest"><?= escape($car['brand']) ?></span>
                            <h3 class="text-lg font-bold text-slate-800 tracking-tight mt-0.5"><?= escape($car['name']) ?></h3>

                            <!-- Atribut Mini -->
                            <div class="flex flex-wrap gap-2 mt-3 mb-4 text-[11px] text-slate-500 font-semibold">
                                <span class="bg-slate-100 px-2 py-1 rounded-md"><i class="fa-solid fa-gears text-slate-400 mr-1"></i> <?= escape($car['transmission']) ?></span>
                                <span class="bg-slate-100 px-2 py-1 rounded-md"><i class="fa-solid fa-gas-pump text-slate-400 mr-1"></i> <?= escape($car['fuel_type']) ?></span>
                                <span class="bg-slate-100 px-2 py-1 rounded-md"><i class="fa-solid fa-users text-slate-400 mr-1"></i> <?= escape((string)$car['capacity']) ?> Kursi</span>
                            </div>
                        </div>

                        <!-- Harga & Action -->
                        <div class="border-t border-slate-100 pt-4 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-semibold">Mulai Dari</p>
                                <p class="text-base font-extrabold text-primary">Rp <?= number_format($car['price_per_day'], 0, ',', '.') ?> <span class="text-xs text-slate-400 font-normal">/hari</span></p>
                            </div>
                            <a href="<?= base_url('car/detail/' . $car['id']) ?>" class="px-4 py-2 bg-primary-600 hover:hover:bg-primary-700 text-white text-xs font-bold rounded-xl shadow-md shadow-primary/20 transition-all">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- 4. CARA BOOKING SECTION -->
<section id="cara-booking" class="bg-slate-100 py-20 border-y border-slate-200/60">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-xl mx-auto mb-16">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Langkah Mudah Sewa Mobil</h2>
            <p class="text-sm text-slate-500 mt-2">Reservasi armada impian Anda hanya dalam hitungan menit lewat 4 langkah mudah.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-8">
            <div class="text-center space-y-3">
                <div class="w-14 h-14 mx-auto bg-primary-600/10 text-primary text-xl font-bold rounded-2xl flex items-center justify-center shadow-inner">1</div>
                <h4 class="font-bold text-slate-800">Pilih Armada</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Cari mobil yang sesuai kebutuhan kapasitas, tipe transmisi, dan harga.</p>
            </div>
            <div class="text-center space-y-3">
                <div class="w-14 h-14 mx-auto bg-primary-600/10 text-primary text-xl font-bold rounded-2xl flex items-center justify-center shadow-inner">2</div>
                <h4 class="font-bold text-slate-800">Tentukan Tanggal</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Tentukan durasi sewa sepuas Anda (harian, mingguan, bulanan).</p>
            </div>
            <div class="text-center space-y-3">
                <div class="w-14 h-14 mx-auto bg-primary-600/10 text-primary text-xl font-bold rounded-2xl flex items-center justify-center shadow-inner">3</div>
                <h4 class="font-bold text-slate-800">Selesaikan Pembayaran</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Selesaikan proses transfer aman dan unggah foto struk bukti pembayaran.</p>
            </div>
            <div class="text-center space-y-3">
                <div class="w-14 h-14 mx-auto bg-primary-600/10 text-primary text-xl font-bold rounded-2xl flex items-center justify-center shadow-inner">4</div>
                <h4 class="font-bold text-slate-800">Serah Terima Kunci</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Mobil siap diantarkan atau diambil langsung di garasi Arkayasa.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>