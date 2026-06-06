@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->invoice_number . ' - Mamitha Bakery')

@section('content')
@include('layouts.partials.customer-navbar')

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary-custom mb-1">Detail Pesanan</h2>
            <p class="text-muted mb-0"><a href="{{ route('customer.orders.index') }}" class="text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Kembali ke Riwayat</a></p>
        </div>
        <div>
            <a href="{{ route('customer.orders.invoice', $order) }}" class="btn btn-outline-danger shadow-sm">
                <i class="fas fa-file-pdf me-1"></i> Download Invoice
            </a>
        </div>
    </div>

    <!-- Tracking Pesanan -->
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4 p-md-5">
            <h5 class="fw-bold mb-4">
                Status Pesanan: {!! $order->status_badge !!}
                @if($order->order_status === 'sedang_dikirim')
                    <i class="fas fa-truck animate-truck ms-2"></i>
                @endif
            </h5>
            
            <div class="position-relative m-4">
                <div class="progress" style="height: 4px;">
                    @php
                        $progress = match($order->order_status) {
                            'menunggu_pembayaran' => 0,
                            'dibayar' => 17,
                            'diproses' => 33,
                            'sedang_dibuat' => 50,
                            'siap_diambil' => 67,
                            'sedang_dikirim' => 83,
                            'selesai' => 100,
                            'dibatalkan' => 0,
                            default => 0,
                        };
                        $bgClass = $order->order_status == 'dibatalkan' ? 'bg-danger' : 'bg-success';
                    @endphp
                    <div class="progress-bar {{ $bgClass }}" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <div class="d-flex justify-content-between position-absolute top-50 start-0 translate-middle-y w-100 px-3">
                    <!-- Step 1 -->
                    <div class="text-center" style="width: 2rem;">
                        <span class="d-inline-block rounded-circle bg-white border border-2 border-{{ $progress >= 0 && $order->order_status != 'dibatalkan' ? 'success' : 'secondary' }} d-flex align-items-center justify-content-center" style="width: 2rem; height: 2rem;">
                            <i class="fas fa-receipt text-{{ $progress >= 0 && $order->order_status != 'dibatalkan' ? 'success' : 'secondary' }} small"></i>
                        </span>
                        <div class="mt-2 text-muted small fw-semibold" style="margin-left: -20px; margin-right: -20px; font-size: 0.7rem;">
                            {{ $order->payment_method === 'cash' ? 'Menunggu Konfirmasi' : 'Menunggu Bayar' }}
                        </div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="text-center" style="width: 2rem;">
                        <span class="d-inline-block rounded-circle bg-white border border-2 border-{{ $progress >= 17 && $order->order_status != 'dibatalkan' ? 'success' : 'secondary' }} d-flex align-items-center justify-content-center" style="width: 2rem; height: 2rem;">
                            <i class="fas fa-wallet text-{{ $progress >= 17 && $order->order_status != 'dibatalkan' ? 'success' : 'secondary' }} small"></i>
                        </span>
                        <div class="mt-2 text-muted small fw-semibold" style="margin-left: -20px; margin-right: -20px; font-size: 0.7rem;">
                            {{ $order->payment_method === 'cash' ? 'Dikonfirmasi' : 'Dibayar' }}
                        </div>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="text-center" style="width: 2rem;">
                        <span class="d-inline-block rounded-circle bg-white border border-2 border-{{ $progress >= 33 && $order->order_status != 'dibatalkan' ? 'success' : 'secondary' }} d-flex align-items-center justify-content-center" style="width: 2rem; height: 2rem;">
                            <i class="fas fa-clipboard-check text-{{ $progress >= 33 && $order->order_status != 'dibatalkan' ? 'success' : 'secondary' }} small"></i>
                        </span>
                        <div class="mt-2 text-muted small fw-semibold" style="margin-left: -20px; margin-right: -20px; font-size: 0.7rem;">Diproses</div>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="text-center" style="width: 2rem;">
                        <span class="d-inline-block rounded-circle bg-white border border-2 border-{{ $progress >= 50 && $order->order_status != 'dibatalkan' ? 'success' : 'secondary' }} d-flex align-items-center justify-content-center" style="width: 2rem; height: 2rem;">
                            <i class="fas fa-bread-slice text-{{ $progress >= 50 && $order->order_status != 'dibatalkan' ? 'success' : 'secondary' }} small"></i>
                        </span>
                        <div class="mt-2 text-muted small fw-semibold" style="margin-left: -20px; margin-right: -20px; font-size: 0.7rem;">Dibuat</div>
                    </div>
                    
                    <!-- Step 5 -->
                    <div class="text-center" style="width: 2rem;">
                        <span class="d-inline-block rounded-circle bg-white border border-2 border-{{ $progress >= 67 && $order->order_status != 'dibatalkan' ? 'success' : 'secondary' }} d-flex align-items-center justify-content-center" style="width: 2rem; height: 2rem;">
                            <i class="fas fa-box text-{{ $progress >= 67 && $order->order_status != 'dibatalkan' ? 'success' : 'secondary' }} small"></i>
                        </span>
                        <div class="mt-2 text-muted small fw-semibold" style="margin-left: -20px; margin-right: -20px; font-size: 0.7rem;">Siap</div>
                    </div>

                    <!-- Step 6 -->
                    <div class="text-center" style="width: 2rem;">
                        <span class="d-inline-block rounded-circle bg-white border border-2 border-{{ $progress >= 83 && $order->order_status != 'dibatalkan' ? 'success' : 'secondary' }} d-flex align-items-center justify-content-center" style="width: 2rem; height: 2rem;">
                            <i class="fas fa-truck text-{{ $progress >= 83 && $order->order_status != 'dibatalkan' ? 'success' : 'secondary' }} small"></i>
                        </span>
                        <div class="mt-2 text-muted small fw-semibold" style="margin-left: -20px; margin-right: -20px; font-size: 0.7rem;">Dikirim</div>
                    </div>

                    <!-- Step 7 -->
                    <div class="text-center" style="width: 2rem;">
                        <span class="d-inline-block rounded-circle bg-white border border-2 border-{{ $progress == 100 && $order->order_status != 'dibatalkan' ? 'success' : 'secondary' }} d-flex align-items-center justify-content-center" style="width: 2rem; height: 2rem;">
                            <i class="fas fa-check-double text-{{ $progress == 100 && $order->order_status != 'dibatalkan' ? 'success' : 'secondary' }} small"></i>
                        </span>
                        <div class="mt-2 text-muted small fw-semibold" style="margin-left: -20px; margin-right: -20px; font-size: 0.7rem;">Selesai</div>
                    </div>
                </div>
            </div>

            @if($order->courier_name)
            <div class="mt-5 border-top pt-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-custom text-white rounded-circle p-3 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-shipping-fast fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Informasi Kurir Pengirim</h6>
                            <p class="mb-0 text-muted small">Roti pesanan Anda sedang dikirim ke alamat tujuan.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-3">
                        <div class="text-md-end">
                            <span class="d-block fw-bold text-dark fs-6">{{ $order->courier_name }}</span>
                            <span class="text-muted small">WhatsApp: {{ $order->courier_phone }}</span>
                        </div>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->courier_phone) }}" target="_blank" class="btn btn-success btn-sm rounded-3 px-3 py-2 fw-semibold">
                            <i class="fab fa-whatsapp me-2"></i> Hubungi Kurir
                        </a>
                    </div>
                </div>
                @if($order->delivery_notes)
                <div class="mt-3 bg-light p-3 rounded-3 border-start border-primary-custom border-3">
                    <small class="text-muted fw-bold d-block mb-1">Catatan Pengiriman:</small>
                    <span class="text-dark small">{{ $order->delivery_notes }}</span>
                </div>
                @endif
            </div>
            @endif
            @if($order->order_status == 'dibatalkan')
            <div class="alert alert-danger mt-5 mb-0">
                <i class="fas fa-exclamation-circle me-2"></i> Pesanan ini telah dibatalkan.
            </div>
            @endif
        </div>
    </div>

    <!-- Detail Pesanan -->
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-header bg-white border-bottom pt-4 pb-3">
                    <h5 class="fw-bold mb-0">Daftar Produk</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end pe-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->details as $detail)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $detail->product->image_url }}" alt="{{ $detail->product->name }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $detail->product->name }}</h6>
                                                @if($detail->notes)
                                                <small class="text-muted"><i class="fas fa-comment-alt me-1"></i> {{ $detail->notes }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $detail->qty }}</td>
                                    <td class="text-end pe-4 fw-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-4">TOTAL PEMBAYARAN</td>
                                    <td class="text-end fw-bold fs-5 text-primary-custom pe-4">{{ $order->formatted_total }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                <div class="card-header bg-white border-bottom pt-4 pb-3">
                    <h5 class="fw-bold mb-0">Informasi Pemesan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-muted d-block">Nomor Invoice</small>
                        <span class="fw-bold text-dark">{{ $order->invoice_number }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Tanggal Pesanan</small>
                        <span class="fw-semibold">{{ $order->order_date->format('d F Y, H:i') }} WIB</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Nama Lengkap</small>
                        <span class="fw-semibold">{{ $order->customer_name }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">No. WhatsApp</small>
                        <span class="fw-semibold">{{ $order->customer_phone }}</span>
                    </div>
                    @if($order->customer_address)
                    <div class="mb-3">
                        <small class="text-muted d-block">Alamat Pengiriman</small>
                        <span class="d-block mb-2">{{ $order->customer_address }}</span>
                        
                        @if($order->customer_lat && $order->customer_lng)
                            @php
                                $googleMapsApiKey = config('services.google_maps.api_key');
                            @endphp
                            @if(!empty($googleMapsApiKey) && $googleMapsApiKey !== 'YOUR_API_KEY_HERE')
                                <div id="mini-map" class="rounded-3 border mt-2 shadow-sm" style="height: 180px; width: 100%;"></div>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $order->customer_lat }},{{ $order->customer_lng }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 mt-2 rounded-3">
                                    <i class="fas fa-directions me-1"></i> Buka di Google Maps
                                </a>
                            @endif
                        @endif
                    </div>
                    @endif
                    @if($order->customer_notes)
                    <div class="mb-3">
                        <small class="text-muted d-block">Catatan Tambahan</small>
                        <span>{{ $order->customer_notes }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-header bg-white border-bottom pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Pembayaran</h5>
                    <button type="button" class="btn btn-link p-0 text-info" data-bs-toggle="modal" data-bs-target="#paymentHelpModal" title="Cara Membayar?" style="line-height:1; font-size: 0.9rem;">
                        <i class="fas fa-question-circle"></i> <span class="small d-none d-sm-inline">Cara Membayar?</span>
                    </button>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-muted d-block">Status Pembayaran</small>
                        <div class="mt-1">{!! $order->payment_badge !!}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Metode Pembayaran</small>
                        <span class="fw-semibold text-uppercase">
                            {{ $order->payment_method === 'cash' ? 'BAYAR DI TEMPAT (COD)' : 'ONLINE (MIDTRANS)' }}
                        </span>
                    </div>
                    
                    @if($order->payment_status == 'pending')
                        @if($order->payment_method === 'cash')
                            <div class="alert alert-info mt-3 mb-0 py-2 border-0 rounded-3 small text-center text-info bg-light">
                                <i class="fas fa-info-circle me-1"></i> Menunggu pembayaran tunai saat pengantaran.
                            </div>
                        @else
                            <div class="mt-4">
                                <button class="btn btn-primary-custom w-100 fw-bold py-2 shadow-sm" id="pay-button">
                                    BAYAR SEKARANG
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes truck-wiggle {
        0% { transform: translateY(0); }
        50% { transform: translateY(-3px) rotate(1deg); }
        100% { transform: translateY(0); }
    }
    .animate-truck {
        animation: truck-wiggle 0.5s ease-in-out infinite;
        display: inline-block;
    }
</style>
@endpush

@if($order->payment_status == 'pending' && $order->payment && $order->payment->snap_token)
    @push('scripts')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script>
            const payButton = document.getElementById('pay-button');
            if (payButton) {
                payButton.addEventListener('click', function () {
                    snap.pay('{{ $order->payment->snap_token }}', {
                        onSuccess: function (result) {
                            alert("Pembayaran berhasil!");
                            window.location.reload();
                        },
                        onPending: function (result) {
                            alert("Menunggu pembayaran Anda.");
                            window.location.reload();
                        },
                        onError: function (result) {
                            alert("Pembayaran gagal!");
                            window.location.reload();
                        },
                        onClose: function () {
                            alert('Anda menutup popup pembayaran tanpa menyelesaikan pembayaran.');
                        }
                    });
                });
            }
        </script>
    @endpush
@endif

@if($order->customer_lat && $order->customer_lng && !empty($googleMapsApiKey) && $googleMapsApiKey !== 'YOUR_API_KEY_HERE')
    @push('scripts')
        <script>
            function initMiniMap() {
                const coords = { lat: {{ $order->customer_lat }}, lng: {{ $order->customer_lng }} };
                const miniMap = new google.maps.Map(document.getElementById('mini-map'), {
                    center: coords,
                    zoom: 15,
                    mapTypeControl: false,
                    fullscreenControl: false,
                    streetViewControl: false,
                    zoomControl: false,
                });
                new google.maps.Marker({
                    position: coords,
                    map: miniMap
                });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&callback=initMiniMap" async defer></script>
    @endpush
@endif

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
                                <li>Pastikan metode pembayaran adalah <strong>ONLINE (MIDTRANS)</strong>.</li>
                                <li>Klik tombol <strong>BAYAR SEKARANG</strong>.</li>
                                <li>Anda akan diarahkan ke popup pembayaran Midtrans.</li>
                                <li>Pilih metode bayar: <strong>QRIS, Transfer Bank, GoPay, OVO, ShopeePay</strong>, dll.</li>
                                <li>Selesaikan pembayaran sesuai instruksi pada layar.</li>
                                <li>Status pembayaran akan otomatis diperbarui.</li>
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
                                <li>Pastikan metode pembayaran adalah <strong>BAYAR DI TEMPAT (COD)</strong>.</li>
                                <li>Tunggu pesanan dikonfirmasi dan diproses oleh dapur.</li>
                                <li>Pesanan akan diantarkan oleh kurir kami.</li>
                                <li>Saat kurir tiba di lokasi Anda, <strong>siapkan uang tunai</strong> pas.</li>
                                <li>Berikan uang tunai ke kurir sebagai pembayaran.</li>
                                <li>Pesanan selesai!</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-primary-custom rounded-3 fw-bold" data-bs-dismiss="modal">Mengerti, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

@endsection
