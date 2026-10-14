<x-layout>
    <x-slot:title>Masuk Pustakawan</x-slot:title>

    <div class="row justify-content-center py-4">
        <div class="col-md-7 col-lg-5">
            <div class="card card-modern overflow-hidden shadow-sm">
                <div class="card-del-header text-center py-4">
                    <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle bg-white text-del-purple mb-2 shadow-sm" style="width: 56px; height: 56px;">
                        <i class="bi bi-shield-lock-fill fs-3 text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Autentikasi Pustakawan</h4>
                    <p class="small text-white-50 mb-0">SIPUS-Del &bull; Institut Teknologi Del</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <!-- Akun Demo Card Banner -->
                    <div class="alert alert-info border-0 rounded-3 mb-4 small">
                        <div class="d-flex align-items-center gap-2 mb-1 fw-bold text-primary">
                            <i class="bi bi-info-circle-fill"></i> Akun Pengujian Pustakawan:
                        </div>
                        <div class="font-monospace ms-3">
                            <div>Email: <strong>pustakawan@del.ac.id</strong></div>
                            <div>Kata Sandi: <strong>password123</strong></div>
                        </div>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <!-- Alamat Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold text-dark">
                                Alamat Email Pustakawan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                       placeholder="nama@del.ac.id" 
                                       value="{{ old('email', $rememberedEmail) }}" 
                                       required 
                                       autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @if($rememberedEmail)
                                <div class="form-text small text-muted">
                                    <i class="bi bi-cookie text-secondary"></i> Alamat email terdeteksi otomatis dari Cookie penjelajah Anda.
                                </div>
                            @endif
                        </div>

                        <!-- Kata Sandi -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold text-dark">
                                Kata Sandi <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">
                                    <i class="bi bi-key"></i>
                                </span>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                       placeholder="••••••••" 
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="remember">
                                    Ingat sesi perangkat ini
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-app py-2 fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Dashboard Pustakawan
                            </button>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('buku.index') }}" class="small text-muted text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog Terbuka
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
