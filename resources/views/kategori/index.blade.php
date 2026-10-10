<x-layout>
    <x-slot:title>Kategori Buku</x-slot:title>

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-del-soft text-del-purple fw-semibold small mb-2">
                <i class="bi bi-tag"></i> Klasifikasi Buku
            </div>
            <h2 class="fw-bold text-dark mb-1">Kategori Buku</h2>
            <p class="text-muted small mb-0">Kelola kelompok klasifikasi dan pengelompokan buku di perpustakaan.</p>
        </div>
        <div>
            <a href="{{ route('kategori.create') }}" class="btn btn-del shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Kategori</span>
            </a>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card card-modern overflow-hidden">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 8%;">No</th>
                        <th style="width: 25%;">Kode Kategori</th>
                        <th style="width: 37%;">Nama Kategori</th>
                        <th class="text-center" style="width: 15%;">Jumlah Buku</th>
                        <th class="text-end pe-4" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $index => $kategori)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $kategoris->firstItem() + $index }}</td>
                            <td>
                                <span class="badge bg-light text-del-purple border font-monospace px-2 py-1 fs-6">
                                    {{ $kategori->kode_kategori }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('kategori.show', $kategori) }}" class="fw-bold text-decoration-none text-dark">
                                    {{ $kategori->nama_kategori }}
                                </a>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-3 py-1">
                                    {{ $kategori->bukus_count }} judul buku
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('kategori.show', $kategori) }}" class="btn btn-sm btn-light border text-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-sm btn-light border text-warning" title="Edit Kategori">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('kategori.destroy', $kategori) }}" method="POST" class="d-inline" onsubmit="return confirm('Menghapus kategori ini akan menghapus semua buku di dalamnya. Yakin?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus Kategori">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <div class="mb-3 d-inline-flex align-items-center justify-content-center p-3 rounded-circle bg-light">
                                        <i class="bi bi-tags fs-1 text-secondary"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Kategori</h6>
                                    <p class="small text-muted mb-3">Silakan tambahkan kategori buku baru.</p>
                                    <a href="{{ route('kategori.create') }}" class="btn btn-sm btn-del">
                                        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kategoris->hasPages())
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-center">
                    {{ $kategoris->links() }}
                </div>
            </div>
        @endif
    </div>
</x-layout>
