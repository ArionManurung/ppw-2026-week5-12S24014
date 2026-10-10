<x-layout>
    <x-slot:title>Daftar Kategori Buku</x-slot:title>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-del-purple mb-0"><i class="bi bi-tags me-2"></i>Kategori Buku</h2>
            <p class="text-muted small mb-0">Kelola klasifikasi dan pengelompokan buku di perpustakaan.</p>
        </div>
        <a href="{{ route('kategori.create') }}" class="btn btn-del shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="ps-4" style="width: 10%;">No</th>
                            <th style="width: 25%;">Kode Kategori</th>
                            <th style="width: 40%;">Nama Kategori</th>
                            <th class="text-center" style="width: 15%;">Jumlah Buku</th>
                            <th class="text-end pe-4" style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoris as $index => $kategori)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">{{ $kategoris->firstItem() + $index }}</td>
                                <td><code class="text-del-purple fw-bold bg-light px-2 py-1 rounded">{{ $kategori->kode_kategori }}</code></td>
                                <td>
                                    <a href="{{ route('kategori.show', $kategori) }}" class="fw-bold text-decoration-none text-dark">
                                        {{ $kategori->nama_kategori }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-3">
                                        {{ $kategori->bukus_count }} buku
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('kategori.show', $kategori) }}" class="btn btn-outline-info" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-outline-warning" title="Edit Kategori">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('kategori.destroy', $kategori) }}" method="POST" class="d-inline" onsubmit="return confirm('Menghapus kategori ini akan menghapus semua buku di dalamnya. Yakin?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus Kategori">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-tags fs-1 d-block mb-2 text-secondary"></i>
                                    <p class="mb-1 fw-bold">Belum Ada Kategori Buku</p>
                                    <small>Silakan tambahkan kategori baru terlebih dahulu.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($kategoris->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $kategoris->links() }}
                </div>
            </div>
        @endif
    </div>
</x-layout>
