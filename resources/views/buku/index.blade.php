<x-layout>
    <x-slot:title>Daftar Buku</x-slot:title>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-del-purple mb-0"><i class="bi bi-journals me-2"></i>Katalog Buku Perpustakaan</h2>
            <p class="text-muted small mb-0">Kelola koleksi buku dan ketersediaan stok di Sistem Informasi Perpustakaan Kampus Del.</p>
        </div>
        <a href="{{ route('buku.create') }}" class="btn btn-del shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Buku Baru
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="ps-4" style="width: 5%;">No</th>
                            <th style="width: 15%;">ISBN</th>
                            <th style="width: 25%;">Judul Buku</th>
                            <th style="width: 15%;">Penulis</th>
                            <th style="width: 15%;">Kategori</th>
                            <th class="text-center" style="width: 8%;">Stok</th>
                            <th class="text-end pe-4" style="width: 17%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bukus as $index => $buku)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">{{ $bukus->firstItem() + $index }}</td>
                                <td><code class="text-dark bg-light px-2 py-1 rounded">{{ $buku->isbn }}</code></td>
                                <td>
                                    <a href="{{ route('buku.show', $buku) }}" class="fw-bold text-decoration-none text-dark">
                                        {{ $buku->judul }}
                                    </a>
                                    <div class="small text-muted">{{ $buku->penerbit }} ({{ $buku->tahun_terbit }})</div>
                                </td>
                                <td>{{ $buku->penulis }}</td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">
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
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('buku.show', $buku) }}" class="btn btn-outline-info" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('buku.edit', $buku) }}" class="btn btn-outline-warning" title="Edit Buku">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('buku.destroy', $buku) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus Buku">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                    <p class="mb-1 fw-bold">Belum Ada Data Buku</p>
                                    <small>Silakan klik tombol "Tambah Buku Baru" di atas untuk menambahkan data.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($bukus->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $bukus->links() }}
                </div>
            </div>
        @endif
    </div>
</x-layout>
