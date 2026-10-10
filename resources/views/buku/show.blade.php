<x-layout>
    <x-slot:title>Detail Buku: {{ $buku->judul }}</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header card-del-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-book me-2"></i>Rincian Informasi Buku</h5>
                    <span class="badge bg-light text-dark font-monospace">{{ $buku->isbn }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="fw-bold text-dark mb-2">{{ $buku->judul }}</h3>
                            <p class="text-muted fs-6 mb-3">
                                Oleh <span class="fw-semibold text-dark">{{ $buku->penulis }}</span> &bull; Diterbitkan oleh <span class="fw-semibold text-dark">{{ $buku->penerbit }}</span> ({{ $buku->tahun_terbit }})
                            </p>

                            <div class="mb-4">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 me-2">
                                    <i class="bi bi-tag me-1"></i> {{ $buku->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                </span>
                                @if($buku->stok > 0)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                        <i class="bi bi-box-seam me-1"></i> Stok tersedia: {{ $buku->stok }} eks
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2">
                                        <i class="bi bi-exclamation-circle me-1"></i> Stok Habis
                                    </span>
                                @endif
                            </div>

                            <h6 class="fw-bold text-del-purple border-bottom pb-2">Sinopsis / Ringkasan</h6>
                            <p class="text-secondary lh-base">
                                {{ $buku->sinopsis ?? 'Belum ada sinopsis untuk buku ini.' }}
                            </p>
                        </div>
                        <div class="col-md-4 border-start ps-4 d-flex flex-column justify-content-between">
                            <div class="bg-light p-3 rounded-3 mb-3">
                                <h6 class="fw-bold mb-2">Metadata Sistem</h6>
                                <ul class="list-unstyled small text-muted mb-0">
                                    <li class="mb-1"><strong>Kode Kategori:</strong> {{ $buku->kategori->kode_kategori ?? '-' }}</li>
                                    <li class="mb-1"><strong>Ditambahkan:</strong> {{ $buku->created_at->format('d M Y H:i') }}</li>
                                    <li><strong>Terakhir Diperbarui:</strong> {{ $buku->updated_at->format('d M Y H:i') }}</li>
                                </ul>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('buku.edit', $buku) }}" class="btn btn-warning text-white">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Data Buku
                                </a>
                                <form action="{{ route('buku.destroy', $buku) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="bi bi-trash me-1"></i> Hapus Buku
                                    </button>
                                </form>
                                <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">
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
