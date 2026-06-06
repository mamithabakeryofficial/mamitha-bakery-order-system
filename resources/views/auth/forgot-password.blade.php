@extends('layouts.app')

@section('title', 'Lupa Password - Mamitha Bakery')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5" style="max-width: 500px; width: 100%;">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary-custom">Lupa Password?</h3>
            <p class="text-muted">Masukkan email Anda untuk menerima link reset password.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success rounded-3">
                {{ session('status') }}
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

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                    <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com" autofocus>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 py-2 fw-bold rounded-3 mb-3">KIRIM LINK RESET</button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-muted text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Kembali ke Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
