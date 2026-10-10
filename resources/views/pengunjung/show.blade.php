<x-layout>
    <x-slot:title>Detail Kunjungan: {{ $pengunjung->nama }}</x-slot:title>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header card-del-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-person-badge me-2"></i>Rincian Log Kunjungan Pemustaka
                    </h5>
                    <span class="badge bg-light text-dark font-monospace">ID: #{{ $pengunjung->id }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <div class="p-3 rounded-circle bg-del-purple text-white">
                            <i class="bi bi-person-fill fs-2"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-dark mb-0">{{ $pengunjung->nama }}</h3>
                            <div class="text-muted">
                                <code>{{ $pengunjung->nim_nik }}</code> &bull; {{ $pengunjung->institusi_prodi ?? 'Umum' }}
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3">
                                <span class="text-muted small d-block">Kategori Pengunjung</span>
                                <span class="fw-bold text-dark fs-6">{{ $pengunjung->jenis_pengunjung }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3">
                                <span class="text-muted small d-block">Waktu & Tanggal Kehadiran</span>
                                <span class="fw-bold text-dark fs-6">
                                    <i class="bi bi-calendar-event me-1"></i>{{ $pengunjung->created_at->format('d F Y, H:i:s') }} WIB
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-light p-3 rounded-3">
                                <span class="text-muted small d-block">Tujuan / Keperluan Kunjungan</span>
                                <span class="fw-bold text-del-purple fs-6">{{ $pengunjung->keperluan }}</span>
                            </div>
                        </div>
                        @if($pengunjung->keterangan)
                            <div class="col-12">
                                <div class="bg-light p-3 rounded-3">
                                    <span class="text-muted small d-block">Catatan Tambahan</span>
                                    <p class="mb-0 text-secondary">{{ $pengunjung->keterangan }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('pengunjung.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Log
                        </a>
                        <form action="{{ route('pengunjung.destroy', $pengunjung) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data log ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash me-1"></i> Hapus Catatan Log
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
