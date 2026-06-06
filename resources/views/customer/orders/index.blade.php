@extends('layouts.app')

@section('title', 'Pesanan Saya - Mamitha Bakery')

@section('content')
@include('layouts.partials.customer-navbar')

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary-custom">Riwayat Pembelian</h2>
            <p class="text-muted">Daftar semua pesanan Anda di Mamitha Bakery.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status Pembayaran</th>
                                        <th>Status Pesanan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td><span class="fw-semibold text-primary-custom">{{ $order->invoice_number }}</span></td>
                                        <td>{{ $order->order_date->format('d M Y') }}</td>
                                        <td>{{ $order->formatted_total }}</td>
                                        <td>{!! $order->payment_badge !!}</td>
                                        <td>{!! $order->status_badge !!}</td>
                                        <td class="text-center">
                                            <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png" alt="Empty" width="200" class="mb-3 opacity-50">
                            <h4 class="text-muted">Belum ada riwayat pembelian.</h4>
                            <p class="text-muted">Anda belum membuat pesanan apapun.</p>
                            <a href="{{ route('customer.products.index') }}" class="btn btn-primary-custom mt-2 px-4 py-2">Mulai Belanja</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
