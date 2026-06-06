@extends('layouts.app')

@section('title', 'Admin Dashboard - Mamitha Bakery')

@section('content')
@include('layouts.partials.admin-navbar')

<div class="container py-5">
    {{-- Welcome & Actions --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-primary-custom mb-1">Dashboard Admin</h2>
            <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}. Berikut ringkasan toko Anda.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <form action="{{ route('admin.reset_transactions') }}" method="POST" onsubmit="return confirm('PERINGATAN!\n\nAksi ini akan MENGHAPUS PERMANEN semua data pesanan, detail pesanan, dan pembayaran.\nTotal Pendapatan dan Transaksi akan kembali menjadi 0.\n\nApakah Anda benar-benar yakin ingin mereset?');">
                @csrf
                <button type="submit" class="btn btn-danger rounded-3 shadow-sm">
                    <i class="fas fa-trash-alt me-2"></i>Reset Pendapatan & Transaksi
                </button>
            </form>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Summary Cards --}}
    <div class="row mb-5 g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-primary">
                <div class="card-body text-white d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 opacity-75">Total Pesanan</h6>
                        <h3 class="fw-bold mb-0">{{ $totalOrders }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-success">
                <div class="card-body text-white d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 opacity-75">Total Pendapatan</h6>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-info">
                <div class="card-body text-white d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 opacity-75">Total Produk</h6>
                        <h3 class="fw-bold mb-0">{{ $totalProducts }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-warning">
                <div class="card-body text-white d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 opacity-75">Total Pelanggan</h6>
                        <h3 class="fw-bold mb-0">{{ $totalCustomers }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Status Overview --}}
    <div class="row mb-5 g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body text-center py-4">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:60px; height:60px;">
                        <i class="fas fa-hourglass-half fa-xl text-warning"></i>
                    </div>
                    <h4 class="fw-bold">{{ $pendingOrders }}</h4>
                    <p class="text-muted mb-0 small">Menunggu Pembayaran</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body text-center py-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:60px; height:60px;">
                        <i class="fas fa-cogs fa-xl text-primary"></i>
                    </div>
                    <h4 class="fw-bold">{{ $processingOrders }}</h4>
                    <p class="text-muted mb-0 small">Sedang Diproses</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body text-center py-4">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:60px; height:60px;">
                        <i class="fas fa-check-double fa-xl text-success"></i>
                    </div>
                    <h4 class="fw-bold">{{ $completedOrders }}</h4>
                    <p class="text-muted mb-0 small">Selesai</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body text-center py-4">
                    <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:60px; height:60px;">
                        <i class="fas fa-times-circle fa-xl text-danger"></i>
                    </div>
                    <h4 class="fw-bold">{{ $cancelledOrders }}</h4>
                    <p class="text-muted mb-0 small">Dibatalkan</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue Trend Chart --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="fas fa-chart-line me-2 text-success"></i>Tren Pendapatan Harian (7 Hari Terakhir)</h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="fas fa-list-alt me-2 text-primary-custom"></i>Pesanan Terbaru</h5>
                </div>
                <div class="card-body p-4">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Pelanggan</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Pembayaran</th>
                                        <th>Status Pesanan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td><span class="fw-semibold text-primary-custom">{{ $order->invoice_number }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $order->user->avatar_url }}" class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;">
                                                {{ $order->user->name ?? $order->customer_name }}
                                            </div>
                                        </td>
                                        <td>{{ $order->order_date->format('d M Y') }}</td>
                                        <td>{{ $order->formatted_total }}</td>
                                        <td>{!! $order->payment_badge !!}</td>
                                        <td>{!! $order->status_badge !!}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3 opacity-25"></i>
                            <h5 class="text-muted">Belum ada pesanan masuk.</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart');
        if (ctx) {
            const rawData = @json($dailyRevenue);
            
            // Format dates
            const labels = rawData.map(item => {
                const d = new Date(item.date);
                return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            });
            const data = rawData.map(item => item.revenue);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: data,
                        borderColor: '#2E7D32',
                        backgroundColor: 'rgba(46, 125, 50, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#2E7D32',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
