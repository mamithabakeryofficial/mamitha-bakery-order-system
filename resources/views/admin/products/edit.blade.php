@extends('layouts.app')

@section('title', 'Edit Produk - Admin Mamitha Bakery')

@section('content')
@include('layouts.partials.admin-navbar')

<div class="container py-5">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto">
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-light border mb-3">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Produk
            </a>
            <h2 class="fw-bold text-primary-custom">Edit Produk: {{ $product->name }}</h2>
            <p class="text-muted">Perbarui data produk di bawah ini.</p>
        </div>
    </div>

    {{-- Error Alerts --}}
    @if($errors->any())
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
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
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Roti Sobek Coklat" value="{{ old('name', $product->name) }}" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label fw-bold">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="price" id="price" class="form-control" placeholder="Contoh: 15000" min="0" value="{{ old('price', (int)$product->price) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="image" class="form-label fw-bold">Foto Produk (Kosongkan jika tidak diubah)</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                <small class="text-muted">Format: JPG, JPEG, PNG, WEBP. Maks: 2MB.</small>
                            </div>
                        </div>

                        {{-- Current Image Preview --}}
                        @if($product->image)
                        <div class="mb-3">
                            <p class="form-label fw-bold mb-1">Foto Sekarang:</p>
                            <img src="{{ $product->image_url }}" class="rounded-3 border" width="120" height="120" style="object-fit: cover;">
                        </div>
                        @endif

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Deskripsi Produk</label>
                            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Jelaskan isi produk, bahan, rasa, dll...">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">Aktifkan Produk (Bisa dipesan customer)</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fw-bold rounded-3">
                            <i class="fas fa-save me-2"></i> Perbarui Produk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
