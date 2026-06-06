<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary-custom fs-4 d-flex align-items-center" href="{{ route('kitchen.dashboard') }}">
            <img src="{{ asset('images/Logo.jpeg') }}" alt="Logo Mamitha" class="rounded-circle me-2 shadow-sm border border-white" style="width: 36px; height: 36px; object-fit: cover;">
            Mamitha Bakery <span class="badge bg-success text-white ms-2 fs-6">Dapur</span>
        </a>
        <button class="navbar-toggler border-0 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#kitchenNav">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="kitchenNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-dark {{ request()->routeIs('kitchen.dashboard') ? 'fw-bold' : '' }}" href="{{ route('kitchen.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
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
