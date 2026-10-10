<x-layout>
    <x-slot:title>Detail Buku: {{ $buku->judul }}</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card card-modern overflow-hidden">
                <div class="card-del-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-book fs-5"></i>
                        <h5 class="card-title mb-0 fw-bold">Rincian Informasi Buku</h5>
                    </div>
                    <span class="badge bg-white text-del-purple font-monospace px-3 py-1 rounded-pill">{{ $buku->isbn }}</span>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <h3 class="fw-bold text-dark mb-2">{{ $buku->judul }}</h3>
                            <p class="text-muted fs-6 mb-3">
                                Penulis: <span class="fw-semibold text-dark">{{ $buku->penulis }}</span> &bull; Penerbit: <span class="fw-semibold text-dark">{{ $buku->penerbit }}</span> ({{ $buku->tahun_terbit }})
                            </p>

                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge bg-del-soft text-del-purple rounded-pill px-3 py-2 fw-semibold">
                                    <i class="bi bi-tag me-1"></i> {{ $buku->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                </span>
                                @if($buku->stok > 0)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold">
                                        <i class="bi bi-box-seam me-1"></i> Stok tersedia: {{ $buku->stok }} eks
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2 fw-semibold">
                                        <i class="bi bi-exclamation-circle me-1"></i> Stok Habis
                                    </span>
                                @endif
                            </div>

                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Sinopsis / Ringkasan</h6>
                            <p class="text-secondary lh-lg mb-0">
                                {{ $buku->sinopsis ?? 'Belum ada sinopsis untuk buku ini.' }}
                            </p>
                        </div>
                        <div class="col-md-4 border-start ps-md-4 d-flex flex-column justify-content-between">
                            <div class="bg-light p-3 rounded-4 mb-4">
                                <h6 class="fw-bold text-dark mb-3">Metadata Sistem</h6>
                                <div class="d-flex flex-column gap-2 small text-muted">
                                    <div>
                                        <span class="d-block fw-semibold text-dark">Kode Kategori:</span>
                                        <code>{{ $buku->kategori->kode_kategori ?? '-' }}</code>
                                    </div>
                                    <div>
                                        <span class="d-block fw-semibold text-dark">Waktu Ditambahkan:</span>
                                        {{ $buku->created_at->format('d M Y, H:i') }} WIB
                                    </div>
                                    <div>
                                        <span class="d-block fw-semibold text-dark">Terakhir Diperbarui:</span>
                                        {{ $buku->updated_at->format('d M Y, H:i') }} WIB
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('buku.edit', $buku) }}" class="btn btn-warning text-dark fw-semibold rounded-pill">
                                    <i class="bi bi-pencil me-1"></i> Edit Data Buku
                                </a>
                                <form action="{{ route('buku.destroy', $buku) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger rounded-pill w-100">
                                        <i class="bi bi-trash me-1"></i> Hapus Buku
                                    </button>
                                </form>
                                <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary rounded-pill">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
