@extends('layouts.app')

@section('title', 'Checkout - Mamitha Bakery')

@section('content')
@include('layouts.partials.customer-navbar')

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary-custom">Informasi Pengiriman & Checkout</h2>
            <p class="text-muted">Lengkapi detail pengiriman Anda untuk menyelesaikan pemesanan.</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4 py-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle fs-4 me-3 text-danger"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Delivery Details Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">
                <h5 class="fw-bold text-dark mb-4"><i class="fas fa-shipping-fast text-primary-custom me-2"></i> Detail Pengiriman</h5>
                
                <form action="{{ route('customer.checkout.store') }}" method="POST">
                    @csrf
                    
                    @csrf
                    
                    <!-- Hidden Lat/Lng Inputs -->
                    <input type="hidden" name="customer_lat" id="customer_lat" value="{{ old('customer_lat') }}">
                    <input type="hidden" name="customer_lng" id="customer_lng" value="{{ old('customer_lng') }}">

                    <!-- Customer Name -->
                    <div class="mb-3">
                        <label for="customer_name" class="form-label fw-semibold text-dark">Nama Penerima</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control rounded-3 py-2 px-3 @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', $user->name) }}" required placeholder="Contoh: Rian Pratama">
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Customer Phone (WhatsApp) -->
                    <div class="mb-3">
                        <label for="customer_phone" class="form-label fw-semibold text-dark">Nomor WhatsApp / HP</label>
                        <input type="text" name="customer_phone" id="customer_phone" class="form-control rounded-3 py-2 px-3 @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone', $user->phone) }}" required placeholder="Contoh: 08123456789">
                        <small class="text-muted"><i class="fab fa-whatsapp text-success me-1"></i> Digunakan untuk konfirmasi pesanan oleh kurir/toko.</small>
                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Google Maps Address Picker -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-semibold text-dark mb-0">Pin Point Lokasi</label>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-3 px-3" id="btn-current-location">
                                <i class="fas fa-location-arrow me-1"></i> Gunakan Lokasi Saya
                            </button>
                        </div>
                        
                        <div id="map" class="rounded-3 shadow-sm border mb-2" style="height: 300px; width: 100%; z-index: 1;"></div>
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Geser penanda (marker) biru ke lokasi tepat pengiriman Anda untuk memudahkan kurir.</small>
                    </div>

                    <!-- Customer Address -->
                    <div class="mb-3">
                        <label for="customer_address" class="form-label fw-semibold text-dark">Alamat Lengkap Pengantaran</label>
                        <textarea name="customer_address" id="customer_address" rows="3" class="form-control rounded-3 py-2 px-3 @error('customer_address') is-invalid @enderror" required placeholder="Tuliskan alamat lengkap beserta patokan rumah...">{{ old('customer_address', $user->address) }}</textarea>
                        @error('customer_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Customer Notes -->
                    <div class="mb-4">
                        <label for="customer_notes" class="form-label fw-semibold text-dark">Catatan Pesanan (Opsional)</label>
                        <textarea name="customer_notes" id="customer_notes" rows="2" class="form-control rounded-3 py-2 px-3 @error('customer_notes') is-invalid @enderror" placeholder="Contoh: Titipkan di pos satpam, dll.">{{ old('customer_notes') }}</textarea>
                        @error('customer_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Payment Method Picker -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <label class="form-label fw-semibold text-dark mb-0">Metode Pembayaran</label>
                            <button type="button" class="btn btn-link p-0 text-info" data-bs-toggle="modal" data-bs-target="#paymentHelpModal" title="Cara Membayar?" style="line-height:1; font-size: 0.9rem;">
                                <i class="fas fa-question-circle"></i> <span class="small">Cara Membayar?</span>
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check card-radio p-3 border rounded-3 d-flex align-items-start position-relative" onclick="selectPaymentMethod('midtrans')">
                                    <input class="form-check-input mt-1 cursor-pointer" type="radio" name="payment_method" id="pay_midtrans" value="midtrans" {{ old('payment_method', 'midtrans') === 'midtrans' ? 'checked' : '' }} required>
                                    <label class="form-check-label ms-2 cursor-pointer w-100" for="pay_midtrans">
                                        <span class="fw-bold d-block mb-1"><i class="fas fa-credit-card text-primary-custom me-2"></i> Bayar Online</span>
                                        <small class="text-muted d-block" style="font-size: 0.8rem; line-height: 1.2;">QRIS, E-Wallet, Transfer Bank, dll (Midtrans)</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check card-radio p-3 border rounded-3 d-flex align-items-start position-relative" onclick="selectPaymentMethod('cash')">
                                    <input class="form-check-input mt-1 cursor-pointer" type="radio" name="payment_method" id="pay_cash" value="cash" {{ old('payment_method') === 'cash' ? 'checked' : '' }} required>
                                    <label class="form-check-label ms-2 cursor-pointer w-100" for="pay_cash">
                                        <span class="fw-bold d-block mb-1"><i class="fas fa-money-bill-wave text-success me-2"></i> Bayar di Tempat (COD)</span>
                                        <small class="text-muted d-block" style="font-size: 0.8rem; line-height: 1.2;">Bayar dengan uang tunai saat kurir mengantar roti</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('payment_method')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" id="btn-submit-checkout" class="btn btn-primary-custom w-100 rounded-3 py-3 fw-bold fs-5 shadow-sm">
                        <i class="fas fa-lock me-2"></i> BUAT PESANAN & BAYAR
                    </button>
                </form>
            </div>
        </div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .card-radio {
        transition: all 0.2s ease-in-out;
        border-color: #dee2e6 !important;
        cursor: pointer;
    }
    .card-radio:hover {
        border-color: #8B4513 !important;
        background-color: #fffaf0;
    }
    .card-radio.active {
        border-color: #8B4513 !important;
        background-color: #fff8dc;
        box-shadow: 0 0 0 1px #8B4513;
    }
</style>
@endpush

@push('scripts')
<script>
    function selectPaymentMethod(method) {
        const radioId = method === 'cash' ? 'pay_cash' : 'pay_midtrans';
        const radioInput = document.getElementById(radioId);
        if (radioInput) radioInput.checked = true;
        
        document.querySelectorAll('.card-radio').forEach(card => {
            card.classList.remove('active');
        });
        
        const selectedRadio = document.getElementById(radioId);
        if (selectedRadio) {
            const cardRadio = selectedRadio.closest('.card-radio');
            if (cardRadio) cardRadio.classList.add('active');
        }
        
        const btnSubmit = document.getElementById('btn-submit-checkout');
        if (btnSubmit) {
            if (method === 'cash') {
                btnSubmit.innerHTML = '<i class="fas fa-money-bill-wave me-2"></i> BUAT PESANAN (BAYAR DI TEMPAT)';
            } else {
                btnSubmit.innerHTML = '<i class="fas fa-lock me-2"></i> BUAT PESANAN & BAYAR';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checkedInput = document.querySelector('input[name="payment_method"]:checked');
        if (checkedInput) {
            selectPaymentMethod(checkedInput.value);
        }
    });
</script>

{{-- Payment Help Modal --}}
<div class="modal fade" id="paymentHelpModal" tabindex="-1" aria-labelledby="paymentHelpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-primary-custom" id="paymentHelpModalLabel">
                    <i class="fas fa-credit-card me-2"></i>Panduan Cara Membayar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="row g-4">
                    {{-- Online Payment --}}
                    <div class="col-md-6">
                        <div class="border rounded-4 p-4 h-100">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px;">
                                    <i class="fas fa-credit-card text-primary"></i>
                                </div>
                                <h6 class="fw-bold mb-0">Bayar Online (Midtrans)</h6>
                            </div>
                            <ol class="text-muted small ps-3 mb-0" style="line-height:1.9;">
                                <li>Pilih metode <strong>Bayar Online</strong> lalu klik <strong>Buat Pesanan &amp; Bayar</strong>.</li>
                                <li>Anda akan diarahkan ke popup pembayaran Midtrans.</li>
                                <li>Pilih metode bayar: <strong>QRIS, Transfer Bank, GoPay, OVO, ShopeePay</strong>, dll.</li>
                                <li>Selesaikan pembayaran sesuai instruksi.</li>
                                <li>Pesanan otomatis dikonfirmasi setelah pembayaran berhasil.</li>
                            </ol>
                        </div>
                    </div>
                    {{-- COD Payment --}}
                    <div class="col-md-6">
                        <div class="border rounded-4 p-4 h-100">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px;">
                                    <i class="fas fa-money-bill-wave text-success"></i>
                                </div>
                                <h6 class="fw-bold mb-0">Bayar di Tempat (COD)</h6>
                            </div>
                            <ol class="text-muted small ps-3 mb-0" style="line-height:1.9;">
                                <li>Pilih metode <strong>Bayar di Tempat (COD)</strong>.</li>
                                <li>Klik <strong>Buat Pesanan (Bayar di Tempat)</strong>.</li>
                                <li>Pesanan akan segera diproses tanpa perlu bayar dulu.</li>
                                <li>Saat kurir tiba di lokasi Anda, <strong>siapkan uang tunai</strong> senilai total tagihan.</li>
                                <li>Berikan uang tunai ke kurir, dan pesanan selesai!</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info border-0 rounded-3 mt-4 small mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Pastikan alamat pengiriman Anda sudah benar sebelum melanjutkan pembayaran.
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-primary-custom rounded-3 fw-bold" data-bs-dismiss="modal">Mengerti, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map, marker;
    const defaultLat = -6.2088;
    const defaultLng = 106.8456;

    document.addEventListener('DOMContentLoaded', function() {
        const latInput = document.getElementById('customer_lat');
        const lngInput = document.getElementById('customer_lng');
        const initialLat = parseFloat(latInput.value) || defaultLat;
        const initialLng = parseFloat(lngInput.value) || defaultLng;

        map = L.map('map').setView([initialLat, initialLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);

        marker.on('dragend', function(event) {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
            geocodePosition(position.lat, position.lng);
        });

        if (!latInput.value || !lngInput.value) {
            updateCoordinates(initialLat, initialLng);
        }

        document.getElementById('btn-current-location').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    map.setView([lat, lng], 15);
                    marker.setLatLng([lat, lng]);
                    updateCoordinates(lat, lng);
                    geocodePosition(lat, lng);
                }, function() {
                    alert('Gagal mendapatkan lokasi Anda. Pastikan izin lokasi diaktifkan.');
                });
            } else {
                alert('Browser Anda tidak mendukung deteksi lokasi.');
            }
        });
    });

    function updateCoordinates(lat, lng) {
        document.getElementById('customer_lat').value = lat;
        document.getElementById('customer_lng').value = lng;
    }

    function geocodePosition(lat, lng) {
        // Reverse geocoding using Nominatim (OpenStreetMap)
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    document.getElementById('customer_address').value = data.display_name;
                }
            })
            .catch(error => {
                console.error('Reverse Geocoding failed: ', error);
            });
    }
