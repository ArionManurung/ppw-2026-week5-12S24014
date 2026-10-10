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
            --del-primary: #4C1D95;
            --del-primary-hover: #3B0764;
            --del-primary-light: #F3E8FF;
            --del-accent: #7C3AED;
            --del-surface: #FFFFFF;
            --del-bg: #F8FAFC;
            --del-text-main: #0F172A;
            --del-text-muted: #64748B;
            --del-border: #E2E8F0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--del-bg);
            color: var(--del-text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            letter-spacing: -0.01em;
        }

        /* Modern Gradient Navbar */
        .navbar-del-modern {
            background: linear-gradient(135deg, #3B0764 0%, #4C1D95 50%, #5B21B6 100%);
            box-shadow: 0 4px 20px -2px rgba(76, 29, 149, 0.25);
            padding: 0.85rem 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -0.02em;
            color: #FFFFFF !important;
            transition: transform 0.2s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.02);
        }

        .brand-icon-box {
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .nav-link-modern {
            color: rgba(255, 255, 255, 0.82) !important;
            font-weight: 500;
            font-size: 0.925rem;
            padding: 0.55rem 1.1rem !important;
            border-radius: 9999px;
            transition: all 0.2s ease;
            margin: 0 0.15rem;
        }

        .nav-link-modern:hover {
            color: #FFFFFF !important;
            background: rgba(255, 255, 255, 0.12);
        }

        .nav-link-modern.active {
            color: #FFFFFF !important;
            background: rgba(255, 255, 255, 0.22);
            font-weight: 700;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.25);
        }

        /* Buttons Styling */
        .btn-del {
            background: linear-gradient(135deg, #5B21B6 0%, #4C1D95 100%);
            color: #FFFFFF;
            border: none;
            font-weight: 600;
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(76, 29, 149, 0.2);
        }

        .btn-del:hover {
            background: linear-gradient(135deg, #4C1D95 0%, #3B0764 100%);
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(76, 29, 149, 0.3);
        }

        .btn-del-outline {
            background: transparent;
            color: var(--del-primary);
            border: 1.5px solid var(--del-primary);
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .btn-del-outline:hover {
            background: var(--del-primary);
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        /* Cards & Surfaces */
        .card-modern {
            background: #FFFFFF;
            border: 1px solid var(--del-border);
            border-radius: 16px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
            transition: box-shadow 0.25s ease;
        }

        .card-modern:hover {
            box-shadow: 0 10px 30px -4px rgba(15, 23, 42, 0.07), 0 4px 10px -2px rgba(15, 23, 42, 0.03);
        }

        .card-del-header {
            background: linear-gradient(135deg, #3B0764 0%, #4C1D95 100%);
            color: #FFFFFF;
            border-top-left-radius: 15px !important;
            border-top-right-radius: 15px !important;
            border-bottom: none;
            padding: 1rem 1.5rem;
        }

        /* Table Styling */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-modern thead th {
            background-color: #F8FAFC;
            color: #475569;
            font-size: 0.785rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.9rem 1rem;
            border-top: none;
            border-bottom: 1px solid var(--del-border);
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
            border-radius: 10px;
            border: 1.5px solid #CBD5E1;
            padding: 0.65rem 0.95rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #7C3AED;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.12);
        }

        /* Text Accents */
        .text-del-purple {
            color: #4C1D95 !important;
        }

        .bg-del-soft {
            background-color: #F5F3FF !important;
            color: #5B21B6 !important;
        }

        /* Footer */
        .footer-modern {
            background: #FFFFFF;
            border-top: 1px solid var(--del-border);
            padding: 1.5rem 0;
            color: var(--del-text-muted);
            font-size: 0.875rem;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <!-- Modern Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-del-modern mb-4">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('buku.index') }}">
                <div class="brand-icon-box">
                    <i class="bi bi-book-half fs-5"></i>
                </div>
                <span>SIPUS-Del</span>
            </a>
            
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-2"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link nav-link-modern {{ request()->routeIs('buku.*') ? 'active' : '' }}" href="{{ route('buku.index') }}">
                            <i class="bi bi-journals me-1"></i> Katalog Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-modern {{ request()->routeIs('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">
                            <i class="bi bi-tags me-1"></i> Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-modern {{ request()->routeIs('pengunjung.*') ? 'active' : '' }}" href="{{ route('pengunjung.index') }}">
                            <i class="bi bi-person-lines-fill me-1"></i> Log Pengunjung
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a href="{{ route('pengunjung.create') }}" class="btn btn-sm btn-light fw-bold text-del-purple rounded-pill px-3 py-2 shadow-sm">
                            <i class="bi bi-pencil-square me-1"></i> Buku Tamu
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
