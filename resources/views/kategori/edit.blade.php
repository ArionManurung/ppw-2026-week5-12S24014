<x-layout>
    <x-slot:title>Edit Kategori: {{ $kategori->nama_kategori }}</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card card-modern overflow-hidden">
                <div class="card-del-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square fs-5"></i>
                        <h5 class="mb-0 fw-bold">Edit Kategori Buku</h5>
                    </div>
                    <span class="badge bg-white text-del-purple rounded-pill px-3 py-1 fw-bold small">ID: #{{ $kategori->id }}</span>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('kategori.update', $kategori) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="kode_kategori" class="form-label fw-semibold text-dark">Kode Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="kode_kategori" id="kode_kategori" class="form-control @error('kode_kategori') is-invalid @enderror" value="{{ old('kode_kategori', $kategori->kode_kategori) }}" required>
                            @error('kode_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nama_kategori" class="form-label fw-semibold text-dark">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" id="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                            @error('nama_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-del rounded-pill px-4">
                                <i class="bi bi-check2-circle me-1"></i> Perbarui Kategori
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
