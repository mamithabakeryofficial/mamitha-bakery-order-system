@extends('layouts.app')

@section('title', 'Verifikasi OTP - Mamitha Bakery')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5" style="max-width: 500px; width: 100%;">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary-custom">Verifikasi OTP</h3>
            <p class="text-muted">Masukkan kode 6 digit yang telah kami kirimkan ke email <strong>{{ $email }}</strong>.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success rounded-3 text-center">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.verify_otp.post') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div class="mb-4 text-center">
                <label for="otp" class="form-label fw-semibold d-block">Kode OTP</label>
                <input type="text" class="form-control form-control-lg text-center fw-bold text-primary-custom mx-auto" style="letter-spacing: 10px; max-width: 250px; font-size: 24px;" id="otp" name="otp" value="{{ old('otp') }}" required maxlength="6" pattern="\d{6}" autocomplete="off" autofocus placeholder="------">
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 py-2 fw-bold rounded-3 mb-3">VERIFIKASI KODE</button>

        </form>

        <div class="text-center mt-3">
            <p class="text-muted mb-2">Kode OTP berlaku dalam: <span id="timer" class="fw-bold text-danger">01:00</span></p>
            
            <form action="{{ route('password.email') }}" method="POST" id="resendForm">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button type="submit" id="resendBtn" class="btn btn-link text-decoration-none fw-semibold" disabled>
                    Kirim Ulang OTP
                </button>
            </form>
            
            <a href="{{ route('login') }}" class="text-muted text-decoration-none mt-3 d-block"><i class="fas fa-arrow-left me-1"></i> Kembali ke Login</a>
        </div>
    </div>
</div>
@endsection

@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Only allow numbers in OTP input
        const otpInput = document.getElementById('otp');
        otpInput.addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Countdown Timer Logic
        let timeLeft = 60; // 60 seconds
        const timerDisplay = document.getElementById('timer');
        const resendBtn = document.getElementById('resendBtn');
        
        const countdown = setInterval(function() {
            if (timeLeft <= 0) {
                clearInterval(countdown);
                timerDisplay.innerHTML = "00:00 (Kadaluarsa)";
                timerDisplay.classList.remove('text-danger');
                timerDisplay.classList.add('text-muted');
                
                // Enable resend button
                resendBtn.disabled = false;
                resendBtn.classList.add('text-primary-custom');
            } else {
                let seconds = timeLeft % 60;
                // Add leading zero
                seconds = seconds < 10 ? "0" + seconds : seconds;
                timerDisplay.innerHTML = "00:" + seconds;
            }
            timeLeft -= 1;
        }, 1000);
    });
</script>
