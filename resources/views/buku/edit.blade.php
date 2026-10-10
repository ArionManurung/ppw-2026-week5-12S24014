<x-layout>
    <x-slot:title>Edit Buku: {{ $buku->judul }}</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-modern overflow-hidden">
                <div class="card-del-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square fs-5"></i>
                        <h5 class="mb-0 fw-bold">Edit Data Buku</h5>
                    </div>
                    <span class="badge bg-white text-del-purple rounded-pill px-3 py-1 fw-bold small">ID: #{{ $buku->id }}</span>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('buku.update', $buku) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="isbn" class="form-label fw-semibold text-dark">Nomor ISBN <span class="text-danger">*</span></label>
                                <input type="text" name="isbn" id="isbn" class="form-control @error('isbn') is-invalid @enderror" value="{{ old('isbn', $buku->isbn) }}" required>
                                @error('isbn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="kategori_id" class="form-label fw-semibold text-dark">Kategori Buku <span class="text-danger">*</span></label>
                                <select name="kategori_id" id="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $buku->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }} ({{ $kategori->kode_kategori }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="judul" class="form-label fw-semibold text-dark">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $buku->judul) }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="penulis" class="form-label fw-semibold text-dark">Penulis <span class="text-danger">*</span></label>
                                <input type="text" name="penulis" id="penulis" class="form-control @error('penulis') is-invalid @enderror" value="{{ old('penulis', $buku->penulis) }}" required>
                                @error('penulis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="penerbit" class="form-label fw-semibold text-dark">Penerbit <span class="text-danger">*</span></label>
                                <input type="text" name="penerbit" id="penerbit" class="form-control @error('penerbit') is-invalid @enderror" value="{{ old('penerbit', $buku->penerbit) }}" required>
                                @error('penerbit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="tahun_terbit" class="form-label fw-semibold text-dark">Tahun Terbit <span class="text-danger">*</span></label>
                                <input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" min="1900" max="2099" required>
                                @error('tahun_terbit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="stok" class="form-label fw-semibold text-dark">Jumlah Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stok" id="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', $buku->stok) }}" min="0" required>
                                @error('stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="sinopsis" class="form-label fw-semibold text-dark">Sinopsis / Ringkasan Buku</label>
                            <textarea name="sinopsis" id="sinopsis" rows="4" class="form-control @error('sinopsis') is-invalid @enderror">{{ old('sinopsis', $buku->sinopsis) }}</textarea>
                            @error('sinopsis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-del rounded-pill px-4">
                                <i class="bi bi-check2-circle me-1"></i> Perbarui Buku
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
