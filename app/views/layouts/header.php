<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <!-- Responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SEO -->
    <title><?= isset($title) ? escape($title) : 'Arkayasa - Rental Mobil Terpercaya' ?></title>
    <meta name="description"
        content="<?= isset($meta_description)
                        ? escape($meta_description)
                        : 'Arkayasa menyediakan layanan rental mobil terpercaya dengan armada berkualitas, harga kompetitif, dan pelayanan terbaik.' ?>">
    <meta name="keywords"
        content="<?= isset($meta_keywords)
                        ? escape($meta_keywords)
                        : 'rental mobil, sewa mobil, rental mobil Indonesia, sewa mobil terpercaya, Arkayasa' ?>">
    <meta name="robots" content="index, follow">
    <!-- Canonical -->
    <?php if (isset($canonical_url)): ?>
        <link rel="canonical" href="<?= escape($canonical_url) ?>">
    <?php endif; ?>
    <!-- Favicon -->
    <link rel="icon"
        type="image/png"
        href="<?= base_url('assets/images/favicon.png') ?>">
    <!-- Tailwind CSS -->
    <link rel="stylesheet"
        href="<?= base_url('assets/css/output.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Custom Style -->
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <?php if (!empty($extra_head)): ?>
        <?= $extra_head ?>
    <?php endif; ?>
    <script>
        if (localStorage.getItem('arkayasa-theme') === 'dark' || (!('arkayasa-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="bg-white text-slate-800 antialiased">