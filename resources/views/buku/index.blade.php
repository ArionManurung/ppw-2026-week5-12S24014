<x-layout>
    <x-slot:title>Katalog Buku</x-slot:title>

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-del-soft text-del-purple fw-semibold small mb-2">
                <i class="bi bi-collection"></i> Koleksi Perpustakaan
            </div>
            <h2 class="fw-bold text-dark mb-1">Katalog Buku</h2>
            <p class="text-muted small mb-0">Kelola dan pantau seluruh data buku, pengarang, penerbit, serta ketersediaan stok.</p>
        </div>
        <div>
            <a href="{{ route('buku.create') }}" class="btn btn-del shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Buku Baru</span>
            </a>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card card-modern overflow-hidden">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 5%;">No</th>
                        <th style="width: 15%;">ISBN</th>
                        <th style="width: 27%;">Judul Buku</th>
                        <th style="width: 16%;">Penulis</th>
                        <th style="width: 15%;">Kategori</th>
                        <th class="text-center" style="width: 8%;">Stok</th>
                        <th class="text-end pe-4" style="width: 14%;">Aksi</th>
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
                                <span class="badge bg-purple-subtle text-del-purple bg-del-soft rounded-pill px-3 py-1 fw-semibold">
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
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('buku.show', $buku) }}" class="btn btn-sm btn-light border text-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
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
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <div class="mb-3 d-inline-flex align-items-center justify-content-center p-3 rounded-circle bg-light">
                                        <i class="bi bi-inbox fs-1 text-secondary"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Data Buku</h6>
                                    <p class="small text-muted mb-3">Katalog buku masih kosong. Silakan tambahkan buku baru.</p>
                                    <a href="{{ route('buku.create') }}" class="btn btn-sm btn-del">
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
