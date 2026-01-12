<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="text-center">
            <div class="mb-8">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-8xl mb-4"></i>
            </div>
            <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Halaman Tidak Ditemukan</h2>
            <p class="text-gray-600 mb-8">Maaf, halaman yang Anda cari tidak dapat ditemukan.</p>
            <a href="<?= base_url('/') ?>" class="inline-block px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-home mr-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>
</body>
</html>
