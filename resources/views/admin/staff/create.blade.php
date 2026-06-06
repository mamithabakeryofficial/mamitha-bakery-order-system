@extends('layouts.app')

@section('title', 'Tambah Staff Baru - Mamitha Bakery')

@section('content')
@include('layouts.partials.admin-navbar')

<div class="container py-5">
    {{-- Back Link --}}
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto">
            <a href="{{ route('admin.staff.index') }}" class="btn btn-sm btn-light border mb-3">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Staff
            </a>
            <h2 class="fw-bold text-primary-custom">Tambah Staff Baru</h2>
            <p class="text-muted">Buat akun untuk Admin, Staff Dapur, atau Kurir baru.</p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4 p-md-5">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3 mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.staff.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold text-dark">Nama Lengkap</label>
                                <input type="text" name="name" id="name" class="form-control rounded-3 py-2 px-3" value="{{ old('name') }}" required placeholder="Contoh: Rian Pratama">
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold text-dark">Username</label>
                                <input type="text" name="username" id="username" class="form-control rounded-3 py-2 px-3" value="{{ old('username') }}" required placeholder="Contoh: rianpratama">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold text-dark">Alamat Email</label>
                                <input type="email" name="email" id="email" class="form-control rounded-3 py-2 px-3" value="{{ old('email') }}" required placeholder="Contoh: rian@email.com">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold text-dark">Nomor WhatsApp / HP</label>
                                <input type="text" name="phone" id="phone" class="form-control rounded-3 py-2 px-3" value="{{ old('phone') }}" required placeholder="Contoh: 08123456789">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label fw-semibold text-dark">Peran (Role) Akses</label>
                            <select name="role" id="role" class="form-select rounded-3 py-2 px-3" required>
                                <option value="" disabled selected>-- Pilih Peran Staff --</option>
                                <option value="courier" {{ old('role') === 'courier' ? 'selected' : '' }}>Kurir (Courier) - Mengantar Pesanan</option>
                                <option value="kitchen" {{ old('role') === 'kitchen' ? 'selected' : '' }}>Dapur (Kitchen) - Memproduksi Roti</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator - Mengelola Seluruh Toko</option>
                            </select>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold text-dark">Password</label>
                                <input type="password" name="password" id="password" class="form-control rounded-3 py-2 px-3" required placeholder="Minimal 8 karakter, huruf besar/kecil & angka">
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold text-dark">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-3 py-2 px-3" required placeholder="Ketik ulang password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fw-bold rounded-3">
                            <i class="fas fa-check-circle me-1"></i> Simpan Staff Baru
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
