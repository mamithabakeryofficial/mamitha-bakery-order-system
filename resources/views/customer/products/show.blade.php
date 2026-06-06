@extends('layouts.app')

@section('title', $product->name . ' - Mamitha Bakery')

@section('content')
@include('layouts.partials.customer-navbar')

<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('customer.products.index') }}" class="text-decoration-none text-primary-custom fw-semibold">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Katalog
        </a>
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

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-5">
        <div class="card-body p-4 p-md-5">
            <div class="row g-5">
                <!-- Product Image -->
                <div class="col-md-6 col-lg-5">
                    <div class="rounded-4 overflow-hidden border bg-light shadow-sm" style="aspect-ratio: 1; max-height: 450px;">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                    </div>
                </div>

                <!-- Product Info & Actions -->
                <div class="col-md-6 col-lg-7 d-flex flex-column justify-content-between">
                    <div>
                        <span class="badge bg-primary-custom mb-3 px-3 py-2 fs-7 rounded-pill">{{ $product->category->name }}</span>
                        <h2 class="fw-bold text-dark mb-2">{{ $product->name }}</h2>
                        <h3 class="fw-bold text-primary-custom mb-4">{{ $product->formatted_price }}</h3>
                        
                        <h5 class="fw-semibold text-dark mb-2">Deskripsi Produk</h5>
                        <p class="text-muted leading-relaxed mb-4">
                            {{ $product->description ?? 'Nikmati kelezatan produk khas Mamitha Bakery yang dipanggang dengan penuh kasih sayang menggunakan resep legendaris dan bahan-bahan pilihan tanpa bahan pengawet.' }}
                        </p>
                    </div>

                    <!-- Add to Cart Form -->
                    <form action="{{ route('customer.cart.add') }}" method="POST" class="mt-4 border-top pt-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="row g-3 mb-4">
                            <!-- Qty Selector -->
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-bold text-dark">Jumlah Pembelian</label>
                                <div class="input-group" style="width: 150px;">
                                    <button class="btn btn-outline-secondary" type="button" onclick="decrementQty()">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" id="qty-input" name="qty" class="form-control text-center fw-bold shadow-none" value="1" min="1">
                                    <button class="btn btn-outline-secondary" type="button" onclick="incrementQty()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Notes Field -->
                            <div class="col-12">
                                <label for="notes" class="form-label fw-bold text-dark">Catatan untuk Penjual (Opsional)</label>
                                <input type="text" name="notes" id="notes" class="form-control rounded-3 py-2 px-3 shadow-none border" placeholder="Contoh: Kurangi manis, tulisan 'Happy Birthday', dll.">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 rounded-3 py-3 fw-bold fs-5 shadow-sm transition-all duration-200">
                            <i class="fas fa-shopping-cart me-2"></i> Tambah ke Keranjang Belanja
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div>
        <h4 class="fw-bold text-dark mb-4">Rekomendasi Lainnya</h4>
        <div class="row g-4">
            @foreach($relatedProducts as $related)
                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white product-card-sm" style="transition: transform 0.2s;">
                        <a href="{{ route('customer.products.show', $related) }}" class="text-decoration-none">
                            <div class="position-relative" style="padding-top: 100%;">
                                <img src="{{ $related->image_url }}" alt="{{ $related->name }}" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
                            </div>
                            <div class="card-body p-3">
                                <h6 class="card-title fw-bold text-dark text-truncate mb-1" title="{{ $related->name }}">{{ $related->name }}</h6>
                                <span class="fw-semibold text-primary-custom text-sm d-block">{{ $related->formatted_price }}</span>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    function incrementQty() {
        var input = document.getElementById('qty-input');
        input.value = parseInt(input.value) + 1;
    }
    
    function decrementQty() {
        var input = document.getElementById('qty-input');
        var val = parseInt(input.value);
        if (val > 1) {
            input.value = val - 1;
        }
    }
</script>

<style>
    .product-card-sm:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(139, 69, 19, 0.08) !important;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@endsection
