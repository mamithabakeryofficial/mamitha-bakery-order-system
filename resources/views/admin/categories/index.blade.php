@extends('layouts.app')

@section('title', 'Manajemen Kategori - Admin Mamitha Bakery')

@section('content')
@include('layouts.partials.admin-navbar')

<div class="container py-5">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold text-primary-custom">Daftar Kategori</h2>
            <p class="text-muted">Kelola kategori produk roti dan kue.</p>
        </div>
        <div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary-custom fw-bold shadow-sm">
                <i class="fas fa-plus me-2"></i> Tambah Kategori Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-body p-0">
            @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Kategori</th>
                                <th>Slug</th>
                                <th class="text-center">Jumlah Produk</th>
                                <th class="text-center">Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @if($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}" class="rounded-3 me-3 border" width="50" height="50" style="object-fit: cover;">
                                        @else
                                            <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                <i class="fas fa-tags text-muted"></i>
                                            </div>
                                        @endif
                                        <span class="fw-bold text-dark">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <code class="text-muted">{{ $category->slug }}</code>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-3 fw-bold">{{ $category->products_count }} produk</span>
                                </td>
                                <td class="text-center">
                                    @if($category->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">Aktif</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-warning fw-bold">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger fw-bold" {{ $category->products_count > 0 ? 'disabled' : '' }} title="{{ $category->products_count > 0 ? 'Kategori masih memiliki produk' : '' }}">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination Links --}}
                <div class="p-4 d-flex justify-content-center">
                    {{ $categories->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-tags fa-4x text-muted opacity-25 mb-3"></i>
                    <h5 class="text-muted fw-bold">Belum Ada Kategori</h5>
                    <p class="text-muted mb-0">Klik tombol "Tambah Kategori Baru" untuk memulai.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
