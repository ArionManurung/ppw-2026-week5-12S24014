<x-layout>
    <x-slot:title>Catat Peminjaman Buku</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-modern overflow-hidden">
                <div class="card-del-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-journal-plus fs-5"></i>
                        <h5 class="card-title mb-0 fw-bold">Formulir Peminjaman Buku (ACID Transaction)</h5>
                    </div>
                    <span class="badge bg-white text-del-purple font-monospace px-3 py-1 rounded-pill">Sirkulasi</span>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="alert alert-info border-0 rounded-3 mb-4 d-flex align-items-center gap-3">
                        <i class="bi bi-shield-check fs-4 text-primary"></i>
                        <div class="small">
                            <strong>Jaminan Konsistensi ACID:</strong> Peminjaman ini menggunakan mekanisme <em>Pessimistic Locking</em> (<code>lockForUpdate</code>) untuk memastikan stok buku tidak berkurang menjadi minus dan mencegah peminjaman ganda untuk mahasiswa yang sama.
                        </div>
                    </div>

                    <form action="{{ route('peminjaman.store') }}" method="POST">
                        @csrf

                        <!-- Pilih Buku -->
                        <div class="mb-4">
                            <label for="buku_id" class="form-label fw-semibold text-dark">
                                Buku yang Dipinjam <span class="text-danger">*</span>
                            </label>
                            <select name="buku_id" id="buku_id" class="form-select @error('buku_id') is-invalid @enderror" required>
                                <option value="" disabled {{ !old('buku_id', $selectedBukuId) ? 'selected' : '' }}>-- Pilih Judul Buku --</option>
                                @foreach($bukus as $buku)
                                    <option value="{{ $buku->id }}" 
                                        {{ old('buku_id', $selectedBukuId) == $buku->id ? 'selected' : '' }}
                                        {{ $buku->stok < 1 ? 'disabled' : '' }}>
                                        {{ $buku->judul }} (ISBN: {{ $buku->isbn }}) — Sisa Stok: {{ $buku->stok }} eks {{ $buku->stok < 1 ? '[HABIS]' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('buku_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text small text-muted">Buku dengan stok 0 tidak dapat dipilih dan dipinjam.</div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- NIM Peminjam -->
                            <div class="col-md-5">
                                <label for="nim_peminjam" class="form-label fw-semibold text-dark">
                                    NIM Pemustaka / Mahasiswa <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="nim_peminjam" 
                                       id="nim_peminjam" 
                                       class="form-control font-monospace @error('nim_peminjam') is-invalid @enderror" 
                                       placeholder="Contoh: 12S24014" 
                                       value="{{ old('nim_peminjam', session('last_borrower_nim')) }}" 
                                       required>
                                @error('nim_peminjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nama Peminjam -->
                            <div class="col-md-7">
                                <label for="nama_peminjam" class="form-label fw-semibold text-dark">
                                    Nama Lengkap Pemustaka <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="nama_peminjam" 
                                       id="nama_peminjam" 
                                       class="form-control @error('nama_peminjam') is-invalid @enderror" 
                                       placeholder="Contoh: Arion Manurung" 
                                       value="{{ old('nama_peminjam', session('last_borrower_name')) }}" 
                                       required>
                                @error('nama_peminjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- Tanggal Pinjam -->
                            <div class="col-md-6">
                                <label for="tanggal_pinjam" class="form-label fw-semibold text-dark">
                                    Tanggal Peminjaman <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="tanggal_pinjam" 
                                       id="tanggal_pinjam" 
                                       class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                                       value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" 
                                       required>
                                @error('tanggal_pinjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Jatuh Tempo -->
                            <div class="col-md-6">
                                <label for="tanggal_jatuh_tempo" class="form-label fw-semibold text-dark">
                                    Tanggal Jatuh Tempo <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="tanggal_jatuh_tempo" 
                                       id="tanggal_jatuh_tempo" 
                                       class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror" 
                                       value="{{ old('tanggal_jatuh_tempo', date('Y-m-d', strtotime('+7 days'))) }}" 
                                       required>
                                @error('tanggal_jatuh_tempo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text small text-muted">Standar durasi peminjaman adalah 7 hari kalender.</div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-app px-4">
                                <i class="bi bi-check-lg me-1"></i> Proses Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
