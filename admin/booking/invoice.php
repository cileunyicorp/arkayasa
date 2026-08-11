<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?= htmlspecialchars($booking['booking_code'] ?? '') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        /* Mencegah background color hilang saat diprint */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                margin: 1cm;
                size: auto;
            }
        }
    </style>
</head>

<body class="bg-gray-100 py-10 print:py-0 print:bg-white text-slate-800">

    <!-- Tombol Kontrol (Akan hilang saat diprint berkat class 'print:hidden') -->
    <div class="max-w-4xl mx-auto mb-6 print:hidden flex justify-between items-center px-4">
        <a href="javascript:window.close()" class="px-4 py-2 bg-slate-300 hover:bg-slate-400 text-slate-800 rounded-xl font-medium transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
        <button onclick="window.print()" class="px-5 py-2 bg-[#B30427] hover:bg-[#8E031F] text-white rounded-xl shadow-lg transition font-bold flex items-center gap-2">
            <i class="fa-solid fa-print"></i> Cetak Invoice
        </button>
    </div>

    <!-- Kertas Invoice A4 -->
    <div class="max-w-4xl mx-auto bg-white min-h-[1000px] shadow-2xl print:shadow-none p-10 relative border border-gray-200 rounded-2xl print:rounded-none">

        <!-- Header Invoice -->
        <div class="flex justify-between items-start border-b-2 border-gray-100 pb-6 mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-[#B30427] tracking-tight uppercase"><?= htmlspecialchars(APP_NAME ?? 'ARKAYASA RENT CAR') ?></h1>
                <p class="text-sm text-gray-500 mt-1">Layanan Sewa Mobil Premium & Eksklusif</p>
                <div class="mt-3 text-xs text-gray-600 space-y-0.5">
                    <p><i class="fa-solid fa-location-dot text-[#B30427] mr-1"></i> Jl. Merdeka No. 123, Kota Bandung, Jawa Barat</p>
                    <p><i class="fa-solid fa-phone text-[#B30427] mr-1"></i> 0812-3456-7890 | <i class="fa-solid fa-envelope text-[#B30427] mr-1"></i> info@arkayasa.com</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-4xl font-black text-gray-200 uppercase tracking-widest">INVOICE</h2>
                <div class="mt-3">
                    <p class="text-xs font-semibold text-gray-500 uppercase">Nomor Invoice:</p>
                    <p class="text-lg font-mono font-bold text-[#B30427]"><?= htmlspecialchars($booking['booking_code'] ?? '') ?></p>
                </div>
                <div class="mt-1">
                    <p class="text-[11px] text-gray-400">Tanggal Terbit:</p>
                    <p class="text-xs font-semibold text-gray-700"><?= date('d F Y', strtotime($booking['created_at'] ?? 'now')) ?></p>
                </div>
            </div>
        </div>

        <!-- Info Pelanggan & Status -->
        <?php
        $totalDays    = (int)($booking['total_days'] ?? 1);
        $carPriceDay  = (float)($booking['car_price'] ?? 0);
        $driverFeeDay = (float)($booking['driver_fee'] ?? 0);
        $discount     = (float)($booking['discount'] ?? 0);
        $deposit      = (float)($booking['deposit'] ?? 0);
        $grandTotal   = (float)($booking['total_price'] ?? 0);
        $sisaBayar    = max(0, $grandTotal - $deposit);

        $sewaMobilTotal  = $carPriceDay * $totalDays;
        $sewaDriverTotal = $driverFeeDay * $totalDays;

        // KOREKSI UTAMA: Lunas HANYA jika sisa bayar = 0 (DP melunasi seluruh total) atau status sudah 'Selesai'
        $isLunas = ($sisaBayar <= 0 && $grandTotal > 0) || ($booking['status'] === 'Selesai');
        ?>

        <!-- Info Pelanggan & Status Pembayaran -->
        <div class="flex justify-between items-start mb-6">
            <div class="w-1/2">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Ditagihkan Kepada:</h3>
                <p class="text-base font-bold text-gray-800"><?= htmlspecialchars($booking['customer_name'] ?? '') ?></p>
                <p class="text-xs text-gray-600 mt-0.5">NIK: <?= htmlspecialchars($booking['customer_nik'] ?? '-') ?></p>
                <p class="text-xs text-gray-600"><?= htmlspecialchars($booking['customer_phone'] ?? '-') ?> | <?= htmlspecialchars($booking['customer_email'] ?? '-') ?></p>
                <p class="text-xs text-gray-600 mt-1"><?= nl2br(htmlspecialchars($booking['customer_address'] ?? '-')) ?></p>
            </div>
            <div class="w-1/3 p-4 rounded-xl <?= $isLunas ? 'bg-emerald-50 border border-emerald-200' : 'bg-rose-50 border border-rose-200' ?>">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Status Pembayaran:</h3>
                <?php if ($isLunas): ?>
                    <p class="text-xl font-black text-emerald-600 uppercase">LUNAS</p>
                <?php else: ?>
                    <p class="text-xl font-black text-rose-600 uppercase">BELUM LUNAS</p>
                    <p class="text-[11px] font-bold text-rose-500 mt-0.5">Sisa: Rp <?= number_format($sisaBayar, 0, ',', '.') ?></p>
                <?php endif; ?>
                <p class="text-xs text-gray-500 mt-1 font-medium">Status Sewa: <span class="font-bold text-gray-700"><?= htmlspecialchars($booking['status'] ?? '-') ?></span></p>
            </div>
        </div>


        <?php
        $totalDays   = (int)($booking['total_days'] ?? 1);
        $carPriceDay = (float)($booking['car_price'] ?? 0);
        $driverFeeDay = (float)($booking['driver_fee'] ?? 0);
        $discount    = (float)($booking['discount'] ?? 0);
        $grandTotal  = (float)($booking['total_price'] ?? 0);
        $sisaBayar   = max(0, $grandTotal - $deposit);

        $sewaMobilTotal = $carPriceDay * $totalDays;
        $sewaDriverTotal = $driverFeeDay * $totalDays;
        ?>

        <!-- Tabel Rincian -->
        <table class="w-full text-left border-collapse mb-6">
            <thead>
                <tr class="bg-gray-50 text-gray-700 text-xs uppercase tracking-wider border-y-2 border-gray-200">
                    <th class="py-3 px-4 font-bold w-1/2">Deskripsi Layanan</th>
                    <th class="py-3 px-4 font-bold text-center">Durasi</th>
                    <th class="py-3 px-4 font-bold text-right">Harga Satuan</th>
                    <th class="py-3 px-4 font-bold text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-xs">
                <!-- Row Mobil -->
                <tr>
                    <td class="py-3 px-4">
                        <p class="font-bold text-gray-800 text-sm">Sewa Armada: <?= htmlspecialchars($booking['car_brand'] ?? '') ?> <?= htmlspecialchars($booking['car_name'] ?? '') ?></p>
                        <p class="text-gray-500 mt-0.5">No. Polisi: <span class="font-mono font-semibold text-gray-700"><?= htmlspecialchars($booking['car_plate'] ?? '-') ?></span></p>
                        <p class="text-gray-400 mt-0.5">
                            Jadwal: <?= date('d M Y, H:i', strtotime($booking['start_date'] ?? 'now')) ?> s/d <?= date('d M Y, H:i', strtotime($booking['end_date'] ?? 'now')) ?>
                        </p>
                    </td>
                    <td class="py-3 px-4 text-center font-semibold text-gray-700">
                        <?= $totalDays ?> Hari
                    </td>
                    <td class="py-3 px-4 text-right text-gray-700">
                        Rp <?= number_format($carPriceDay, 0, ',', '.') ?>
                    </td>
                    <td class="py-3 px-4 text-right font-bold text-gray-900">
                        Rp <?= number_format($sewaMobilTotal, 0, ',', '.') ?>
                    </td>
                </tr>

                <!-- Row Driver (jika ada) -->
                <?php if (!empty($booking['driver_id']) || $driverFeeDay > 0): ?>
                    <tr>
                        <td class="py-3 px-4">
                            <p class="font-bold text-gray-800 text-sm">Layanan Sopir / Driver</p>
                            <p class="text-gray-500 mt-0.5">Nama Sopir: <span class="font-semibold text-gray-700"><?= htmlspecialchars($booking['driver_name'] ?? 'Driver Terpilih') ?></span></p>
                        </td>
                        <td class="py-3 px-4 text-center font-semibold text-gray-700">
                            <?= $totalDays ?> Hari
                        </td>
                        <td class="py-3 px-4 text-right text-gray-700">
                            Rp <?= number_format($driverFeeDay, 0, ',', '.') ?>
                        </td>
                        <td class="py-3 px-4 text-right font-bold text-gray-900">
                            Rp <?= number_format($sewaDriverTotal, 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Ringkasan Total & Pembayaran -->
        <div class="flex justify-between items-start mb-8">
            <div class="w-1/2 text-xs text-gray-500">
                <?php if (!empty($booking['notes'])): ?>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="font-bold text-gray-700 mb-0.5">Catatan Tambahan:</p>
                        <p class="italic text-gray-600"><?= nl2br(htmlspecialchars($booking['notes'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="w-1/2 md:w-5/12 text-xs space-y-1.5">
                <div class="flex justify-between py-1 text-gray-600">
                    <span>Subtotal Sewa:</span>
                    <span class="font-semibold">Rp <?= number_format($sewaMobilTotal + $sewaDriverTotal, 0, ',', '.') ?></span>
                </div>

                <?php if ($discount > 0): ?>
                    <div class="flex justify-between py-1 text-rose-600">
                        <span>Diskon Potongan:</span>
                        <span class="font-semibold">- Rp <?= number_format($discount, 0, ',', '.') ?></span>
                    </div>
                <?php endif; ?>

                <div class="flex justify-between py-2 border-t border-gray-200 font-extrabold text-sm text-gray-900">
                    <span>TOTAL SEWA:</span>
                    <span>Rp <?= number_format($grandTotal, 0, ',', '.') ?></span>
                </div>

                <div class="flex justify-between py-1 text-emerald-700">
                    <span>Uang Muka / Deposit (DP):</span>
                    <span class="font-semibold">Rp <?= number_format($deposit, 0, ',', '.') ?></span>
                </div>

                <div class="flex justify-between py-2 border-t-2 border-[#B30427] font-black text-base text-[#B30427]">
                    <span>SISA PELUNASAN:</span>
                    <span>Rp <?= number_format($sisaBayar, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Footer / TTD -->
        <div class="flex justify-between items-end mt-12 pt-6 border-t border-gray-100">
            <div class="text-[11px] text-gray-500 w-1/2 space-y-1">
                <p class="font-bold text-gray-700 mb-1">Syarat & Ketentuan Tambahan:</p>
                <ol class="list-decimal pl-4 space-y-0.5">
                    <li>Penyewa wajib menunjukkan KTP & SIM asli saat serah terima unit.</li>
                    <li>Keterlambatan pengembalian unit dikenakan denda sesuai dengan tarif harian.</li>
                    <li>Segala bentuk kerusakan/kehilangan selama masa sewa menjadi tanggung jawab penyewa.</li>
                </ol>
            </div>
            <div class="text-center w-44">
                <p class="text-xs text-gray-600 mb-12">Hormat Kami,</p>
                <p class="font-bold text-xs text-gray-800 border-t border-gray-300 pt-1"><?= htmlspecialchars(APP_NAME ?? 'ARKAYASA RENT CAR') ?></p>
            </div>
        </div>

    </div>
</body>

</html>