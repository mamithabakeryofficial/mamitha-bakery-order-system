<style>
    /* Premium Navbar Styling */
    .premium-navbar {
        background: rgba(255, 255, 255, 0.92) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05) !important;
        transition: all 0.3s ease;
    }
    
    .navbar-brand-premium {
        font-family: 'Outfit', 'Inter', sans-serif;
        font-weight: 800;
        background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.5px;
        font-size: 1.6rem !important;
        display: flex;
        align-items: center;
    }

    .nav-link-custom {
        font-weight: 600;
        color: #6c757d !important;
        position: relative;
        padding: 0.5rem 1rem !important;
        margin: 0 0.2rem;
        transition: color 0.3s ease;
    }
    
    .nav-link-custom:hover, .nav-link-custom.active {
        color: #8B4513 !important;
    }
    
    .nav-link-custom::after {
        content: '';
        position: absolute;
        width: 0;
        height: 3px;
        bottom: 0;
        left: 50%;
        background: linear-gradient(90deg, #8B4513, #D2691E);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        transform: translateX(-50%);
        border-radius: 3px 3px 0 0;
        opacity: 0;
    }
    
    .nav-link-custom:hover::after, .nav-link-custom.active::after {
        width: 100%;
        opacity: 1;
    }

    .icon-btn-premium {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #f8f9fa;
        color: #495057 !important;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
    }
    
    .icon-btn-premium:hover {
        background-color: #fffaf0;
        color: #8B4513 !important;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(139, 69, 19, 0.15);
    }

    .badge-premium {
        border: 2px solid #fff;
        padding: 0.35em 0.5em !important;
        transform: translate(-20%, -20%) !important;
        background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
        box-shadow: 0 3px 6px rgba(231, 76, 60, 0.4);
        font-weight: 700;
    }

    .user-dropdown-premium {
        padding: 5px 12px 5px 5px;
        border-radius: 50px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        background: #fff;
        font-weight: 600;
        color: #495057 !important;
    }
    
    .user-dropdown-premium:hover {
        background: #f8f9fa;
        border-color: #dee2e6;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        color: #8B4513 !important;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light premium-navbar sticky-top py-2">
    <div class="container">
        <a class="navbar-brand navbar-brand-premium" href="{{ route('customer.dashboard') }}">
            <img src="{{ asset('images/Logo.jpeg') }}" alt="Logo Mamitha" class="rounded-circle me-2 shadow-sm border border-white" style="width: 36px; height: 36px; object-fit: cover;">
            Mamitha Bakery
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto ps-lg-4">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('customer.products.*') ? 'active' : '' }}" href="{{ route('customer.products.index') }}">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}" href="{{ route('customer.orders.index') }}">Pesanan Saya</a>
                </li>
            </ul>
            <ul class="navbar-nav align-items-center gap-2">
                <!-- Shopping Cart -->
                <li class="nav-item">
                    <a class="icon-btn-premium position-relative {{ request()->routeIs('customer.cart.index') ? 'active' : '' }}" href="{{ route('customer.cart.index') }}" title="Keranjang Belanja">
                        <i class="fas fa-shopping-cart fs-5"></i>
                        @php
                            $cartCount = auth()->user()->cartItems()->sum('qty');
                        @endphp
                        @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-premium" style="font-size: 0.65rem;">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>
                </li>
                <!-- Notification Dropdown -->
                <li class="nav-item dropdown me-2">
                    <a class="icon-btn-premium position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <i class="fas fa-bell fs-5"></i>
                        @if(auth()->user()->unreadNotificationsCount() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-premium" style="font-size: 0.65rem;">
                            {{ auth()->user()->unreadNotificationsCount() }}
                        </span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0 rounded-4 mt-2" style="width: 320px; animation: dropdownFade 0.2s ease;">
                        <style>
                            @keyframes dropdownFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
                        </style>
                        <div class="card border-0 rounded-4 overflow-hidden">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                                <h6 class="mb-0 fw-bold">Notifikasi</h6>
                                @if(auth()->user()->unreadNotificationsCount() > 0)
                                <form action="{{ route('customer.notifications.readAll') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-decoration-none p-0 small text-primary-custom">Tandai Semua Dibaca</button>
                                </form>
                                @endif
                            </div>
                            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                                @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                <form action="{{ route('customer.notifications.read', $notification->id) }}" method="POST" class="border-bottom">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="dropdown-item py-3 {{ $notification->is_read ? 'bg-light text-muted' : 'bg-white' }} text-wrap">
                                        <div class="d-flex align-items-start">
                                            <div class="rounded-circle bg-{{ $notification->type }} bg-opacity-10 p-2 me-3 mt-1">
                                                @php
                                                    $icon = match($notification->type) {
                                                        'success' => 'fa-check-circle',
                                                        'warning' => 'fa-exclamation-triangle',
                                                        'danger' => 'fa-times-circle',
                                                        default => 'fa-info-circle',
                                                    };
                                                @endphp
                                                <i class="fas {{ $icon }} text-{{ $notification->type }}"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 {{ $notification->is_read ? '' : 'fw-bold' }} text-dark fs-6">{{ $notification->title }}</h6>
                                                <p class="mb-1 small {{ $notification->is_read ? 'text-muted' : 'text-dark' }}">{{ $notification->message }}</p>
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            @if(!$notification->is_read)
                                            <div class="ms-auto mt-2">
                                                <span class="p-1 bg-primary-custom rounded-circle d-inline-block"></span>
                                            </div>
                                            @endif
                                        </div>
                                    </button>
                                </form>
                                @empty
                                <div class="p-4 text-center text-muted">
                                    <i class="fas fa-bell-slash fs-3 mb-2 opacity-50"></i>
                                    <p class="mb-0">Belum ada notifikasi.</p>
                                </div>
                                @endforelse
                            </div>
                            <div class="card-footer bg-light text-center py-2 border-0">
                                <a href="#" class="text-decoration-none text-primary-custom small fw-semibold">Lihat Semua Notifikasi</a>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- User Dropdown -->
                <li class="nav-item dropdown ms-1">
                    <a class="nav-link d-flex align-items-center user-dropdown-premium text-decoration-none" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="rounded-circle me-2 shadow-sm" width="34" height="34" style="object-fit: cover;">
                        <span class="me-1">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down ms-1" style="font-size: 0.75rem;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2" style="animation: dropdownFade 0.2s ease;">
                        <li>
                            <a class="dropdown-item py-2 fw-semibold {{ request()->routeIs('customer.profile') ? 'active bg-primary-custom' : '' }}" href="{{ route('customer.profile') }}">
                                <i class="fas fa-user fa-fw me-2 {{ request()->routeIs('customer.profile') ? '' : 'text-muted' }}"></i> Profil Saya
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt fa-fw me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
