<x-layout>
    <x-slot:title>Detail Kategori: {{ $kategori->nama_kategori }}</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Header Card -->
            <div class="card card-modern overflow-hidden mb-4">
                <div class="card-del-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-tag fs-5"></i>
                        <h5 class="card-title mb-0 fw-bold">Rincian Kategori Buku</h5>
                    </div>
                    <span class="badge bg-white text-del-purple font-monospace px-3 py-1 rounded-pill">{{ $kategori->kode_kategori }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h3 class="fw-bold text-dark mb-1">{{ $kategori->nama_kategori }}</h3>
                            <p class="text-muted small mb-0">Total Buku Terdaftar: <strong class="text-dark">{{ $kategori->bukus->count() }} judul buku</strong></p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-warning text-dark fw-semibold rounded-pill px-3">
                                <i class="bi bi-pencil me-1"></i> Edit Kategori
                            </a>
                            <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List Buku di Kategori Ini -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-collection me-2 text-del-purple"></i>Daftar Buku Dalam Kategori Ini
                </h5>
            </div>

            <div class="card card-modern overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
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
                                    <td class="ps-4">
                                        <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                            {{ $buku->isbn }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('buku.show', $buku) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $buku->judul }}
                                        </a>
                                    </td>
                                    <td><span class="text-secondary">{{ $buku->penulis }}</span></td>
                                    <td>{{ $buku->tahun_terbit }}</td>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-journal-x fs-1 text-secondary d-block mb-2"></i>
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
</x-layout>
