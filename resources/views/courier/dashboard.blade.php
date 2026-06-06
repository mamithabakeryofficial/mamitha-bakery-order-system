@extends('layouts.app')

@section('title', 'Dashboard Kurir - Mamitha Bakery')

@section('content')
@include('layouts.partials.courier-navbar')

<div class="container py-4">
    {{-- Welcome Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 bg-primary text-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="me-3 d-none d-sm-block">
                        <i class="fas fa-user-circle fa-3x"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">Selamat Bekerja, {{ auth()->user()->name }}!</h4>
                        <p class="mb-0 text-white-50">Kelola pengiriman roti Mamitha dengan cepat dan tepat. Selalu utamakan keselamatan berkendara.</p>
                    </div>
                </div>
            </div>
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
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tabs Navigation --}}
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-pills nav-fill bg-white p-2 rounded-3 shadow-sm" id="deliveryTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold py-2.5 rounded-3 d-flex align-items-center justify-content-center" id="active-tab" data-bs-toggle="tab" data-bs-target="#active-deliveries" type="button" role="tab">
                        <i class="fas fa-truck me-2"></i> Pengiriman Aktif
                        <span class="badge bg-danger ms-2">{{ $activeDeliveries->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-2.5 rounded-3 d-flex align-items-center justify-content-center text-secondary" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-deliveries" type="button" role="tab">
                        <i class="fas fa-history me-2"></i> Riwayat Hari Ini
                        <span class="badge bg-secondary ms-2">{{ $completedDeliveries->count() }}</span>
                    </button>
                </li>
            </ul>
        </div>
    </div>

    {{-- Tabs Content --}}
    <div class="tab-content" id="deliveryTabsContent">
        {{-- Active Deliveries Tab --}}
        <div class="tab-pane fade show active" id="active-deliveries" role="tabpanel">
            @if($activeDeliveries->isEmpty())
                <div class="card border shadow-sm rounded-3 text-center p-5 bg-white">
                    <div class="card-body">
                        <div class="mb-4">
                            <i class="fas fa-clipboard-check text-muted fa-4x"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Tidak Ada Pengiriman Aktif</h5>
                        <p class="text-muted">Hubungi Admin jika Anda merasa ada pesanan yang seharusnya diantarkan.</p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach($activeDeliveries as $order)
                        <div class="col-lg-6">
                            <div class="card border shadow-sm rounded-3 bg-white overflow-hidden h-100">
                                {{-- Card Header --}}
                                <div class="card-header border-0 bg-light py-3 px-4 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold text-primary">{{ $order->invoice_number }}</span>
                                        <div class="small text-muted">{{ $order->order_date->format('d M Y') }}</div>
                                    </div>
                                    <div>
                                        {!! $order->payment_badge !!}
                                    </div>
                                </div>

                                {{-- Card Body --}}
                                <div class="card-body p-4">
                                    {{-- Customer Information --}}
                                    <div class="mb-4">
                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                            <i class="fas fa-user me-2 text-primary"></i>Informasi Pelanggan
                                        </h6>
                                        <div class="row g-2 mb-3">
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block">Nama Pelanggan</small>
                                                <strong class="text-dark">{{ $order->user->name ?? $order->customer_name }}</strong>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted d-block">Telepon / WhatsApp</small>
                                                <div class="d-flex align-items-center">
                                                    <strong class="text-dark me-2">{{ $order->customer_phone }}</strong>
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" class="btn btn-sm btn-success rounded-circle shadow-sm" style="width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center;">
                                                        <i class="fab fa-whatsapp small"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <small class="text-muted d-block">Alamat Pengiriman</small>
                                                <span class="text-dark text-wrap">{{ $order->customer_address }}</span>
                                            </div>
                                            @if($order->customer_notes)
                                                <div class="col-12 mt-2">
                                                    <small class="text-muted d-block">Catatan Pelanggan</small>
                                                    <div class="bg-light p-2 rounded text-dark border small">
                                                        <i class="fas fa-sticky-note me-1 text-warning"></i> {{ $order->customer_notes }}
                                                    </div>
                                                </div>
                                            @endif
                                            @if($order->delivery_notes)
                                                <div class="col-12 mt-2">
                                                    <small class="text-muted d-block">Catatan Admin untuk Kurir</small>
                                                    <div class="bg-warning-subtle p-2 rounded text-dark border border-warning small">
                                                        <i class="fas fa-info-circle me-1 text-primary"></i> {{ $order->delivery_notes }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Google Maps View --}}
                                        @if($order->customer_lat && $order->customer_lng)
                                            <div class="mb-3">
                                                <small class="text-muted d-block mb-1">Rute Lokasi</small>
                                                <div id="map-{{ $order->id }}" class="rounded-3 border shadow-sm" style="height: 180px; width: 100%;"></div>
                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $order->customer_lat }},{{ $order->customer_lng }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2 w-100 rounded-3">
                                                    <i class="fas fa-directions me-1"></i> Buka Navigasi Google Maps
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Order Items --}}
                                    <div class="mb-4">
                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-2">
                                            <i class="fas fa-shopping-basket me-2 text-primary"></i>Daftar Roti
                                        </h6>
                                        <ul class="list-group list-group-flush small">
                                            @foreach($order->details as $detail)
                                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent py-2">
                                                    <span>{{ $detail->product->name ?? 'Produk Terhapus' }} <strong class="text-primary-custom ms-1">x{{ $detail->qty }}</strong></span>
                                                    <span class="fw-semibold text-dark">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                                </li>
                                            @endforeach
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent py-2 border-0 fw-bold fs-6">
                                                <span>Total Tagihan:</span>
                                                <span class="text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                            </li>
                                        </ul>
                                    </div>

                                    {{-- COD Check / Payment Status Warning --}}
                                    @if($order->payment_method === 'cash')
                                        <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-4 small d-flex align-items-start">
                                            <i class="fas fa-money-bill-wave text-warning fs-5 me-2 mt-1"></i>
                                            <div>
                                                <strong class="text-dark d-block mb-1">METODE PEMBAYARAN TUNAI (COD)</strong>
                                                Tagih uang tunai sebesar <strong class="text-danger">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong> kepada pelanggan sebelum menyerahkan pesanan.
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 small d-flex align-items-start">
                                            <i class="fas fa-check-double text-success fs-5 me-2 mt-1"></i>
                                            <div>
                                                <strong class="text-dark d-block mb-1">METODE PEMBAYARAN ONLINE (LUNAS)</strong>
                                                Pembayaran telah dilunasi secara online melalui Midtrans. Cukup antarkan roti dan serahkan langsung ke pelanggan.
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Submit Actions --}}
                                    <form action="{{ route('courier.orders.complete', $order->id) }}" method="POST" onsubmit="return confirmComplete('{{ $order->payment_method }}', '{{ number_format($order->total_price, 0, ',', '.') }}')">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100 fw-bold py-2.5 rounded-3 shadow-sm">
                                            <i class="fas fa-check-circle me-1"></i> Selesaikan Pengiriman
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Completed Deliveries Tab --}}
        <div class="tab-pane fade" id="history-deliveries" role="tabpanel">
            @if($completedDeliveries->isEmpty())
                <div class="card border shadow-sm rounded-3 text-center p-5 bg-white">
                    <div class="card-body">
                        <div class="mb-4">
                            <i class="fas fa-history text-muted fa-4x"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Belum Ada Riwayat Selesai Hari Ini</h5>
                        <p class="text-muted">Setiap pengiriman yang Anda selesaikan hari ini akan muncul di sini.</p>
                    </div>
                </div>
            @else
                <div class="card border shadow-sm rounded-3 bg-white">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">No. Invoice</th>
                                        <th>Pelanggan</th>
                                        <th>Alamat</th>
                                        <th>Total Pembayaran</th>
                                        <th>Metode</th>
                                        <th class="pe-4">Waktu Selesai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedDeliveries as $order)
                                        <tr>
                                            <td class="ps-4 fw-semibold text-primary">{{ $order->invoice_number }}</td>
                                            <td>{{ $order->user->name ?? $order->customer_name }}</td>
                                            <td class="text-truncate" style="max-width: 250px;">{{ $order->customer_address }}</td>
                                            <td class="fw-semibold text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge {{ $order->payment_method === 'cash' ? 'bg-secondary' : 'bg-success' }}">
                                                    {{ strtoupper($order->payment_method) }}
                                                </span>
                                            </td>
                                            <td class="pe-4 text-muted small">{{ $order->updated_at->format('H:i') }} WIB</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmComplete(paymentMethod, amount) {
        if (paymentMethod === 'cash') {
            return confirm(`APAKAH ANDA SUDAH MENERIMA UANG TUNAI?\n\nHarap pastikan Anda telah menerima pembayaran tunai sebesar Rp ${amount} sebelum menyelesaikan pengiriman ini.`);
        }
        return confirm("Apakah Anda yakin ingin menandai pengiriman ini sebagai SELESAI?");
    }
</script>

{{-- Load Google Maps for active orders containing coordinates --}}
@php
    $googleMapsApiKey = config('services.google_maps.api_key');
    $hasCoords = $activeDeliveries->contains(fn($o) => !empty($o->customer_lat) && !empty($o->customer_lng));
@endphp

@if($hasCoords && !empty($googleMapsApiKey) && $googleMapsApiKey !== 'YOUR_API_KEY_HERE')
    <script>
        function initCourierMaps() {
            @foreach($activeDeliveries as $order)
                @if($order->customer_lat && $order->customer_lng)
                    (function() {
                        const coords = { lat: {{ $order->customer_lat }}, lng: {{ $order->customer_lng }} };
                        const mapEl = document.getElementById('map-{{ $order->id }}');
                        if (mapEl) {
                            const map = new google.maps.Map(mapEl, {
                                center: coords,
                                zoom: 15,
                                mapTypeControl: false,
                                streetViewControl: false,
                                fullscreenControl: true
                            });
                            new google.maps.Marker({
                                position: coords,
                                map: map,
                                title: "{{ $order->customer_name }}"
                            });
                        }
                    })();
                @endif
            @endforeach
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&callback=initCourierMaps" async defer></script>
@endif
@endpush
@endsection