</script>
@endpush

        <!-- Order Breakdown -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 sticky-top" style="top: 100px; z-index: 10;">
                <h5 class="fw-bold text-dark mb-4">Ringkasan Pesanan</h5>

                <!-- Cart Items List -->
                <div class="overflow-y-auto mb-4" style="max-height: 250px;">
                    @foreach($cartItems as $item)
                        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                            <div class="d-flex align-items-center" style="max-width: 70%;">
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="rounded-2 me-2" style="width: 45px; height: 45px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-0 text-dark fw-bold text-truncate" title="{{ $item->product->name }}">{{ $item->product->name }}</h6>
                                    <small class="text-muted">{{ $item->qty }} x {{ $item->product->formatted_price }}</small>
                                    @if($item->notes)
                                        <div class="small text-muted text-truncate" style="font-size: 0.75rem;"><i class="fas fa-comment-dots"></i> {{ $item->notes }}</div>
                                    @endif
                                </div>
                            </div>
                            <span class="fw-bold text-dark text-end">
                                Rp {{ number_format($item->qty * $item->product->price, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Price summary -->
                <div class="d-flex justify-content-between mb-3 text-muted">
                    <span>Subtotal Produk</span>
                    <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 text-muted">
                    <span>Biaya Layanan</span>
                    <span>Gratis</span>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold text-dark fs-5">Total Pembayaran</span>
                    <span class="fw-bold text-primary-custom fs-4">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
