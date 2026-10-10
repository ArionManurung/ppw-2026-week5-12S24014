<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SIPUS-Del' }} — Perpustakaan Institut Teknologi Del</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .bg-del-purple { background-color: #4C1D95 !important; }
        .text-del-purple { color: #4C1D95 !important; }
        .btn-del { background-color: #4C1D95; color: #ffffff; border: none; }
        .btn-del:hover { background-color: #3B0764; color: #ffffff; }
        .card-del-header { background-color: #4C1D95; color: white; }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar Shell -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-del-purple shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('buku.index') }}">
                <i class="bi bi-book-half fs-4"></i> SIPUS-Del
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('buku.*') ? 'active fw-bold' : '' }}" href="{{ route('buku.index') }}">
                            <i class="bi bi-journals me-1"></i> Katalog Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kategori.*') ? 'active fw-bold' : '' }}" href="{{ route('kategori.index') }}">
                            <i class="bi bi-tags me-1"></i> Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pengunjung.*') ? 'active fw-bold' : '' }}" href="{{ route('pengunjung.index') }}">
                            <i class="bi bi-person-lines-fill me-1"></i> Log Pengunjung
                        </a>
                    </li>
                </ul>
                <span class="navbar-text text-white-50 small">
                    Institut Teknologi Del | Modul 5 PPW
                </span>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container pb-5">
        <!-- Flash Session Notification -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Slot Main Content -->
        {{ $slot }}
    </div>

    <!-- Footer Shell -->
    <footer class="bg-white border-top py-3 text-center text-muted small mt-auto">
        <div class="container">
            SIPUS-Del &copy; {{ date('Y') }} Institut Teknologi Del — Praktikum Pemrograman & Pengujian Web
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
