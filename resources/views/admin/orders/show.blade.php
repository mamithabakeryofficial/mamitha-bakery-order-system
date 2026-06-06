@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->invoice_number . ' - Admin Mamitha Bakery')

@section('content')
@include('layouts.partials.admin-navbar')

<div class="container py-5">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-lg-10 mx-auto">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light border mb-3">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Pesanan
            </a>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h3 class="fw-bold text-primary-custom">Pesanan #{{ $order->invoice_number }}</h3>
                    <p class="text-muted mb-0">Dipesan pada tanggal {{ $order->order_date->format('d M Y') }}</p>
                </div>
                <div class="mt-3 mt-md-0 d-flex gap-2">
                    {!! $order->payment_badge !!}
                    {!! $order->status_badge !!}
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="row mb-4">
            <div class="col-lg-10 mx-auto">
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-10 mx-auto">
            <div class="row g-4">
                {{-- Left: Order Details & Products --}}
                <div class="col-md-8">
                    {{-- Products Table --}}
                    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                            <h5 class="fw-bold mb-0"><i class="fas fa-cookie-bite me-2 text-primary-custom"></i>Rincian Produk</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Produk</th>
                                            <th class="text-center">Harga</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->details as $detail)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $detail->product->image_url }}" class="rounded border me-2" width="45" height="45" style="object-fit:cover;">
                                                    <div>
                                                        <h6 class="fw-bold mb-0 text-dark" style="font-size:0.9rem;">{{ $detail->product->name ?? 'Produk Terhapus' }}</h6>
                                                        @if($detail->notes)
                                                            <small class="text-muted"><i class="fas fa-comment-alt me-1"></i>Catatan: {{ $detail->notes }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center text-muted" style="font-size:0.9rem;">
                                                Rp {{ number_format($detail->price, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->qty }}
                                            </td>
                                            <td class="text-end fw-semibold text-dark">
                                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr class="border-0">
                                            <td colspan="3" class="text-end fw-bold pt-3 fs-5">Total Harga:</td>
                                            <td class="text-end fw-bold text-success pt-3 fs-5">
                                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Customer Details --}}
                    <div class="card border-0 shadow-sm rounded-4 bg-white">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                            <h5 class="fw-bold mb-0"><i class="fas fa-user-tag me-2 text-primary-custom"></i>Informasi Pelanggan</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">Nama Pelanggan</small>
                                    <span class="fw-semibold text-dark">{{ $order->user->name ?? $order->customer_name }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">Nomor Telepon</small>
                                    <span class="fw-semibold text-dark">{{ $order->customer_phone }}</span>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted d-block">Alamat Pengiriman / Pengambilan</small>
                                    <span class="text-dark">{{ $order->customer_address ?? 'Ambil di Toko' }}</span>
                                </div>
                                @if($order->customer_lat && $order->customer_lng)
                                    @php
                                        $googleMapsApiKey = config('services.google_maps.api_key');
                                    @endphp
                                    @if(!empty($googleMapsApiKey) && $googleMapsApiKey !== 'YOUR_API_KEY_HERE')
                                        <div class="col-12 mt-2">
                                            <small class="text-muted d-block mb-2">Koordinat Lokasi Pengiriman</small>
                                            <div id="admin-map" class="rounded-3 border shadow-sm" style="height: 250px; width: 100%;"></div>
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $order->customer_lat }},{{ $order->customer_lng }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2 rounded-3">
                                                <i class="fas fa-external-link-alt me-1"></i> Buka di Google Maps
                                            </a>
                                        </div>
                                    @endif
                                @endif
                                @if($order->customer_notes)
                                <div class="col-12">
                                    <small class="text-muted d-block">Catatan Tambahan Pelanggan</small>
                                    <div class="alert alert-light border py-2 px-3 mt-1 mb-0 small">
                                        <i class="fas fa-sticky-note me-1"></i> {{ $order->customer_notes }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Update Status & Payment Info --}}
                <div class="col-md-4">
                    {{-- Status Update Card --}}
                    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                            <h5 class="fw-bold mb-0"><i class="fas fa-cog me-2 text-primary-custom"></i>Kelola Status</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="order_status" class="form-label fw-semibold text-muted small">Status Pesanan</label>
                                    <select name="order_status" id="order_status" class="form-select">
                                        <option value="menunggu_pembayaran" {{ $order->order_status === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                        <option value="dibayar" {{ $order->order_status === 'dibayar' ? 'selected' : '' }}>Pembayaran Berhasil</option>
                                        <option value="diproses" {{ $order->order_status === 'diproses' ? 'selected' : '' }}>Diproses Admin (Terima)</option>
                                        <option value="sedang_dibuat" {{ $order->order_status === 'sedang_dibuat' ? 'selected' : '' }}>Sedang Diproduksi (Dapur)</option>
                                        <option value="siap_diambil" {{ $order->order_status === 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                                        <option value="sedang_dikirim" {{ $order->order_status === 'sedang_dikirim' ? 'selected' : '' }}>Sedang Dikirim (Kurir)</option>
                                        <option value="selesai" {{ $order->order_status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="dibatalkan" {{ $order->order_status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary-custom w-100 fw-bold py-2 rounded-3">
                                    <i class="fas fa-save me-1"></i> Perbarui Status
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($order->order_status === 'siap_diambil' || $order->order_status === 'sedang_dikirim')
                    {{-- Courier Card --}}
                    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-shipping-fast me-2 text-primary-custom"></i>
                                {{ $order->order_status === 'sedang_dikirim' ? 'Detail Pengiriman' : 'Assign Kurir Pengirim' }}
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @if($order->order_status === 'sedang_dikirim')
                                <div class="bg-light p-3 rounded-3 mb-3 border small">
                                    <div class="mb-2"><strong>Nama Kurir:</strong> {{ $order->courier_name }}</div>
                                    <div class="mb-2"><strong>No. HP:</strong> {{ $order->courier_phone }}</div>
                                    @if($order->delivery_notes)
                                        <div><strong>Catatan:</strong> {{ $order->delivery_notes }}</div>
                                    @endif
                                </div>
                            @endif

                            <form action="{{ route('admin.orders.assignCourier', $order->id) }}" method="POST">
                                @csrf
                                @if(isset($couriers) && $couriers->count() > 0)
                                <div class="mb-3">
                                    <label for="courier_select" class="form-label fw-semibold text-muted small">Pilih Kurir Terdaftar</label>
                                    <select id="courier_select" class="form-select">
                                        <option value="">-- Pilih atau isi manual di bawah --</option>
                                        @foreach($couriers as $courier)
                                            <option value="{{ $courier->id }}" data-name="{{ $courier->name }}" data-phone="{{ $courier->phone }}">
                                                {{ $courier->name }} ({{ $courier->phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <hr class="my-3">
                                @endif
                                <div class="mb-3">
                                    <label for="courier_name" class="form-label fw-semibold text-muted small">Nama Kurir</label>
                                    <input type="text" name="courier_name" id="courier_name" class="form-control" value="{{ old('courier_name', $order->courier_name) }}" required placeholder="Contoh: Budi Santoso">
                                </div>
                                <div class="mb-3">
                                    <label for="courier_phone" class="form-label fw-semibold text-muted small">Nomor WhatsApp Kurir</label>
                                    <input type="text" name="courier_phone" id="courier_phone" class="form-control" value="{{ old('courier_phone', $order->courier_phone) }}" required placeholder="Contoh: 08123456789">
                                </div>
                                <div class="mb-3">
                                    <label for="delivery_notes" class="form-label fw-semibold text-muted small">Catatan Pengiriman (Opsional)</label>
                                    <textarea name="delivery_notes" id="delivery_notes" class="form-control" rows="2" placeholder="Contoh: Hubungi jika sudah sampai gerbang depan">{{ old('delivery_notes', $order->delivery_notes) }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-warning w-100 fw-bold py-2 rounded-3 text-white">
                                    <i class="fas fa-truck me-1"></i> {{ $order->order_status === 'sedang_dikirim' ? 'Perbarui Info Kurir' : 'Kirim Pesanan (Sedang Dikirim)' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    {{-- Payment Details Card --}}
                    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                            <h5 class="fw-bold mb-0"><i class="fas fa-credit-card me-2 text-primary-custom"></i>Informasi Bayar</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <small class="text-muted d-block">Status Pembayaran</small>
                                <span class="fw-bold text-dark fs-6">{{ strtoupper($order->payment_status) }}</span>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Metode Pembayaran</small>
                                <span class="fw-semibold text-dark">{{ $order->payment_method === 'cash' ? 'BAYAR DI TEMPAT (COD)' : 'ONLINE (MIDTRANS)' }}</span>
                            </div>
                            
                            @if($order->payment_method === 'cash' && $order->payment_status === 'pending')
                                <div class="alert alert-warning border-0 rounded-3 mb-3 small">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Pelanggan memilih pembayaran cash saat pengiriman.
                                </div>
                                <form action="{{ route('admin.orders.confirmCash', $order->id) }}" method="POST" class="mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 fw-bold py-2 rounded-3 shadow-sm" onclick="return confirm('Konfirmasi bahwa pembayaran cash sebesar Rp {{ number_format($order->total_price, 0, ',', '.') }} telah diterima?')">
                                        <i class="fas fa-check-circle me-1"></i> Konfirmasi Pembayaran Cash
                                    </button>
                                </form>
                            @endif

                            @if($order->payment)
                            <div class="mb-0">
                                <small class="text-muted d-block">Waktu Transaksi</small>
                                <span class="fw-semibold text-dark small">{{ $order->payment->updated_at->format('d M Y, H:i') }} WIB</span>
                            </div>
                            @else
                                @if($order->payment_method !== 'cash')
                                <div class="alert alert-light py-2 px-3 mb-0 small border">
                                    <i class="fas fa-info-circle me-1"></i> Tidak ada riwayat pembayaran detail (menunggu pembayaran).
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($order->customer_lat && $order->customer_lng && !empty($googleMapsApiKey) && $googleMapsApiKey !== 'YOUR_API_KEY_HERE')
@push('scripts')
<script>
    function initAdminMap() {
        const coords = { lat: {{ $order->customer_lat }}, lng: {{ $order->customer_lng }} };
        const adminMap = new google.maps.Map(document.getElementById('admin-map'), {
            center: coords,
            zoom: 16,
            mapTypeControl: true,
            fullscreenControl: true,
            streetViewControl: true,
        });
        new google.maps.Marker({
            position: coords,
            map: adminMap,
            title: "{{ $order->customer_name }}"
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&callback=initAdminMap" async defer></script>
@endpush
@endif

@push('scripts')
<script>
    // Auto-fill courier fields when selecting from dropdown
    document.addEventListener('DOMContentLoaded', function() {
        const courierSelect = document.getElementById('courier_select');
        if (courierSelect) {
            courierSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                const nameInput = document.getElementById('courier_name');
                const phoneInput = document.getElementById('courier_phone');

                if (this.value) {
                    nameInput.value = selected.getAttribute('data-name');
                    phoneInput.value = selected.getAttribute('data-phone');
                } else {
                    nameInput.value = '';
                    phoneInput.value = '';
                }
            });
        }
    });
</script>
@endpush

@endsection
