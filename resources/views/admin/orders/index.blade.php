@extends('layouts.app')

@section('title', 'Manajemen Pesanan - Admin Mamitha Bakery')

@section('content')
@include('layouts.partials.admin-navbar')

<div class="container py-5">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary-custom">Manajemen Pesanan</h2>
            <p class="text-muted">Kelola status pesanan, pembayaran, dan pengiriman pelanggan.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari invoice atau nama..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status Pesanan</option>
                        <option value="menunggu_pembayaran" {{ request('status') === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="dibayar" {{ request('status') === 'dibayar' ? 'selected' : '' }}>Pembayaran Berhasil</option>
                        <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses Admin</option>
                        <option value="sedang_dibuat" {{ request('status') === 'sedang_dibuat' ? 'selected' : '' }}>Sedang Diproduksi</option>
                        <option value="siap_diambil" {{ request('status') === 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                        <option value="sedang_dikirim" {{ request('status') === 'sedang_dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="payment" class="form-select">
                        <option value="">Semua Status Bayar</option>
                        <option value="pending" {{ request('payment') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="settlement" {{ request('payment') === 'settlement' ? 'selected' : '' }}>Lunas</option>
                        <option value="expire" {{ request('payment') === 'expire' ? 'selected' : '' }}>Expired</option>
                        <option value="cancel" {{ request('payment') === 'cancel' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary-custom w-100 fw-bold"><i class="fas fa-filter me-2"></i>Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-body p-0">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Invoice</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status Bayar</th>
                                <th>Status Pesanan</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-primary-custom">{{ $order->invoice_number }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $order->user->avatar_url }}" class="rounded-circle me-2" width="30" height="30" style="object-fit:cover;">
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $order->user->name ?? $order->customer_name }}</div>
                                            <small class="text-muted">{{ $order->customer_phone }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $order->order_date->format('d M Y') }}
                                </td>
                                <td class="fw-semibold text-dark">
                                    {{ $order->formatted_total }}
                                </td>
                                <td>
                                    {!! $order->payment_badge !!}
                                </td>
                                <td>
                                    {!! $order->status_badge !!}
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary fw-bold">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination Links --}}
                <div class="p-4 d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted opacity-25 mb-3"></i>
                    <h5 class="text-muted fw-bold">Pesanan Tidak Ditemukan</h5>
                    <p class="text-muted mb-0">Belum ada pesanan yang sesuai dengan filter Anda.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
