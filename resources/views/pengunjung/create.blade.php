<x-layout>
    <x-slot:title>Buku Tamu Pengunjung Perpustakaan</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-modern overflow-hidden">
                <div class="card-del-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square fs-5"></i>
                        <h5 class="mb-0 fw-bold">Formulir Kehadiran Pengunjung</h5>
                    </div>
                    <span class="badge bg-white text-del-purple rounded-pill px-3 py-1 fw-bold small">Buku Tamu Digital</span>
                </div>
                <div class="card-body p-4 p-md-5">
                    <p class="text-muted small mb-4">
                        Selamat datang di Perpustakaan Institut Teknologi Del! Silakan isi formulir kehadiran di bawah ini untuk pencatatan buku tamu.
                    </p>

                    <form action="{{ route('pengunjung.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="nim_nik" class="form-label fw-semibold text-dark">NIM / NIK / No. Identitas <span class="text-danger">*</span></label>
                                <input type="text" name="nim_nik" id="nim_nik" class="form-control @error('nim_nik') is-invalid @enderror" value="{{ old('nim_nik') }}" placeholder="Contoh: 12S22001 / 012345" required>
                                @error('nim_nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nama" class="form-label fw-semibold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="jenis_pengunjung" class="form-label fw-semibold text-dark">Jenis Pengunjung <span class="text-danger">*</span></label>
                                <select name="jenis_pengunjung" id="jenis_pengunjung" class="form-select @error('jenis_pengunjung') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori Pengunjung --</option>
                                    <option value="Mahasiswa" {{ old('jenis_pengunjung') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa IT Del</option>
                                    <option value="Dosen / Staf" {{ old('jenis_pengunjung') == 'Dosen / Staf' ? 'selected' : '' }}>Dosen / Staf / Karyawan</option>
                                    <option value="Tamu / Umum" {{ old('jenis_pengunjung') == 'Tamu / Umum' ? 'selected' : '' }}>Tamu Eksternal / Umum</option>
                                </select>
                                @error('jenis_pengunjung')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="institusi_prodi" class="form-label fw-semibold text-dark">Program Studi / Asal Institusi</label>
                                <input type="text" name="institusi_prodi" id="institusi_prodi" class="form-control @error('institusi_prodi') is-invalid @enderror" value="{{ old('institusi_prodi') }}" placeholder="Contoh: S1 Informatika / Universitas X">
                                @error('institusi_prodi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="keperluan" class="form-label fw-semibold text-dark">Tujuan / Keperluan Kunjungan <span class="text-danger">*</span></label>
                            <select name="keperluan" id="keperluan" class="form-select @error('keperluan') is-invalid @enderror" required>
                                <option value="">-- Pilih Keperluan Utama --</option>
                                <option value="Membaca Buku & Jurnal" {{ old('keperluan') == 'Membaca Buku & Jurnal' ? 'selected' : '' }}>Membaca Koleksi Buku / Jurnal</option>
                                <option value="Peminjaman / Pengembalian Buku" {{ old('keperluan') == 'Peminjaman / Pengembalian Buku' ? 'selected' : '' }}>Peminjaman / Pengembalian Sirkulasi Buku</option>
                                <option value="Diskusi & Belajar Kelompok" {{ old('keperluan') == 'Diskusi & Belajar Kelompok' ? 'selected' : '' }}>Diskusi / Belajar Kelompok</option>
                                <option value="Mencari Referensi Tugas Akhir" {{ old('keperluan') == 'Mencari Referensi Tugas Akhir' ? 'selected' : '' }}>Mencari Referensi Skripsi / Tugas Akhir / Proyek</option>
                                <option value="Menggunakan Fasilitas Komputer" {{ old('keperluan') == 'Menggunakan Fasilitas Komputer' ? 'selected' : '' }}>Mengakses Komputer / Internet Perpustakaan</option>
                                <option value="Lainnya" {{ old('keperluan') == 'Lainnya' ? 'selected' : '' }}>Keperluan Lainnya</option>
                            </select>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="keterangan" class="form-label fw-semibold text-dark">Catatan Tambahan (Opsional)</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Tuliskan catatan tambahan jika ada">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <a href="{{ route('pengunjung.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-del rounded-pill px-4">
                                <i class="bi bi-check2-circle me-1"></i> Simpan Kehadiran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
