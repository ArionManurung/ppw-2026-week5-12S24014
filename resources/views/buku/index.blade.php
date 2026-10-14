<x-layout>
    <x-slot:title>Katalog & Analitik Buku</x-slot:title>

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-del-soft text-del-purple fw-semibold small mb-2">
                <i class="bi bi-speedometer2"></i> Dashboard & Katalog Terpadu
            </div>
            <h2 class="fw-bold text-dark mb-1">Katalog Koleksi Buku & Rekapitulasi</h2>
            <p class="text-muted small mb-0">Kelola katalog inventaris, rekapitulasi data agregat Query Builder, serta sirkulasi peminjaman buku.</p>
        </div>
        <div>
            @auth
                <a href="{{ route('buku.create') }}" class="btn btn-app shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Buku Baru</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock"></i>
                    <span>Login Pustakawan</span>
                </a>
            @endauth
        </div>
    </div>

    <!-- 4 Kartu Statistik Perpustakaan (Fluent Query Builder DB::table) -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Judul Buku -->
        <div class="col-sm-6 col-xl-3">
            <div class="card card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-4 bg-primary-subtle text-primary">
                        <i class="bi bi-journal-bookmark-fill fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-semibold d-block">Total Judul Buku</span>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['total_judul']) }}</h3>
                        <span class="text-muted" style="font-size: 0.75rem;">Terdaftar di sistem</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Total Stok Tersedia -->
        <div class="col-sm-6 col-xl-3">
            <div class="card card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-4 bg-success-subtle text-success">
                        <i class="bi bi-box-seam-fill fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-semibold d-block">Total Stok Tersedia</span>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['total_stok']) }} <small class="fs-6 text-muted fw-normal">eks</small></h3>
                        <span class="text-muted" style="font-size: 0.75rem;">Siap untuk dipinjam</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Total Sedang Dipinjam -->
        <div class="col-sm-6 col-xl-3">
            <div class="card card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-4 bg-warning-subtle text-warning">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-semibold d-block">Sedang Dipinjam</span>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['total_dipinjam']) }} <small class="fs-6 text-muted fw-normal">buku</small></h3>
                        <span class="text-muted" style="font-size: 0.75rem;">Status aktif sirkulasi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Total Buku Telah Dikembalikan -->
        <div class="col-sm-6 col-xl-3">
            <div class="card card-modern p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-4 bg-info-subtle text-info">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-semibold d-block">Telah Dikembalikan</span>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['total_dikembalikan']) }} <small class="fs-6 text-muted fw-normal">transaksi</small></h3>
                        <span class="text-muted" style="font-size: 0.75rem;">Sirkulasi tuntas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Pencarian Dinamis (when() & withQueryString()) -->
    <div class="card card-modern p-3 mb-4">
        <form method="GET" action="{{ route('buku.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0" 
                           placeholder="Cari judul, penulis, penerbit, atau ISBN..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="kategori_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-app flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Cari & Filter
                </button>
                @if(request()->hasAny(['search', 'kategori_id']))
                    <a href="{{ route('buku.index') }}" class="btn btn-light border" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>

        @if(session('last_buku_search'))
            <div class="mt-2 pt-2 border-top small text-muted d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-secondary"></i>
                <span>Pencarian terakhir Anda di sesi ini: <strong>"{{ session('last_buku_search') }}"</strong></span>
            </div>
        @endif
    </div>

    <!-- Main Table Card -->
    <div class="card card-modern overflow-hidden">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 5%;">No</th>
                        <th style="width: 14%;">ISBN</th>
                        <th style="width: 25%;">Judul Buku</th>
                        <th style="width: 15%;">Penulis</th>
                        <th style="width: 14%;">Kategori</th>
                        <th class="text-center" style="width: 8%;">Stok</th>
                        <th class="text-center" style="width: 9%;">Dipinjam</th>
                        <th class="text-end pe-4" style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bukus as $index => $buku)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $bukus->firstItem() + $index }}</td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                    {{ $buku->isbn }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('buku.show', $buku) }}" class="fw-bold text-decoration-none text-dark d-block">
                                    {{ $buku->judul }}
                                </a>
                                <span class="small text-muted">{{ $buku->penerbit }} &bull; {{ $buku->tahun_terbit }}</span>
                            </td>
                            <td>
                                <span class="text-secondary fw-medium">{{ $buku->penulis }}</span>
                            </td>
                            <td>
                                <span class="badge badge-soft-blue rounded-pill px-3 py-1 fw-semibold">
                                    {{ $buku->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($buku->stok > 0)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                        {{ $buku->stok }} eks
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1">
                                        Habis
                                    </span>
                                @endif
                            </td>
                            <!-- withCount('peminjamans') -->
                            <td class="text-center">
                                <span class="badge badge-soft-slate rounded-pill px-2 py-1 fw-semibold" title="Total kali dipinjam">
                                    <i class="bi bi-arrow-repeat me-1 text-primary"></i>{{ $buku->peminjamans_count }}x
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    @if($buku->stok > 0)
                                        <a href="{{ route('peminjaman.create', ['buku_id' => $buku->id]) }}" class="btn btn-sm btn-light border text-primary" title="Pinjam Buku Ini">
                                            <i class="bi bi-journal-plus"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('buku.show', $buku) }}" class="btn btn-sm btn-light border text-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @auth
                                        <a href="{{ route('buku.edit', $buku) }}" class="btn btn-sm btn-light border text-warning" title="Edit Buku">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('buku.destroy', $buku) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus Buku">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <div class="mb-3 d-inline-flex align-items-center justify-content-center p-3 rounded-circle bg-light">
                                        <i class="bi bi-inbox fs-1 text-secondary"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Tidak Ditemukan Data Buku</h6>
                                    <p class="small text-muted mb-3">Tidak ada buku yang sesuai dengan kriteria pencarian atau filter Anda.</p>
                                    <a href="{{ route('buku.create') }}" class="btn btn-sm btn-app">
                                        <i class="bi bi-plus-lg me-1"></i> Tambah Buku Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bukus->hasPages())
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-center">
                    {{ $bukus->links() }}
                </div>
            </div>
        @endif
    </div>
</x-layout>
