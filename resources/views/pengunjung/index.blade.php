<x-layout>
    <x-slot:title>Log Kunjungan Perpustakaan</x-slot:title>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-del-purple mb-0">
                <i class="bi bi-person-lines-fill me-2"></i>Buku Tamu & Log Pengunjung
            </h2>
            <p class="text-muted small mb-0">Pencatatan dan pemantauan riwayat kehadiran pemustaka di Perpustakaan Institut Teknologi Del.</p>
        </div>
        <a href="{{ route('pengunjung.create') }}" class="btn btn-del shadow-sm">
            <i class="bi bi-pencil-square me-1"></i> Isi Buku Tamu Kunjungan
        </a>
    </div>

    <!-- Statistik Ringkas -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="p-3 rounded-circle bg-primary-subtle text-primary">
                        <i class="bi bi-calendar-check fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Kunjungan Hari Ini</span>
                        <h4 class="fw-bold mb-0">{{ $totalHariIni }} orang</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="p-3 rounded-circle bg-success-subtle text-success">
                        <i class="bi bi-mortarboard fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Pengunjung Mahasiswa</span>
                        <h4 class="fw-bold mb-0">{{ $totalMahasiswa }} orang</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="p-3 rounded-circle bg-warning-subtle text-warning">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Total Riwayat Kunjungan</span>
                        <h4 class="fw-bold mb-0">{{ $totalSemua }} data</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Filter & Pencarian -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('pengunjung.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
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
                    <button type="submit" class="btn btn-del w-100">Filter</button>
                    @if(request()->hasAny(['q', 'jenis']))
                        <a href="{{ route('pengunjung.index') }}" class="btn btn-outline-secondary" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Riwayat Log Kunjungan -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="ps-4" style="width: 5%;">No</th>
                            <th style="width: 15%;">Waktu Kunjung</th>
                            <th style="width: 15%;">NIM / Identitas</th>
                            <th style="width: 20%;">Nama Pengunjung</th>
                            <th style="width: 15%;">Kategori</th>
                            <th style="width: 18%;">Keperluan</th>
                            <th class="text-end pe-4" style="width: 12%;">Aksi</th>
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
                                <td><code>{{ $log->nim_nik }}</code></td>
                                <td>
                                    <a href="{{ route('pengunjung.show', $log) }}" class="fw-bold text-decoration-none text-dark">
                                        {{ $log->nama }}
                                    </a>
                                    <div class="small text-muted">{{ $log->institusi_prodi ?? '-' }}</div>
                                </td>
                                <td>
                                    @if($log->jenis_pengunjung == 'Mahasiswa')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1">
                                            <i class="bi bi-mortarboard me-1"></i> Mahasiswa
                                        </span>
                                    @elseif($log->jenis_pengunjung == 'Dosen / Staf')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                            <i class="bi bi-person-badge me-1"></i> Dosen / Staf
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-1">
                                            <i class="bi bi-person me-1"></i> Tamu Umum
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-dark small fw-medium">{{ $log->keperluan }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('pengunjung.show', $log) }}" class="btn btn-outline-info" title="Lihat Rincian">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('pengunjung.destroy', $log) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data log ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus Log">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-clipboard2-x fs-1 d-block mb-2 text-secondary"></i>
                                    <p class="mb-1 fw-bold">Belum Ada Log Pengunjung</p>
                                    <small>Silakan isi buku tamu kunjungan melalui tombol di atas.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pengunjungs->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $pengunjungs->links() }}
                </div>
            </div>
        @endif
    </div>
</x-layout>
