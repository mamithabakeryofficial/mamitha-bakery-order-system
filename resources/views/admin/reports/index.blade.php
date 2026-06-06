@extends('layouts.app')

@section('title', 'Laporan Keuangan - Mamitha Bakery')

@section('content')
@include('layouts.partials.admin-navbar')

<div class="container py-5">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold text-primary-custom">Laporan Keuangan</h2>
            <p class="text-muted">Pantau data penjualan dan performa bisnis Anda.</p>
        </div>
        
        {{-- Date Filter Form --}}
        <form method="GET" action="{{ route('admin.reports.index') }}" class="card border-0 shadow-sm p-3 bg-white rounded-3">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Tanggal</option>
                    </select>
                </div>
                
                @if($period === 'custom' || request()->filled('start_date'))
                <div class="col-auto">
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}" required>
                </div>
                <div class="col-auto text-muted">s/d</div>
                <div class="col-auto">
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary-custom"><i class="fas fa-filter"></i></button>
                </div>
                @endif
            </div>
        </form>
    </div>

    {{-- Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-wallet fa-2x text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small">Total Pendapatan</h6>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-receipt fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small">Total Transaksi</h6>
                        <h4 class="fw-bold mb-0">{{ $totalOrders }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="fas fa-check-circle fa-2x text-info"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small">Transaksi Sukses</h6>
                        <h4 class="fw-bold mb-0">{{ $paidOrders }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                        <i class="fas fa-calculator fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small">Rata-rata Transaksi</h6>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts and Analytics --}}
    <div class="row g-4 mb-5">
        {{-- Daily Revenue Line Chart --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-0">Tren Pendapatan Harian</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div style="position: relative; height:320px; width:100%">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales by Category --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-0">Proporsi Kategori</h5>
                </div>
                <div class="card-body px-4 pb-4 d-flex flex-column justify-content-center">
                    @if($revenueByCategory->count() > 0)
                        <div style="position: relative; height:240px; width:100%" class="mb-3">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-pie fa-3x text-muted opacity-25 mb-3"></i>
                            <p class="text-muted mb-0">Belum ada data penjualan kategori.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Top Selling Products --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-0">Produk Terlaris</h5>
                </div>
                <div class="card-body p-4">
                    @if($topProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <thead>
                                    <tr class="table-light">
                                        <th class="rounded-start">Nama Produk</th>
                                        <th class="text-center">Jumlah Terjual</th>
                                        <th class="text-end rounded-end">Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProducts as $item)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-dark">{{ $item->name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-3">{{ $item->total_qty }} pcs</span>
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-cookie fa-3x text-muted opacity-25 mb-3"></i>
                            <p class="text-muted mb-0">Belum ada data penjualan produk.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Sales --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-0">Transaksi Sukses Terbaru</h5>
                </div>
                <div class="card-body p-4">
                    @if($recentPaidOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <thead>
                                    <tr class="table-light">
                                        <th class="rounded-start">Invoice</th>
                                        <th>Nama Pelanggan</th>
                                        <th class="text-end rounded-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPaidOrders as $order)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-primary-custom">{{ $order->invoice_number }}</span>
                                        </td>
                                        <td>
                                            <span class="text-dark">{{ $order->user->name ?? $order->customer_name }}</span>
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            {{ $order->formatted_total }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-3x text-muted opacity-25 mb-3"></i>
                            <p class="text-muted mb-0">Belum ada transaksi pembayaran lunas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Daily Revenue Data
        const dailyLabels = [@foreach($dailyRevenue as $item) '{{ \Carbon\Carbon::parse($item->date)->format("d M") }}', @endforeach];
        const dailyData = [@foreach($dailyRevenue as $item) {{ $item->revenue }}, @endforeach];
        
        // Render Line Chart
        const revCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revCtx, {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: dailyData,
                    borderColor: '#8B4513',
                    backgroundColor: 'rgba(139, 69, 19, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        @if($revenueByCategory->count() > 0)
        // Category Data
        const categoryLabels = [@foreach($revenueByCategory as $item) '{{ $item->name }}', @endforeach];
        const categoryData = [@foreach($revenueByCategory as $item) {{ $item->total_revenue }}, @endforeach];
        
        // Render Doughnut Chart
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryData,
                    backgroundColor: ['#8B4513', '#CD853F', '#D2B48C', '#DEB887', '#F5DEB3'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12
                        }
                    }
                }
            }
        });
        @endif
    });
</script>
@endpush
@endsection
