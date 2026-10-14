<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SIPUS-Del' }} — Perpustakaan Institut Teknologi Del</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --app-primary: #1E293B;        /* Deep Charcoal / Slate */
            --app-primary-hover: #0F172A;
            --app-accent: #2563EB;         /* Clean Royal Blue */
            --app-accent-hover: #1D4ED8;
            --app-accent-soft: #EFF6FF;
            --app-surface: #FFFFFF;
            --app-bg: #F8FAFC;            /* Clean light grey-blue */
            --app-text-main: #0F172A;
            --app-text-muted: #64748B;
            --app-border: #E2E8F0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--app-bg);
            color: var(--app-text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            letter-spacing: -0.01em;
        }

        /* Clean Modern Navbar */
        .navbar-app {
            background-color: #0F172A;
            border-bottom: 1px solid #1E293B;
            padding: 0.85rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.02em;
            color: #FFFFFF !important;
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .brand-icon-box {
            width: 36px;
            height: 36px;
            background: #2563EB;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
        }

        .nav-link-app {
            color: #94A3B8 !important;
            font-weight: 500;
            font-size: 0.925rem;
            padding: 0.55rem 1rem !important;
            border-radius: 8px;
            transition: all 0.2s ease;
            margin: 0 0.15rem;
        }

        .nav-link-app:hover {
            color: #FFFFFF !important;
            background: rgba(255, 255, 255, 0.08);
        }

        .nav-link-app.active {
            color: #FFFFFF !important;
            background: #1E293B;
            font-weight: 600;
        }

        /* Buttons Styling */
        .btn-app {
            background-color: #2563EB;
            color: #FFFFFF;
            border: none;
            font-weight: 600;
            padding: 0.6rem 1.25rem;
            border-radius: 9px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
        }

        .btn-app:hover {
            background-color: #1D4ED8;
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-del {
            background-color: #2563EB !important;
            color: #FFFFFF !important;
            border: none !important;
            font-weight: 600 !important;
            border-radius: 9px !important;
        }

        .btn-del:hover {
            background-color: #1D4ED8 !important;
        }

        .btn-app-dark {
            background-color: #0F172A;
            color: #FFFFFF;
            border: none;
            font-weight: 600;
            border-radius: 9px;
        }

        .btn-app-dark:hover {
            background-color: #1E293B;
            color: #FFFFFF;
        }

        /* Cards & Containers */
        .card-modern {
            background: #FFFFFF;
            border: 1px solid var(--app-border);
            border-radius: 14px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .card-header-clean {
            background: #FFFFFF;
            border-bottom: 1px solid var(--app-border);
            padding: 1.1rem 1.5rem;
            border-top-left-radius: 14px !important;
            border-top-right-radius: 14px !important;
        }

        .card-del-header {
            background: #0F172A !important;
            color: #FFFFFF !important;
            border-top-left-radius: 13px !important;
            border-top-right-radius: 13px !important;
            border-bottom: none !important;
            padding: 1rem 1.5rem !important;
        }

        /* Table Styling */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-modern thead th {
            background-color: #F8FAFC;
            color: #64748B;
            font-size: 0.775rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.9rem 1rem;
            border-top: none;
            border-bottom: 1px solid var(--app-border);
        }

        .table-modern tbody tr {
            transition: background-color 0.15s ease;
        }

        .table-modern tbody tr:hover {
            background-color: #F8FAFC !important;
        }

        .table-modern tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #F1F5F9;
        }

        /* Form Inputs */
        .form-control, .form-select {
            border-radius: 9px;
            border: 1.5px solid #CBD5E1;
            padding: 0.65rem 0.95rem;
            font-size: 0.935rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        /* Badge Pills */
        .badge-soft-blue {
            background-color: #EFF6FF;
            color: #1D4ED8;
            border: 1px solid #DBEAFE;
        }

        .badge-soft-slate {
            background-color: #F1F5F9;
            color: #334155;
            border: 1px solid #E2E8F0;
        }

        .badge-soft-green {
            background-color: #ECFDF5;
            color: #047857;
            border: 1px solid #A7F3D0;
        }

        .badge-soft-red {
            background-color: #FFF1F2;
            color: #BE123C;
            border: 1px solid #FECDD3;
        }

        .text-del-purple {
            color: #0F172A !important;
        }

        .bg-del-soft {
            background-color: #EFF6FF !important;
            color: #1D4ED8 !important;
        }

        /* Footer */
        .footer-modern {
            background: #FFFFFF;
            border-top: 1px solid var(--app-border);
            padding: 1.5rem 0;
            color: var(--app-text-muted);
            font-size: 0.875rem;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <!-- Clean Dark Slate Navbar -->
    <nav class="navbar navbar-expand-lg navbar-app mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('buku.index') }}">
                <div class="brand-icon-box">
                    <i class="bi bi-book-half fs-6"></i>
                </div>
                <span>SIPUS-Del</span>
            </a>
            
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-2 text-white"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link nav-link-app {{ request()->routeIs('buku.*') ? 'active' : '' }}" href="{{ route('buku.index') }}">
                            <i class="bi bi-journals me-1"></i> Katalog Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-app {{ request()->routeIs('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">
                            <i class="bi bi-tags me-1"></i> Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-app {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
                            <i class="bi bi-arrow-repeat me-1"></i> Sirkulasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-app {{ request()->routeIs('pengunjung.*') ? 'active' : '' }}" href="{{ route('pengunjung.index') }}">
                            <i class="bi bi-person-lines-fill me-1"></i> Log Pengunjung
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a href="{{ route('pengunjung.create') }}" class="btn btn-sm btn-app d-inline-flex align-items-center gap-1">
                            <i class="bi bi-pencil-square"></i>
                            <span>Buku Tamu</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container pb-5">
        <!-- Flash Session Notification -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center gap-3 p-3 mb-4 bg-success-subtle text-success-emphasis" role="alert">
                <div class="p-2 bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="bi bi-check-lg fw-bold"></i>
                </div>
                <div class="fw-medium">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center gap-3 p-3 mb-4 bg-danger-subtle text-danger-emphasis" role="alert">
                <div class="p-2 bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="bi bi-exclamation-triangle fw-bold"></i>
                </div>
                <div class="fw-medium">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Slot Main Content -->
        {{ $slot }}
    </div>

    <!-- Modern Minimalist Footer -->
    <footer class="footer-modern">
        <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <div class="fw-semibold text-dark">
                SIPUS-Del <span class="text-muted fw-normal">&mdash; Perpustakaan Institut Teknologi Del</span>
            </div>
            <div class="small text-muted">
                &copy; {{ date('Y') }} Institut Teknologi Del. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
