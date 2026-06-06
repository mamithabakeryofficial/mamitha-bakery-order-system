<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary-custom d-flex align-items-center fs-4" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('images/Logo.jpeg') }}" alt="Logo" class="rounded-circle me-2 shadow-sm" style="width: 35px; height: 35px; object-fit: cover; background-color: white;">
            Mamitha Bakery <span class="badge bg-primary-custom text-white ms-2 fs-6">Admin</span>
        </a>
        <button class="navbar-toggler border-0 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-dark {{ request()->routeIs('admin.dashboard') ? 'fw-bold' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-chart-pie me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark {{ request()->routeIs('admin.products.*') ? 'fw-bold' : '' }}" href="{{ route('admin.products.index') }}">
                        <i class="fas fa-cookie me-1"></i> Produk
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark {{ request()->routeIs('admin.orders.*') ? 'fw-bold' : '' }}" href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-basket me-1"></i> Pesanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark {{ request()->routeIs('admin.staff.*') ? 'fw-bold' : '' }}" href="{{ route('admin.staff.index') }}">
                        <i class="fas fa-users-cog me-1"></i> Kelola Staff
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark {{ request()->routeIs('admin.reports.*') ? 'fw-bold' : '' }}" href="{{ route('admin.reports.index') }}">
                        <i class="fas fa-file-invoice-dollar me-1"></i> Laporan Keuangan
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="userDropdown" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle me-1" width="28" height="28" style="object-fit: cover;">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
