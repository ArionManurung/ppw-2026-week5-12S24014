<x-layout>
    <x-slot:title>Sirkulasi Peminjaman Buku</x-slot:title>

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-del-soft text-del-purple fw-semibold small mb-2">
                <i class="bi bi-arrow-repeat"></i> Sirkulasi & Transaksi
            </div>
            <h2 class="fw-bold text-dark mb-1">Sirkulasi Peminjaman Buku</h2>
            <p class="text-muted small mb-0">Pantau transaksi peminjaman, pengembalian, status jatuh tempo, serta histori pemustaka secara atomik.</p>
        </div>
        <div>
            <a href="{{ route('peminjaman.create') }}" class="btn btn-app shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                <span>Catat Peminjaman Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card card-modern p-3 mb-4">
        <form method="GET" action="{{ route('peminjaman.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0" 
                           placeholder="Cari kode pinjam, NIM, nama peminjam, atau judul buku..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Telah Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-app flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-light border" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="card card-modern overflow-hidden">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 5%;">No</th>
                        <th style="width: 15%;">Kode Pinjam</th>
                        <th style="width: 25%;">Judul Buku</th>
                        <th style="width: 20%;">Peminjam</th>
                        <th style="width: 12%;">Tgl Pinjam</th>
                        <th style="width: 12%;">Jatuh Tempo</th>
                        <th class="text-center" style="width: 11%;">Status</th>
                        <th class="text-end pe-4" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $index => $item)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $peminjamans->firstItem() + $index }}</td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                    {{ $item->kode_pinjam }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('buku.show', $item->buku) }}" class="fw-bold text-decoration-none text-dark d-block">
                                    {{ $item->buku->judul }}
                                </a>
                                <span class="small text-muted font-monospace">{{ $item->buku->isbn }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->nama_peminjam }}</div>
                                <span class="small text-muted font-monospace">{{ $item->nim_peminjam }}</span>
                            </td>
                            <td>
                                <span class="small text-secondary">{{ $item->tanggal_pinjam->format('d M Y') }}</span>
                            </td>
                            <td>
                                <span class="small {{ $item->status === 'dipinjam' && $item->tanggal_jatuh_tempo->isPast() ? 'text-danger fw-bold' : 'text-secondary' }}">
                                    {{ $item->tanggal_jatuh_tempo->format('d M Y') }}
                                    @if($item->status === 'dipinjam' && $item->tanggal_jatuh_tempo->isPast())
                                        <i class="bi bi-exclamation-circle-fill text-danger ms-1" title="Terlambat!"></i>
                                    @endif
                                </span>
                            </td>
                            <td class="text-center">
                                @if($item->status === 'dipinjam')
                                    <span class="badge badge-soft-blue rounded-pill px-3 py-1 fw-semibold">
                                        <i class="bi bi-clock-history me-1"></i> Dipinjam
                                    </span>
                                @else
                                    <span class="badge badge-soft-green rounded-pill px-3 py-1 fw-semibold">
                                        <i class="bi bi-check2-circle me-1"></i> Dikembalikan
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('peminjaman.show', $item) }}" class="btn btn-sm btn-light border text-info" title="Detail Transaksi">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if($item->status === 'dipinjam')
                                        <form action="{{ route('peminjaman.kembali', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi pengembalian buku {{ $item->buku->judul }} oleh {{ $item->nama_peminjam }}? Stok akan bertambah secara atomik.')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light border text-success fw-semibold" title="Proses Pengembalian">
                                                <i class="bi bi-arrow-return-left"></i> Kembali
                                            </button>
                                        </form>
                                    @endif
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
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Riwayat Peminjaman</h6>
                                    <p class="small text-muted mb-3">Tidak ditemukan catatan peminjaman dengan kriteria filter saat ini.</p>
                                    <a href="{{ route('peminjaman.create') }}" class="btn btn-sm btn-app">
                                        <i class="bi bi-plus-lg me-1"></i> Catat Peminjaman Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($peminjamans->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $peminjamans->links() }}
            </div>
        @endif
    </div>
</x-layout>
