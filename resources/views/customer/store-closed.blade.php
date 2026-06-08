@extends('layouts.app')

@section('title', 'Toko Tutup - Mamitha Bakery')

@section('content')
@include('layouts.partials.customer-navbar')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="text-center mb-5">
                {{-- Animated closed icon --}}
                <div class="store-closed-icon mx-auto mb-4">
                    <div class="icon-circle">
                        <i class="fas fa-store-slash fa-3x"></i>
                    </div>
                </div>

                <h2 class="fw-bold text-primary-custom mb-3">Toko Sedang Tutup</h2>
                <p class="text-muted fs-5 mb-0">
                    Mohon maaf, Mamitha Bakery sedang tidak beroperasi saat ini.
                </p>
            </div>

            {{-- Operational Hours Card --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #4A2E1B 0%, #6B3E26 100%);">
                    <h5 class="fw-bold text-white mb-0 text-center">
                        <i class="fas fa-clock me-2"></i>Jam Operasional
                    </h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center g-4">
                        {{-- Opening Time --}}
                        <div class="col-md-5 text-center">
                            <div class="time-block">
                                <div class="time-icon bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-door-open fa-lg text-success"></i>
                                </div>
                                <p class="text-muted small fw-semibold mb-1 text-uppercase" style="letter-spacing: 0.1em;">Buka</p>
                                <h3 class="fw-bold text-dark mb-0">
                                    {{ $dailyLimit ? \Carbon\Carbon::createFromTimeString($dailyLimit->opening_time)->format('H:i') : '08:00' }}
                                </h3>
                                <p class="text-muted small mb-0">WIB</p>
                            </div>
                        </div>

                        {{-- Separator --}}
                        <div class="col-md-2 text-center">
                            <div class="separator-line d-none d-md-block">
                                <i class="fas fa-arrow-right fa-lg text-muted opacity-25"></i>
                            </div>
                            <div class="d-md-none py-2">
                                <i class="fas fa-arrow-down fa-lg text-muted opacity-25"></i>
                            </div>
                        </div>

                        {{-- Closing Time --}}
                        <div class="col-md-5 text-center">
                            <div class="time-block">
                                <div class="time-icon bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-door-closed fa-lg text-danger"></i>
                                </div>
                                <p class="text-muted small fw-semibold mb-1 text-uppercase" style="letter-spacing: 0.1em;">Tutup</p>
                                <h3 class="fw-bold text-dark mb-0">
                                    {{ $dailyLimit ? \Carbon\Carbon::createFromTimeString($dailyLimit->closing_time)->format('H:i') : '20:00' }}
                                </h3>
                                <p class="text-muted small mb-0">WIB</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 opacity-10">

                    {{-- Current Time --}}
                    <div class="text-center">
                        <p class="text-muted small mb-1">Waktu saat ini (WIB)</p>
                        <h4 class="fw-bold text-primary-custom mb-0" id="current-time">
                            {{ now()->format('H:i:s') }}
                        </h4>
                        <p class="text-muted small mt-1 mb-0">{{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Info Alert --}}
            <div class="alert border-0 rounded-4 shadow-sm py-3 px-4 mb-4" style="background: linear-gradient(135deg, #FFF7ED 0%, #FFFBEB 100%); border-left: 4px solid #D97706 !important;">
                <div class="d-flex align-items-start">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                        <i class="fas fa-info-circle text-warning"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Silakan kembali saat jam buka</h6>
                        <p class="text-muted small mb-0">
                            Anda masih dapat melihat <strong>riwayat pesanan</strong> dan mengatur <strong>profil</strong> Anda selama toko tutup.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="row g-3">
                <div class="col-6">
                    <a href="{{ route('customer.orders.index') }}" class="card border-0 shadow-sm rounded-4 text-decoration-none h-100 store-closed-link">
                        <div class="card-body text-center py-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-receipt fa-lg text-primary"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Riwayat Pesanan</h6>
                            <p class="text-muted small mb-0">Lihat pesanan Anda</p>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('customer.profile') }}" class="card border-0 shadow-sm rounded-4 text-decoration-none h-100 store-closed-link">
                        <div class="card-body text-center py-4">
                            <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user-edit fa-lg text-info"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Profil Saya</h6>
                            <p class="text-muted small mb-0">Atur data akun Anda</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .store-closed-icon .icon-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #DC2626;
        animation: pulse-soft 2.5s ease-in-out infinite;
    }

    @keyframes pulse-soft {
        0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.15); }
        50% { transform: scale(1.05); box-shadow: 0 0 0 16px rgba(220, 38, 38, 0); }
    }

    .store-closed-link {
        transition: all 0.25s ease;
    }
    .store-closed-link:hover {
        transform: translateY(-4px) !important;
        box-shadow: 0 12px 24px -4px rgba(0,0,0,0.1) !important;
    }

    .time-block {
        padding: 1rem;
        border-radius: 1rem;
        background: #F8FAFC;
        transition: all 0.2s ease;
    }
    .time-block:hover {
        background: #F1F5F9;
    }
</style>
@endpush

@push('scripts')
<script>
    // Live clock
    function updateClock() {
        const el = document.getElementById('current-time');
        if (el) {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            el.textContent = hours + ':' + minutes + ':' + seconds;
        }
    }
    setInterval(updateClock, 1000);
</script>
@endpush
