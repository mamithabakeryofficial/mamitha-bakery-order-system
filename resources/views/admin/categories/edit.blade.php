@extends('layouts.app')

@section('title', 'Edit Kategori - Admin Mamitha Bakery')

@section('content')
@include('layouts.partials.admin-navbar')

<div class="container py-5">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-lg-6 mx-auto">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-light border mb-3">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Kategori
            </a>
            <h2 class="fw-bold text-primary-custom">Edit Kategori: {{ $category->name }}</h2>
            <p class="text-muted">Perbarui nama atau foto kategori di bawah ini.</p>
        </div>
    </div>

    {{-- Error Alerts --}}
    @if($errors->any())
        <div class="row mb-4">
            <div class="col-lg-6 mx-auto">
                <div class="alert alert-danger shadow-sm rounded-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Form --}}
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Kue Kering" value="{{ old('name', $category->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">Foto Kategori (Kosongkan jika tidak diubah)</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG, JPEG, PNG, WEBP. Maks: 2MB.</small>
                        </div>

                        {{-- Current Image Preview --}}
                        @if($category->image)
                        <div class="mb-3">
                            <p class="form-label fw-bold mb-1">Foto Sekarang:</p>
                            <img src="{{ asset('storage/' . $category->image) }}" class="rounded-3 border" width="100" height="100" style="object-fit: cover;">
                        </div>
                        @endif

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">Aktifkan Kategori</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fw-bold rounded-3">
                            <i class="fas fa-save me-2"></i> Perbarui Kategori
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
