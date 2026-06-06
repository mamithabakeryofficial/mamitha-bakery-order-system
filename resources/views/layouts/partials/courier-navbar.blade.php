<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary-custom fs-4 d-flex align-items-center" href="{{ route('courier.dashboard') }}">
            <img src="{{ asset('images/Logo.jpeg') }}" alt="Logo Mamitha" class="rounded-circle me-2 shadow-sm border border-white" style="width: 36px; height: 36px; object-fit: cover;">
            Mamitha Bakery <span class="badge bg-primary text-white ms-2 fs-6">Kurir</span>
        </a>
        <button class="navbar-toggler border-0 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#courierNav">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="courierNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-dark {{ request()->routeIs('courier.dashboard') ? 'fw-bold' : '' }}" href="{{ route('courier.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle text-dark d-flex align-items-center" href="#" id="userDropdown" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle me-2 border border-2 border-white" width="30" height="30" style="object-fit: cover;">
                        <span>{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li class="dropdown-header text-muted border-bottom pb-2">
                            <strong>{{ auth()->user()->name }}</strong><br>
                            <small>{{ auth()->user()->email }}</small>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button class="dropdown-item text-danger py-2 mt-1" type="submit">
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
