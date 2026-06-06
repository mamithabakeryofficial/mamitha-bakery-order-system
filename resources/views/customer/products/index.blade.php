@extends('layouts.app')

@section('title', 'Katalog Produk - Mamitha Bakery')

@section('content')
@include('layouts.partials.customer-navbar')

<div class="container py-5">
    <!-- Header Section with Search -->
    <div class="row align-items-center mb-5 g-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-primary-custom mb-1">Pilihan Menu Lezat</h2>
            <p class="text-muted mb-0">Nikmati roti dan kue segar kualitas premium langsung dari oven kami.</p>
        </div>
        <div class="col-md-6">
            <form action="{{ route('customer.products.index') }}" method="GET">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white border">
                    <span class="input-group-text bg-white border-0 ps-4">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-0 py-3 ps-2 shadow-none" placeholder="Cari roti cokelat, kue tar, pastry..." value="{{ request('search') }}">
                    <button class="btn btn-primary-custom px-4 border-0" type="submit">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Pills -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2 justify-content-start align-items-center">
                <a href="{{ route('customer.products.index', ['search' => request('search')]) }}" 
                   class="btn btn-sm rounded-pill px-4 py-2 border-0 shadow-sm fw-semibold {{ !request('category') ? 'btn-primary-custom' : 'btn-white bg-white text-primary-custom' }}">
                   Semua Menu
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('customer.products.index', ['category' => $category->slug, 'search' => request('search')]) }}" 
                       class="btn btn-sm rounded-pill px-4 py-2 border-0 shadow-sm fw-semibold {{ request('category') === $category->slug ? 'btn-primary-custom' : 'btn-white bg-white text-primary-custom' }}">
                       {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4 py-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fs-4 me-3 text-success"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Product Grid -->
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white product-card transition-all" style="transition: transform 0.2s, box-shadow 0.2s;">
                    <!-- Image with link -->
                    <a href="{{ route('customer.products.show', $product) }}" class="text-decoration-none">
                        <div class="position-relative overflow-hidden" style="padding-top: 100%;">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                 class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover transition-all duration-300 product-image">
                            <span class="position-absolute top-0 end-0 bg-primary-custom text-white px-3 py-1 rounded-bl-4 small fw-semibold shadow-sm" style="border-bottom-left-radius: 12px;">
                                {{ $product->category->name }}
                            </span>
                        </div>
                    </a>

                    <!-- Card Body -->
                    <div class="card-body p-4 d-flex flex-column">
                        <a href="{{ route('customer.products.show', $product) }}" class="text-decoration-none">
                            <h5 class="card-title fw-bold text-dark mb-1 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h5>
                        </a>
                        <p class="card-text text-muted small mb-3 flex-grow-1 text-truncate-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 38px;">
                            {{ $product->description ?? 'Nikmati kelembutan roti khas Mamitha Bakery yang dibuat dengan bahan berkualitas tinggi.' }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="fs-5 fw-bold text-primary-custom">{{ $product->formatted_price }}</span>
                        </div>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="card-footer bg-white border-0 pt-0 pb-4 px-4">
                        <form action="{{ route('customer.cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" class="btn btn-primary-custom w-100 rounded-3 py-2 fw-semibold d-flex align-items-center justify-content-center">
                                <i class="fas fa-shopping-basket me-2"></i> Tambah
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="py-5">
                    <i class="fas fa-cookie-bite fa-4x text-muted mb-4 opacity-50"></i>
                    <h4 class="text-muted fw-bold">Menu tidak ditemukan</h4>
                    <p class="text-muted">Coba ubah kata kunci pencarian atau kategori Anda.</p>
                    <a href="{{ route('customer.products.index') }}" class="btn btn-primary-custom mt-3 px-4 py-2 rounded-pill">Reset Pencarian</a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(139, 69, 19, 0.1) !important;
    }
    .product-card:hover .product-image {
        transform: scale(1.08);
    }
</style>
@endsection
