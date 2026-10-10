<x-layout>
    <x-slot:title>Detail Kategori: {{ $kategori->nama_kategori }}</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header card-del-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-tag me-2"></i>Rincian Kategori Buku</h5>
                    <code class="text-white bg-dark px-2 py-1 rounded">{{ $kategori->kode_kategori }}</code>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold text-dark mb-1">{{ $kategori->nama_kategori }}</h3>
                            <p class="text-muted small mb-0">Total Buku Terdaftar: <strong>{{ $kategori->bukus->count() }} judul</strong></p>
                        </div>
                        <div>
                            <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-warning text-white me-2">
                                <i class="bi bi-pencil-square me-1"></i> Edit Kategori
                            </a>
                            <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold text-del-purple mb-3"><i class="bi bi-journals me-2"></i>Daftar Buku Dalam Kategori Ini</h5>
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ISBN</th>
                                    <th>Judul Buku</th>
                                    <th>Penulis</th>
                                    <th>Tahun</th>
                                    <th class="text-center">Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategori->bukus as $buku)
                                    <tr>
                                        <td class="ps-4"><code>{{ $buku->isbn }}</code></td>
                                        <td>
                                            <a href="{{ route('buku.show', $buku) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $buku->judul }}
                                            </a>
                                        </td>
                                        <td>{{ $buku->penulis }}</td>
                                        <td>{{ $buku->tahun_terbit }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success border px-2 py-1">
                                                {{ $buku->stok }} eks
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            Belum ada buku dalam kategori ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
