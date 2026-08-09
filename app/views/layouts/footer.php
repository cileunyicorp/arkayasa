<!-- Footer Publik -->
<footer class="bg-[#1E1E1E] text-white pt-16 pb-8 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">

        <!-- Kolom 1: Branding -->
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 bg-primary-600/20 text-primary rounded-xl">
                    <i class="fa-solid fa-car-side text-xl"></i>
                </div>
                <span class="text-lg font-black uppercase tracking-wider text-white"><?= escape(APP_NAME) ?></span>
            </div>
            <p class="text-sm text-slate-400 leading-relaxed">Penyedia jasa layanan sewa mobil harian, mingguan, hingga bulanan dengan armada prima dan jaminan pelayanan profesional terbaik.</p>
        </div>

        <!-- Kolom 2: Tautan Cepat -->
        <div>
            <h4 class="text-sm font-bold uppercase tracking-widest text-slate-300 mb-4 border-l-4 border-primary pl-2">Navigasi</h4>
            <ul class="space-y-2 text-sm text-slate-400">
                <li><a href="<?= base_url('') ?>" class="hover:text-primary transition">Home / Beranda</a></li>
                <li><a href="<?= base_url('cars') ?>" class="hover:text-primary transition">Daftar Armada</a></li>
                <li><a href="<?= base_url('articles') ?>" class="hover:text-primary transition">Artikel Blog</a></li>
                <li><a href="<?= base_url('contact') ?>" class="hover:text-primary transition">Kontak Kami</a></li>
            </ul>
        </div>

        <!-- Kolom 3: Kebijakan Sewa -->
        <div>
            <h4 class="text-sm font-bold uppercase tracking-widest text-slate-300 mb-4 border-l-4 border-primary pl-2">Informasi Sewa</h4>
            <ul class="space-y-2 text-sm text-slate-400">
                <li><span class="hover:text-slate-300">Sewa Lepas Kunci (S&K Berlaku)</span></li>
                <li><span class="hover:text-slate-300">Sewa Dengan Sopir Utama</span></li>
                <li><span class="hover:text-slate-300">Layanan Antar-Jemput Bandara</span></li>
                <li><span class="hover:text-slate-300">Layanan Darurat Roadside 24 Jam</span></li>
            </ul>
        </div>

        <!-- Kolom 4: Hubungi Kami -->
        <div>
            <h4 class="text-sm font-bold uppercase tracking-widest text-slate-300 mb-4 border-l-4 border-primary pl-2">Hubungi Kami</h4>
            <ul class="space-y-3 text-sm text-slate-400">
                <li class="flex items-center gap-3">
                    <i class="fa-solid fa-location-dot text-primary text-base"></i>
                    <span>Jl. Merdeka No. 123, Bandung, Jawa Barat</span>
                </li>
                <li class="flex items-center gap-3">
                    <i class="fa-solid fa-phone text-primary text-base"></i>
                    <span>0812-3456-7890</span>
                </li>
                <li class="flex items-center gap-3">
                    <i class="fa-solid fa-envelope text-primary text-base"></i>
                    <span>info@arkayasa.com</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Copyright -->
    <div class="max-w-7xl mx-auto px-6 border-t border-slate-800 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
        <p>&copy; <?= date('Y') ?> <?= escape(APP_NAME) ?>. Hak Cipta Dilindungi.</p>
        <div class="flex gap-4">
            <a href="#" class="hover:text-primary"><i class="fa-brands fa-whatsapp text-lg"></i></a>
            <a href="#" class="hover:text-primary"><i class="fa-brands fa-instagram text-lg"></i></a>
            <a href="#" class="hover:text-primary"><i class="fa-brands fa-facebook text-lg"></i></a>
        </div>
    </div>
</footer>
<script src="<?= base_url('assets/js/theme.js') ?>"></script>
<script src="<?= base_url('assets/js/navbar.js') ?>"></script>
</body>

</html>