<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?= htmlspecialchars($booking['booking_code']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        /* Mencegah background color hilang saat diprint */
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            @page { margin: 0; size: auto; }
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

    <!-- Kertas A4 -->
    <div class="max-w-4xl mx-auto bg-white min-h-[1050px] shadow-2xl print:shadow-none p-12 relative border border-gray-200">
        
        <!-- Header Invoice -->
        <div class="flex justify-between items-start border-b-2 border-gray-100 pb-8 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-[#B30427] tracking-tight uppercase"><?= htmlspecialchars(APP_NAME) ?></h1>
                <p class="text-sm text-gray-500 mt-1">Layanan Sewa Mobil Premium & Eksklusif</p>
                <div class="mt-4 text-sm text-gray-600">
                    <p>Jl. Merdeka No. 123, Kota Bandung, Jawa Barat</p>
                    <p>Phone: 0812-3456-7890 | Email: info@arkayasa.com</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-4xl font-black text-gray-200 uppercase tracking-widest">INVOICE</h2>
                <div class="mt-4">
                    <p class="text-sm font-semibold text-gray-800">Nomor Invoice:</p>
                    <p class="text-lg font-mono font-bold text-[#B30427]"><?= htmlspecialchars($booking['booking_code']) ?></p>
                </div>
                <div class="mt-2">
                    <p class="text-xs text-gray-500">Tanggal Terbit:</p>
                    <p class="text-sm font-semibold text-gray-800"><?= date('d F Y') ?></p>
                </div>
            </div>
        </div>

        <!-- Info Pelanggan & Status -->
        <div class="flex justify-between items-start mb-8">
            <div class="w-1/2">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ditagihkan Kepada:</h3>
                <p class="text-lg font-bold text-gray-800"><?= htmlspecialchars($booking['customer_name']) ?></p>
                <p class="text-sm text-gray-600 mt-1">NIK: <?= htmlspecialchars($booking['customer_nik']) ?></p>
                <p class="text-sm text-gray-600"><?= htmlspecialchars($booking['customer_phone']) ?> | <?= htmlspecialchars($booking['customer_email']) ?></p>
                <p class="text-sm text-gray-600 mt-1"><?= nl2br(htmlspecialchars($booking['customer_address'])) ?></p>
            </div>
            <div class="w-1/3 p-4 rounded-xl <?= in_array($booking['status'], ['Approve', 'Dipinjam', 'Selesai', 'Dikembalikan']) ? 'bg-emerald-50 border border-emerald-100' : 'bg-rose-50 border border-rose-100' ?>">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Status Pembayaran:</h3>
                <?php if (in_array($booking['status'], ['Approve', 'Dipinjam', 'Selesai', 'Dikembalikan'])): ?>
                    <p class="text-xl font-black text-emerald-600 uppercase">LUNAS</p>
                <?php else: ?>
                    <p class="text-xl font-black text-rose-600 uppercase">BELUM LUNAS</p>
                <?php endif; ?>
                <p class="text-xs text-gray-500 mt-2 font-medium">Status Sewa: <?= htmlspecialchars($booking['status']) ?></p>
            </div>
        </div>

        <!-- Tabel Rincian -->
        <table class="w-full text-left border-collapse mb-8">
            <thead>
                <tr class="bg-gray-50 text-gray-700 text-sm uppercase tracking-wider border-y-2 border-gray-200">
                    <th class="py-3 px-4 font-bold w-1/2">Deskripsi Layanan</th>
                    <th class="py-3 px-4 font-bold text-center">Durasi</th>
                    <th class="py-3 px-4 font-bold text-right">Harga Satuan</th>
                    <th class="py-3 px-4 font-bold text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="py-4 px-4">
                        <p class="font-bold text-gray-800 text-base">Sewa Kendaraan: <?= htmlspecialchars($booking['car_brand']) ?> <?= htmlspecialchars($booking['car_name']) ?></p>
                        <p class="text-sm text-gray-500 mt-1">Plat Nomor: <span class="font-mono font-semibold"><?= htmlspecialchars($booking['car_plate']) ?></span></p>
                        <p class="text-xs text-gray-500 mt-1">
                            Tanggal Sewa: <?= date('d M Y', strtotime($booking['start_date'])) ?> s/d <?= date('d M Y', strtotime($booking['end_date'])) ?>
                        </p>
                    </td>
                    <td class="py-4 px-4 text-center text-gray-700 font-semibold">
                        <?= htmlspecialchars($booking['total_days']) ?> Hari
                    </td>
                    <td class="py-4 px-4 text-right text-gray-700">
                        Rp <?= number_format($booking['car_price'], 0, ',', '.') ?>
                    </td>
                    <td class="py-4 px-4 text-right font-bold text-gray-900 text-lg">
                        Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Ringkasan Total -->
        <div class="flex justify-end mb-12">
            <div class="w-1/2 md:w-1/3">
                <div class="flex justify-between py-2 text-sm text-gray-600">
                    <span>Subtotal:</span>
                    <span>Rp <?= number_format($booking['total_price'], 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between py-2 text-sm text-gray-600 border-b border-gray-200">
                    <span>Pajak (0%):</span>
                    <span>Rp 0</span>
                </div>
                <div class="flex justify-between py-3 text-lg font-black text-[#B30427]">
                    <span>TOTAL TAGIHAN:</span>
                    <span>Rp <?= number_format($booking['total_price'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Footer / TTD -->
        <div class="flex justify-between items-end mt-16 pt-8 border-t border-gray-100">
            <div class="text-xs text-gray-500 w-1/2">
                <p class="font-bold text-gray-700 mb-1">Catatan Penting:</p>
                <ol class="list-decimal pl-4 space-y-1">
                    <li>Harap membawa KTP dan SIM asli saat serah terima kendaraan.</li>
                    <li>Keterlambatan pengembalian akan dikenakan denda sesuai ketentuan.</li>
                    <li>Kerusakan kendaraan selama masa sewa menjadi tanggung jawab penyewa.</li>
                </ol>
            </div>
            <div class="text-center w-48">
                <p class="text-sm text-gray-600 mb-16">Hormat Kami,</p>
                <p class="font-bold text-gray-800 border-t border-gray-300 pt-2"><?= htmlspecialchars(APP_NAME) ?></p>
            </div>
        </div>

    </div>
</body>
</html>
