@extends('layouts.app')

@section('title', 'Profil Pelanggan - Mamitha Bakery')

@section('content')
@include('layouts.partials.customer-navbar')

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary-custom">Profil Saya</h2>
            <p class="text-muted">Kelola informasi data diri dan keamanan akun Anda.</p>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Data Diri -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0">Informasi Pribadi</h5>
                </div>
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success rounded-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any() && !session('success_password'))
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4 align-items-center">
                            <div class="col-auto">
                                <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle shadow-sm" width="100" height="100" id="avatarPreview" style="object-fit: cover;">
                            </div>
                            <div class="col">
                                <label for="avatar" class="form-label fw-semibold">Foto Profil</label>
                                <input class="form-control" type="file" id="avatar" name="avatar" accept="image/*" onchange="previewImage(this)">
                                <small class="text-muted">Format: JPG, PNG, GIF. Maks: 2MB.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label fw-semibold">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-semibold">Nomor WhatsApp</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $profile->address ?? $user->address) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label fw-semibold">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date', $profile->birth_date?->format('Y-m-d') ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label fw-semibold">Jenis Kelamin</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="" {{ old('gender', $profile->gender ?? '') == '' ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                                    <option value="male" {{ old('gender', $profile->gender ?? '') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender', $profile->gender ?? '') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-custom py-2 fw-bold rounded-3">SIMPAN PERUBAHAN</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Keamanan Akun (Ganti Password) -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0">Keamanan Akun</h5>
                </div>
                <div class="card-body p-4">
                    @if (session('success_password'))
                        <div class="alert alert-success rounded-3">
                            {{ session('success_password') }}
                        </div>
                    @endif
                    
                    @if ($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
                        <div class="alert alert-danger rounded-3">
                            Gagal mengubah password. Periksa form di bawah.
                        </div>
                    @endif

                    <form action="{{ route('customer.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-end-0 @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                <span class="input-group-text bg-white cursor-pointer toggle-password" data-target="current_password" style="cursor: pointer;">
                                    <i class="fas fa-eye-slash text-muted"></i>
                                </span>
                            </div>
                            @error('current_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-end-0 @error('password') is-invalid @enderror" id="password" name="password" required>
                                <span class="input-group-text bg-white cursor-pointer toggle-password" data-target="password" style="cursor: pointer;">
                                    <i class="fas fa-eye-slash text-muted"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Harus mengandung huruf dan angka, min 8 karakter.</small>
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

                        <button type="submit" class="btn btn-outline-primary w-100 py-2 fw-bold rounded-3">GANTI PASSWORD</button>
                    </form>
                </div>
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

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
