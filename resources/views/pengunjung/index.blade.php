<x-layout>
    <x-slot:title>Log Kunjungan Perpustakaan</x-slot:title>

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-del-soft text-del-purple fw-semibold small mb-2">
                <i class="bi bi-person-check"></i> Buku Tamu Digital
            </div>
            <h2 class="fw-bold text-dark mb-1">Buku Tamu & Log Pengunjung</h2>
            <p class="text-muted small mb-0">Pencatatan dan pemantauan riwayat kehadiran pemustaka di Perpustakaan Institut Teknologi Del.</p>
        </div>
        <div>
            <a href="{{ route('pengunjung.create') }}" class="btn btn-del shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-pencil-square"></i>
                <span>Isi Buku Tamu Kunjungan</span>
            </a>
        </div>
    </div>

    <!-- Statistik Ringkas Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-modern p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-4 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="bi bi-calendar-check fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Kunjungan Hari Ini</span>
                        <h4 class="fw-bold text-dark mb-0">{{ $totalHariIni }} orang</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-modern p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-4 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="bi bi-mortarboard fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Pengunjung Mahasiswa</span>
                        <h4 class="fw-bold text-dark mb-0">{{ $totalMahasiswa }} orang</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-modern p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-4 bg-secondary-subtle text-secondary-emphasis d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total Riwayat Kunjungan</span>
                        <h4 class="fw-bold text-dark mb-0">{{ $totalSemua }} data</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Filter & Pencarian -->
    <div class="card card-modern mb-4 p-3">
        <form action="{{ route('pengunjung.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Cari berdasarkan nama, NIM/NIK, atau prodi...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="jenis" class="form-select">
                    <option value="">-- Semua Jenis Pengunjung --</option>
                    <option value="Mahasiswa" {{ request('jenis') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="Dosen / Staf" {{ request('jenis') == 'Dosen / Staf' ? 'selected' : '' }}>Dosen / Staf</option>
                    <option value="Tamu / Umum" {{ request('jenis') == 'Tamu / Umum' ? 'selected' : '' }}>Tamu / Umum</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-del w-100 rounded-pill">Filter</button>
                @if(request()->hasAny(['q', 'jenis']))
                    <a href="{{ route('pengunjung.index') }}" class="btn btn-outline-secondary rounded-pill" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
                @endif
            </div>
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="card card-modern overflow-hidden">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 5%;">No</th>
                        <th style="width: 15%;">Waktu Kunjung</th>
                        <th style="width: 15%;">NIM / Identitas</th>
                        <th style="width: 22%;">Nama Pengunjung</th>
                        <th style="width: 15%;">Kategori</th>
                        <th style="width: 17%;">Keperluan</th>
                        <th class="text-end pe-4" style="width: 11%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengunjungs as $index => $log)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $pengunjungs->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $log->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $log->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                    {{ $log->nim_nik }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('pengunjung.show', $log) }}" class="fw-bold text-decoration-none text-dark d-block">
                                    {{ $log->nama }}
                                </a>
                                <span class="small text-muted">{{ $log->institusi_prodi ?? '-' }}</span>
                            </td>
                            <td>
                                @if($log->jenis_pengunjung == 'Mahasiswa')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">
                                        <i class="bi bi-mortarboard me-1"></i> Mahasiswa
                                    </span>
                                @elseif($log->jenis_pengunjung == 'Dosen / Staf')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                        <i class="bi bi-person-badge me-1"></i> Dosen / Staf
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-3 py-1">
                                        <i class="bi bi-person me-1"></i> Tamu Umum
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="text-dark small fw-medium">{{ $log->keperluan }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('pengunjung.show', $log) }}" class="btn btn-sm btn-light border text-info" title="Lihat Rincian">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('pengunjung.destroy', $log) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data log ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus Log">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <div class="mb-3 d-inline-flex align-items-center justify-content-center p-3 rounded-circle bg-light">
                                        <i class="bi bi-clipboard2-x fs-1 text-secondary"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Log Pengunjung</h6>
                                    <p class="small text-muted mb-3">Silakan isi formulir buku tamu kehadiran.</p>
                                    <a href="{{ route('pengunjung.create') }}" class="btn btn-sm btn-del">
                                        <i class="bi bi-pencil-square me-1"></i> Isi Buku Tamu
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengunjungs->hasPages())
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-center">
                    {{ $pengunjungs->links() }}
                </div>
            </div>
        @endif
    </div>
</x-layout>
