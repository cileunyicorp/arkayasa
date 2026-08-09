<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#B30427">
    <meta name="color-scheme" content="light dark">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Arkayasa Rent Car Administration Panel">
    <meta name="author" content="Arkayasa">
    <title>
        <?= isset($title) && $title !== ''
            ? htmlspecialchars($title) . ' | Arkayasa Rent Car'
            : 'Admin Panel | Arkayasa Rent Car'
        ?>
    </title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.png') ?>">
    <script>
        (() => {
            const savedTheme =
                localStorage.getItem('color-theme');
            const prefersDark =
                window.matchMedia(
                    '(prefers-color-scheme: dark)'
                ).matches;
            if (
                savedTheme === 'dark' ||
                (!savedTheme && prefersDark)
            ) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?= base_url('assets/css/output.css') ?>">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
</head>

<body class="bg-slate-50 dark:bg-neutral-900 text-slate-800 dark:text-neutral-100 font-sans antialiased transition-colors duration-300" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">