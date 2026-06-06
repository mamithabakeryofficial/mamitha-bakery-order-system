@extends('layouts.app')

@section('title', 'Reset Password - Mamitha Bakery')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5" style="max-width: 500px; width: 100%;">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary-custom">Reset Password</h3>
            <p class="text-muted">Silakan buat password baru Anda.</p>
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

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                <input type="email" class="form-control bg-light" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password Baru</label>
                <div class="input-group">
                    <input type="password" class="form-control border-end-0" id="password" name="password" required autofocus>
                    <span class="input-group-text bg-white cursor-pointer toggle-password" data-target="password" style="cursor: pointer;">
                        <i class="fas fa-eye-slash text-muted"></i>
                    </span>
                </div>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                <div class="input-group">
                    <input type="password" class="form-control border-end-0" id="password_confirmation" name="password_confirmation" required>
                    <span class="input-group-text bg-white cursor-pointer toggle-password" data-target="password_confirmation" style="cursor: pointer;">
                        <i class="fas fa-eye-slash text-muted"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 py-2 fw-bold rounded-3">SIMPAN PASSWORD BARU</button>
        </form>
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
