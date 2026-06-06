@extends('layouts.app')

@section('title', 'Dashboard Pelanggan - Mamitha Bakery')

@section('content')
@include('layouts.partials.customer-navbar')

<div class="container py-5">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary-custom">Halo, {{ $user->name }}!</h2>
            <p class="text-muted">Selamat datang di dashboard pelanggan Mamitha Bakery.</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-5 g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-primary-custom bg-opacity-10 p-3 me-3">
                        <i class="fas fa-shopping-bag fa-2x text-primary-custom"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Pesanan</h6>
                        <h3 class="fw-bold mb-0">{{ $totalOrders }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-wallet fa-2x text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Transaksi</h6>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalTransactions, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Pesanan Aktif</h6>
                        <h3 class="fw-bold mb-0">{{ $activeOrders }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="fas fa-check-circle fa-2x text-info"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Pesanan Selesai</h6>
                        <h3 class="fw-bold mb-0">{{ $completedOrders }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Pesanan Terkini</h5>
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-4">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Pembayaran</th>
                                        <th>Status Pesanan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td><span class="fw-semibold text-primary-custom">{{ $order->invoice_number }}</span></td>
                                        <td>{{ $order->order_date->format('d M Y') }}</td>
                                        <td>{{ $order->formatted_total }}</td>
                                        <td>{!! $order->payment_badge !!}</td>
                                        <td>{!! $order->status_badge !!}</td>
                                        <td>
                                            <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-light border shadow-sm"><i class="fas fa-eye text-primary-custom"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png" alt="Empty" width="150" class="mb-3 opacity-50">
                            <h5 class="text-muted">Belum ada pesanan.</h5>
                            <a href="{{ route('customer.products.index') }}" class="btn btn-primary-custom mt-2">Mulai Belanja</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
