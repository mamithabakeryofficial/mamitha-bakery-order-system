@extends('layouts.app')

@section('title', 'Daftar Akun - Mamitha Bakery')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="max-width: 1000px; width: 100%;">
        <div class="row g-0">
            <!-- Left Side: Image -->
            <div class="col-md-5 d-none d-md-block bg-primary-custom position-relative">
                <div class="position-absolute w-100 h-100" style="background: url('https://images.unsplash.com/photo-1558961363-fa8fdf82db35?q=80&w=1000&auto=format&fit=crop') center/cover; opacity: 0.7;"></div>
                <div class="position-absolute w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white p-4 text-center" style="background: rgba(139, 69, 19, 0.4);">
                    <h2 class="fw-bold mb-3">Bergabunglah Bersama Kami!</h2>
                    <p>Buat akun sekarang dan nikmati kemudahan memesan roti favorit Anda secara online.</p>
                </div>
            </div>
            
            <!-- Right Side: Form -->
            <div class="col-md-7 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary-custom">Buat Akun Baru</h3>
                    <p class="text-muted">Lengkapi form di bawah ini untuk mendaftar</p>
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

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required placeholder="Unik, misal: budi123">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label fw-semibold">Nomor WhatsApp</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="0812xxxxxx">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-end-0" id="password" name="password" required placeholder="Minimal 8 karakter">
                                <span class="input-group-text bg-white cursor-pointer toggle-password" data-target="password" style="cursor: pointer;">
                                    <i class="fas fa-eye-slash text-muted"></i>
                                </span>
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">Harus mengandung huruf dan angka.</small>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-end-0" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
                                <span class="input-group-text bg-white cursor-pointer toggle-password" data-target="password_confirmation" style="cursor: pointer;">
                                    <i class="fas fa-eye-slash text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 fw-bold rounded-3 mb-3">DAFTAR SEKARANG</button>

                    <div class="text-center">
                        <p class="text-muted mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary-custom fw-semibold text-decoration-none">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        });
    });
</script>
