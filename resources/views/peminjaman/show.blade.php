<x-layout>
    <x-slot:title>Detail Transaksi: {{ $peminjaman->kode_pinjam }}</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-modern overflow-hidden">
                <div class="card-del-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-receipt fs-5"></i>
                        <h5 class="card-title mb-0 fw-bold">Detail Bukti Transaksi Peminjaman</h5>
                    </div>
                    <span class="badge bg-white text-del-purple font-monospace px-3 py-1 rounded-pill">{{ $peminjaman->kode_pinjam }}</span>
                </div>

                <div class="card-body p-4 p-md-5">
                    <!-- Status Header Banner -->
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-4 mb-4 {{ $peminjaman->status === 'dipinjam' ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-success-subtle text-success border border-success-subtle' }}">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi {{ $peminjaman->status === 'dipinjam' ? 'bi-clock-history' : 'bi-check-circle' }} fs-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Status Sirkulasi: {{ $peminjaman->status === 'dipinjam' ? 'Sedang Dipinjam' : 'Telah Dikembalikan' }}</h6>
                                <span class="small">
                                    {{ $peminjaman->status === 'dipinjam' ? 'Buku belum dikembalikan ke perpustakaan.' : 'Buku telah dikembalikan dan stok sudah dipulihkan ke katalog.' }}
                                </span>
                            </div>
                        </div>
                        <div>
                            @if($peminjaman->status === 'dipinjam')
                                <form action="{{ route('peminjaman.kembali', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success fw-semibold shadow-sm">
                                        <i class="bi bi-arrow-return-left me-1"></i> Kembalikan Sekarang
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-success text-white px-3 py-2 rounded-pill">Selesai</span>
                            @endif
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Kolom Informasi Peminjam -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 h-100">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="bi bi-person me-1 text-primary"></i> Data Pemustaka
                                </h6>
                                <div class="d-flex flex-column gap-2 small">
                                    <div>
                                        <span class="text-muted d-block">Nomor Induk Mahasiswa (NIM):</span>
                                        <span class="fw-bold font-monospace text-dark fs-6">{{ $peminjaman->nim_peminjam }}</span>
                                    </div>
                                    <div>
                                        <span class="text-muted d-block">Nama Lengkap:</span>
                                        <span class="fw-semibold text-dark">{{ $peminjaman->nama_peminjam }}</span>
                                    </div>
                                    <div>
                                        <span class="text-muted d-block">Waktu Transaksi Dibuat:</span>
                                        <span class="text-secondary">{{ $peminjaman->created_at->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Informasi Jadwal -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 h-100">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="bi bi-calendar-event me-1 text-primary"></i> Jadwal Peminjaman
                                </h6>
                                <div class="d-flex flex-column gap-2 small">
                                    <div>
                                        <span class="text-muted d-block">Tanggal Peminjaman:</span>
                                        <span class="fw-semibold text-dark">{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-muted d-block">Batas Jatuh Tempo:</span>
                                        <span class="fw-semibold {{ $peminjaman->status === 'dipinjam' && $peminjaman->tanggal_jatuh_tempo->isPast() ? 'text-danger' : 'text-dark' }}">
                                            {{ $peminjaman->tanggal_jatuh_tempo->format('d F Y') }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-muted d-block">Tanggal Dikembalikan:</span>
                                        @if($peminjaman->tanggal_kembali)
                                            <span class="fw-bold text-success">{{ $peminjaman->tanggal_kembali->format('d F Y') }}</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis">Belum Kembali</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Buku Terkait -->
                        <div class="col-12">
                            <div class="p-3 border rounded-4">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="bi bi-book me-1 text-primary"></i> Informasi Buku yang Dipinjam
                                </h6>
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="fw-bold text-dark mb-1">{{ $peminjaman->buku->judul }}</h5>
                                        <p class="text-muted small mb-2">
                                            Penulis: <span class="text-dark fw-medium">{{ $peminjaman->buku->penulis }}</span> &bull; 
                                            Penerbit: <span class="text-dark fw-medium">{{ $peminjaman->buku->penerbit }}</span> ({{ $peminjaman->buku->tahun_terbit }})
                                        </p>
                                        <div class="d-flex gap-2">
                                            <span class="badge badge-soft-blue rounded-pill px-2 py-1">
                                                {{ $peminjaman->buku->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                            </span>
                                            <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                                ISBN: {{ $peminjaman->buku->isbn }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        <a href="{{ route('buku.show', $peminjaman->buku) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Katalog Buku
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Sirkulasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
