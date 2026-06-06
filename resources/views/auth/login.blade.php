@extends('layouts.app')

@section('title', 'Login - Mamitha Bakery')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="max-width: 1000px; width: 100%;">
        <div class="row g-0">
            <!-- Left Side: Image / Promo -->
            <div class="col-md-6 d-none d-md-block bg-primary-custom position-relative">
                <div class="position-absolute w-100 h-100" style="background: url('https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=1000&auto=format&fit=crop') center/cover; opacity: 0.6;"></div>
                <div class="position-absolute w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white p-5 text-center" style="background: rgba(139, 69, 19, 0.4);">
                    <h2 class="fw-bold mb-3">Selamat Datang di Mamitha Bakery!</h2>
                    <p class="lead">Rasakan kelezatan roti dan kue segar buatan rumah setiap hari. Promo spesial menanti Anda!</p>
                </div>
            </div>
            
            <!-- Right Side: Form -->
            <div class="col-md-6 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary-custom">Masuk Akun</h3>
                    <p class="text-muted">Silakan login untuk mulai memesan</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="login" class="form-label fw-semibold">Email atau Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="login" name="login" value="{{ old('login') }}" required autocomplete="username" placeholder="Masukkan email / username">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                            <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="password" name="password" required autocomplete="current-password" placeholder="Masukkan password">
                            <span class="input-group-text bg-light cursor-pointer toggle-password" style="cursor: pointer;">
                                <i class="fas fa-eye-slash text-muted" id="togglePasswordIcon"></i>
                            </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="remember">
                                Ingat Saya (30 Hari)
                            </label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-decoration-none text-primary-custom fw-semibold">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 fw-bold rounded-3 mb-3">LOGIN</button>

                    <div class="text-center">
                        <p class="text-muted mb-2">Belum punya akun? <a href="{{ route('register') }}" class="text-primary-custom fw-semibold text-decoration-none">Buat Akun Baru</a></p>
                        <button type="button" class="btn btn-link text-muted text-decoration-none small p-0 animate-fade-in" style="font-size: 0.85rem;" data-bs-toggle="modal" data-bs-target="#loginHelpModal">
                            <i class="fas fa-question-circle me-1 text-info"></i> Bingung cara membuat akun? Klik di sini
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Help/Guidance Modal -->
<div class="modal fade" id="loginHelpModal" tabindex="-1" aria-labelledby="loginHelpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-primary-custom" id="loginHelpModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Panduan Pendaftaran Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <h6 class="fw-bold text-dark mb-2">1. Pendaftaran Akun Pelanggan (Customer)</h6>
                <p class="text-muted small mb-4" style="line-height: 1.5;">
                    Klik tombol <strong>"Mulai Buat Akun"</strong> di bawah atau link <strong>"Buat Akun Baru"</strong> di halaman login. Isi nama lengkap, username unik, email aktif, nomor WhatsApp Anda, serta sandi minimal 8 karakter. Setelah mendaftar, Anda bisa langsung memesan produk bakery favorit Anda.
                </p>

                <h6 class="fw-bold text-dark mb-2">2. Akun Staff / Kurir / Dapur / Admin</h6>
                <p class="text-muted small mb-0" style="line-height: 1.5;">
                    Akun selain pelanggan tidak didaftarkan secara umum demi keamanan. Silakan minta Administrator toko untuk menambahkan akun Anda melalui modul <strong>Kelola Staff</strong> di dashboard Admin utama.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-3 fw-semibold" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('register') }}" class="btn btn-primary-custom rounded-3 fw-bold">Mulai Buat Akun</a>
            </div>
        </div>
    </div>
</div>
@endsection

@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('.toggle-password');
        const password = document.querySelector('#password');
        const icon = document.querySelector('#togglePasswordIcon');

        if(togglePassword) {
            togglePassword.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // toggle the icon
                if (type === 'password') {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }
    });
</script>
