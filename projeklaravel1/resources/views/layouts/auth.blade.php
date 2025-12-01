<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Berita')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header Simple -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <a href="/" class="text-2xl font-bold">
                    <span class="text-red-600">PORTAL</span><span class="bg-red-600 text-white px-2 py-1 rounded">BERITA</span>
                </a>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-700 hover:text-red-600">Beranda</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12 py-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2024 Portal Berita. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
