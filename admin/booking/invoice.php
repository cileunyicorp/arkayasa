<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?= htmlspecialchars($booking['booking_code'] ?? '') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                margin: 0;
                size: A4 portrait;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 py-6 print:py-0 print:bg-white text-slate-800">

    <!-- Tombol Kontrol Cetak (Tidak ikut terprint) -->
    <div class="max-w-4xl mx-auto mb-4 print:hidden flex justify-between items-center px-4 no-print">
        <a href="javascript:window.close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-medium transition text-sm">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
        <button onclick="window.print()" class="px-5 py-2 bg-[#800000] hover:bg-[#600000] text-white rounded-xl shadow-lg transition font-bold text-sm flex items-center gap-2">
            <i class="fa-solid fa-print"></i> Cetak Invoice
        </button>
    </div>

    <!-- Container Kertas Invoice -->
    <div class="max-w-4xl mx-auto bg-white min-h-[1120px] shadow-2xl print:shadow-none relative overflow-hidden flex flex-col justify-between border border-gray-200 print:border-none">

        <div>
            <!-- Header Section -->
            <div class="grid grid-cols-12 items-stretch">
                <!-- Header Kiri: Logo & Info Perusahaan -->
                <div class="col-span-6 p-8 pr-4">
                    <div class="flex items-center gap-3">
                        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" class="h-16 w-auto object-contain" onerror="this.src='https://ui-avatars.com/api/?name=Arkayasa&background=800000&color=fff'">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 tracking-tight leading-none uppercase">CAR RENTAL</h2>
                            <h2 class="text-xl font-black text-slate-900 tracking-tight leading-none uppercase">POINT BANDUNG</h2>
                        </div>
                    </div>

                    <div class="mt-4 text-[11px] text-slate-600 space-y-1 font-medium">
                        <p class="flex items-center gap-2"><i class="fa-solid fa-phone text-slate-800 w-3"></i> 0857-2222-0442</p>
                        <p class="flex items-center gap-2"><i class="fa-solid fa-globe text-slate-800 w-3"></i> www.arkayasa.com</p>
                        <p class="flex items-center gap-2"><i class="fa-solid fa-envelope text-slate-800 w-3"></i> prayugaarkatamayasa@gmail.com</p>
                        <p class="flex items-center gap-2"><i class="fa-solid fa-location-dot text-slate-800 w-3"></i> Jl. Embah Jaksa No A66 K-17, Cipadung - Cibiru Kota Bandung</p>
                    </div>
                </div>

                <!-- Header Kanan: Banner Merah Marun INVOICE -->
                <div class="col-span-6 bg-[#800000] text-white p-8 flex flex-col justify-center items-end relative overflow-hidden">
                    <!-- Icon Tangan Kunci (Hiasan) -->
                    <div class="absolute top-2 right-4 text-white/20 text-5xl pointer-events-none">
                        <i class="fa-solid fa-key"></i>
                    </div>

                    <h1 class="text-5xl font-black tracking-widest text-white uppercase">INVOICE</h1>
                    <p class="text-sm font-semibold tracking-wide mt-1 text-white/90">No. <?= htmlspecialchars($booking['booking_code'] ?? '-') ?></p>
                </div>
            </div>

            <!-- Bar Transaksi Dark Slate -->
            <div class="grid grid-cols-12 bg-[#1e293b] text-white text-xs font-semibold py-2.5 px-8 tracking-wider">
                <div class="col-span-4">Invoice No : <?= htmlspecialchars(substr($booking['booking_code'] ?? '-', -6)) ?></div>
                <div class="col-span-4 text-center">Invoice Date : <?= date('d/m/Y', strtotime($booking['created_at'] ?? 'now')) ?></div>
                <div class="col-span-4 text-right">Due Date : <?= date('d/m/Y', strtotime($booking['end_date'] ?? 'now')) ?></div>
            </div>

            <!-- Detail Tagihan & Pembayaran -->
            <div class="p-8 grid grid-cols-12 gap-6">
                <!-- Info Pelanggan -->
                <div class="col-span-7 space-y-1.5">
                    <h3 class="text-sm font-bold text-slate-900 uppercase">Invoice to:</h3>
                    <h2 class="text-2xl font-black text-[#800000] leading-tight"><?= htmlspecialchars($booking['customer_name'] ?? 'Pelanggan') ?></h2>

                    <div class="text-xs text-slate-700 space-y-1 font-semibold pt-1">
                        <p><i class="fa-solid fa-phone mr-1.5 text-slate-500"></i> (<?= htmlspecialchars($booking['customer_phone'] ?? '-') ?>)</p>
                        <p><i class="fa-solid fa-calendar-days mr-1.5 text-slate-500"></i> Tanggal sewa: <?= date('d F Y', strtotime($booking['start_date'] ?? 'now')) ?> (<?= (int)($booking['total_days'] ?? 1) ?> hari)</p>
                        <p><i class="fa-solid fa-location-dot mr-1.5 text-slate-500"></i> Alamat: <?= htmlspecialchars($booking['customer_address'] ?? 'Kota Bandung') ?></p>
                    </div>
                </div>

                <!-- Informasi Rekening Pembayaran -->
                <div class="col-span-5 text-xs text-slate-800 font-medium pl-4 border-l border-slate-100">
                    <h3 class="font-black text-slate-900 uppercase mb-2 tracking-wider">PAYMENT INFO</h3>
                    <div class="grid grid-cols-12 gap-y-1">
                        <span class="col-span-5 font-bold text-slate-600">Bank Name</span>
                        <span class="col-span-7 font-extrabold">: BCA</span>

                        <span class="col-span-5 font-bold text-slate-600">Account Name</span>
                        <span class="col-span-7 font-extrabold">: PT Prayuga Arkatama Yasa</span>

                        <span class="col-span-5 font-bold text-slate-600">Account ID</span>
                        <span class="col-span-7 font-extrabold">: 283 44 555 60</span>
                    </div>
                </div>
            </div>

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
            $subtotalBiaya   = $sewaMobilTotal + $sewaDriverTotal;

            $isLunas = ($sisaBayar <= 0 && $grandTotal > 0);
            ?>

            <!-- Tabel Rincian Layanan -->
            <div class="px-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs uppercase font-extrabold text-white">
                            <th class="bg-[#800000] py-3 px-5 rounded-l-lg w-7/12">Item Description</th>
                            <th class="bg-[#1e293b] py-3 px-4 text-center w-2/12">Quantity</th>
                            <th class="bg-[#1e293b] py-3 px-5 text-right rounded-r-lg w-3/12">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-bold text-slate-800">
                        <!-- Baris Mobil -->
                        <tr>
                            <td class="py-3.5 px-5">
                                <?= htmlspecialchars($booking['car_name'] ?? '') ?> (<?= htmlspecialchars($booking['car_plate'] ?? '') ?>)
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                1 Unit/<?= $totalDays ?>Hari
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                Rp. <?= number_format($sewaMobilTotal, 0, ',', '.') ?>
                            </td>
                        </tr>

                        <!-- Baris Driver (jika ada) -->
                        <?php if (!empty($booking['driver_id']) || $driverFeeDay > 0): ?>
                            <tr>
                                <td class="py-3.5 px-5">
                                    Layanan Sopir / Driver: <?= htmlspecialchars($booking['driver_name'] ?? 'Driver') ?>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <?= $totalDays ?> Hari
                                </td>
                                <td class="py-3.5 px-5 text-right">
                                    Rp. <?= number_format($sewaDriverTotal, 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <!-- Row Placeholder Tambahan (Mengikuti gaya invoice) -->
                        <tr>
                            <td class="py-2.5 px-5 text-slate-600 font-medium">Fee Drop & Pickup</td>
                            <td class="py-2.5 px-4 text-center text-slate-400">-</td>
                            <td class="py-2.5 px-5 text-right text-slate-400">-</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-5 text-slate-600 font-medium">Overtime</td>
                            <td class="py-2.5 px-4 text-center text-slate-400">-</td>
                            <td class="py-2.5 px-5 text-right text-slate-400">-</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-5 text-slate-600 font-medium">Free Cuci</td>
                            <td class="py-2.5 px-4 text-center text-slate-400">-</td>
                            <td class="py-2.5 px-5 text-right text-slate-400">-</td>
                        </tr>
                    </tbody>
                </table>
                <div class="border-b-2 border-slate-800 my-2"></div>
            </div>

            <!-- Perhitungan Keuangan & Status Lunas/Transfer -->
            <div class="px-8 mt-4 grid grid-cols-12 gap-6 items-start">

                <!-- Box Keterangan Pembayaran Kiri -->
                <div class="col-span-6 space-y-3">
                    <div class="w-full">
                        <div class="bg-[#1e293b] text-white text-xs font-bold py-2 px-4 rounded-t-lg">
                            Keterangan Pembayaran :
                        </div>
                        <div class="border-2 border-slate-200 p-3 rounded-b-lg flex items-center justify-around text-xs font-bold">
                            <span class="flex items-center gap-1.5 text-slate-700">
                                <i class="fa-solid fa-check text-emerald-600"></i> Transfer
                            </span>
                            <?php if ($isLunas): ?>
                                <span class="flex items-center gap-1.5 text-emerald-600 font-black">
                                    <i class="fa-solid fa-check"></i> Lunas
                                </span>
                            <?php else: ?>
                                <span class="flex items-center gap-1.5 text-rose-600 font-black">
                                    <i class="fa-solid fa-xmark"></i> Belum Lunas
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h3 class="text-xl font-black text-[#800000] italic pt-2">Thank you for order.</h3>
                </div>

                <!-- Rincian Nominal Kanan -->
                <div class="col-span-6 text-xs font-bold space-y-2">
                    <div class="flex justify-between items-center px-4">
                        <span class="text-slate-700">Subtotal</span>
                        <span class="text-slate-900">Rp. <?= number_format($subtotalBiaya, 0, ',', '.') ?></span>
                    </div>

                    <div class="flex justify-between items-center px-4">
                        <span class="text-slate-700">Down payment</span>
                        <span class="text-slate-900"><?= $deposit > 0 ? 'Rp. ' . number_format($deposit, 0, ',', '.') : '-' ?></span>
                    </div>

                    <div class="flex justify-between items-center px-4">
                        <span class="text-slate-700">Discount (-)</span>
                        <span class="text-slate-900"><?= $discount > 0 ? 'Rp. ' . number_format($discount, 0, ',', '.') : '-' ?></span>
                    </div>

                    <div class="flex justify-between items-center px-4">
                        <span class="text-slate-700">Tax (-)</span>
                        <span class="text-slate-900">-</span>
                    </div>

                    <!-- Total Block Merah -->
                    <div class="mt-4 flex items-stretch rounded-lg overflow-hidden shadow-md">
                        <div class="bg-[#1e293b] text-white py-3 px-6 text-sm font-black uppercase flex items-center justify-center w-1/3">
                            Total
                        </div>
                        <div class="bg-[#800000] text-white py-3 px-6 text-xl font-black text-right flex-1">
                            Rp. <?= number_format($grandTotal, 0, ',', '.') ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer Section (Terms & Conditions & TTD) -->
        <div class="mt-12">
            <div class="grid grid-cols-12 items-end">

                <!-- Box Syarat Kiri (Dark Slate) -->
                <div class="col-span-7 bg-[#1e293b] text-white p-6 text-[11px] font-medium leading-relaxed">
                    <h4 class="font-extrabold text-xs uppercase tracking-wider mb-1 text-white">TERMS & CONDITIONS</h4>
                    <p class="text-slate-300">Please send payment within 1 day of receiving this invoice.</p>
                    <p class="text-slate-300">And please check the car body and fuel.</p>
                </div>

                <!-- Area Tanda Tangan Kanan -->
                <!-- Area Tanda Tangan Kanan -->
                <div class="col-span-5 p-6 bg-white flex flex-col items-center justify-center text-center">

                    <!-- KONTENER STAMPEL & TTD: Menggunakan Gambar Asli -->
                    <div class="relative w-40 h-20 mb-1">
                        <!-- Gambar Tanda Tangan (di bawah) -->
                        <img src="<?= base_url('assets/images/ttd.png') ?>" class="absolute inset-0 w-full h-full object-contain" alt="Tanda Tangan">

                        <!-- Gambar Cap Stempel (di atas) -->
                        <img src="<?= base_url('assets/images/cap.png') ?>" class="absolute inset-0 w-full h-full object-contain" alt="Cap Perusahaan">
                    </div>

                    <h4 class="text-sm font-extrabold text-[#800000] tracking-tight mt-1">Disha Arkayasa</h4>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">FINANCE</p>
                </div>


            </div>
        </div>

    </div>

</body>

</html>