@extends('layouts.app')

@section('title', 'Keranjang Belanja - Mamitha Bakery')

@section('content')
@include('layouts.partials.customer-navbar')

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary-custom">Keranjang Belanja</h2>
            <p class="text-muted">Kelola produk pilihan Anda sebelum melakukan pembayaran.</p>
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
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4 py-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle fs-4 me-3 text-danger"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($cartItems->count() > 0)
        <div class="row g-4">
            <!-- Cart Items List -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3">Produk</th>
                                        <th class="text-center">Kuantitas & Catatan</th>
                                        <th class="text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr class="border-bottom">
                                            <!-- Product Info -->
                                            <td class="ps-4 py-4" style="min-width: 250px;">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="rounded-3 me-3" style="width: 70px; height: 70px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold text-dark">{{ $item->product->name }}</h6>
                                                        <span class="text-muted d-block small mb-2">{{ $item->product->category->name }}</span>
                                                        <span class="fw-semibold text-primary-custom">{{ $item->product->formatted_price }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Qty & Notes form -->
                                            <td class="py-4 text-center" style="min-width: 280px;">
                                                <form action="{{ route('customer.cart.update') }}" method="POST" class="d-flex flex-column align-items-center gap-2">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    
                                                    <!-- Qty selector + Save -->
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="input-group input-group-sm" style="width: 110px;">
                                                            <button class="btn btn-outline-secondary" type="button" onclick="changeQty(this, -1)">-</button>
                                                            <input type="number" name="qty" class="form-control text-center fw-bold qty-field" value="{{ $item->qty }}" min="1">
                                                            <button class="btn btn-outline-secondary" type="button" onclick="changeQty(this, 1)">+</button>
                                                        </div>
                                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-3 px-3" title="Simpan Perubahan">
                                                            <i class="fas fa-save me-1"></i> Simpan
                                                        </button>
                                                    </div>

                                                    <!-- Notes -->
                                                    <div class="w-70 px-2">
                                                        <input type="text" name="notes" class="form-control form-control-sm text-center border-0 bg-light rounded-3 small" placeholder="Tambah catatan..." value="{{ $item->notes }}">
                                                    </div>
                                                </form>

                                                <!-- Delete button -->
                                                <form action="{{ route('customer.cart.remove') }}" method="POST" class="mt-2">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <button type="submit" class="btn btn-link text-danger text-decoration-none p-0 small">
                                                        <i class="fas fa-trash-alt me-1"></i> Hapus dari Keranjang
                                                    </button>
                                                </form>
                                            </td>

                                            <!-- Subtotal -->
                                            <td class="text-end pe-4 py-4 fw-bold text-dark fs-5" style="min-width: 120px;">
                                                Rp {{ number_format($item->qty * $item->product->price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Clear Cart Action -->
                <div class="d-flex justify-content-between align-items-center px-2">
                    <a href="{{ route('customer.products.index') }}" class="btn btn-link text-primary-custom text-decoration-none fw-semibold">
                        <i class="fas fa-arrow-left me-2"></i> Tambah Produk Lain
                    </a>
                    <form action="{{ route('customer.cart.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan keranjang belanja?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 px-3">
                            <i class="fas fa-trash me-2"></i> Kosongkan Keranjang
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 sticky-top" style="top: 100px; z-index: 10;">
                    <h5 class="fw-bold text-dark mb-4">Ringkasan Belanja</h5>
                    
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Total Barang</span>
                        <span class="fw-semibold">{{ $cartItems->sum('qty') }} pcs</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Subtotal Produk</span>
                        <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold text-dark fs-5">Total Harga</span>
                        <span class="fw-bold text-primary-custom fs-4">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('customer.checkout.index') }}" class="btn btn-primary-custom w-100 rounded-3 py-3 fw-bold fs-5 shadow-sm">
                        Lanjutkan ke Checkout <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-white py-5">
                    <div class="card-body text-center py-5">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png" alt="Keranjang Kosong" width="250" class="mb-4 opacity-75">
                        <h4 class="fw-bold text-muted mb-2">Keranjang Belanja Kosong</h4>
                        <p class="text-muted mb-4">Anda belum menambahkan produk apapun ke keranjang belanja.</p>
                        <a href="{{ route('customer.products.index') }}" class="btn btn-primary-custom px-5 py-3 rounded-pill fw-bold shadow-sm">
                            Mulai Belanja Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function changeQty(btn, val) {
        var input = btn.parentNode.querySelector('.qty-field');
        var cur = parseInt(input.value);
        if (!isNaN(cur)) {
            var newVal = cur + val;
            if (newVal >= 1) {
                input.value = newVal;
            }
        }
    }
</script>

<style>
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
