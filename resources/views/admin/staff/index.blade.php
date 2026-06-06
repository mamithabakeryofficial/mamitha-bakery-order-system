@extends('layouts.app')

@section('title', 'Kelola Staff - Mamitha Bakery')

@section('content')
@include('layouts.partials.admin-navbar')

<div class="container py-5">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="fw-bold text-primary-custom mb-1">Kelola Staff Toko</h2>
                <p class="text-muted mb-0">Kelola akun Staff Dapur, Kurir, dan Administrator.</p>
            </div>
            <div>
                <a href="{{ route('admin.staff.create') }}" class="btn btn-primary-custom rounded-3 fw-bold">
                    <i class="fas fa-plus me-1"></i> Tambah Staff Baru
                </a>
            </div>
        </div>
    </div>

    {{-- Success/Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filter & Search Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.staff.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label for="search" class="form-label fw-semibold text-muted small">Cari Staff</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" id="search" class="form-control border-start-0 ps-0" value="{{ request('search') }}" placeholder="Cari nama, username, email, telepon...">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="role" class="form-label fw-semibold text-muted small">Filter Peran (Role)</label>
                    <select name="role" id="role" class="form-select">
                        <option value="">Semua Peran</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="kitchen" {{ request('role') === 'kitchen' ? 'selected' : '' }}>Dapur (Kitchen)</option>
                        <option value="courier" {{ request('role') === 'courier' ? 'selected' : '' }}>Kurir (Courier)</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary-custom w-100 fw-bold">Filter</button>
                    @if(request()->anyFilled(['search', 'role']))
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary fw-bold"><i class="fas fa-undo"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Staff Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>No. WhatsApp</th>
                            <th>Peran</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->avatar_url }}" class="rounded-circle me-2 border" width="36" height="36" style="object-fit:cover;">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ $user->name }}</h6>
                                        @if($user->id === auth()->id())
                                            <span class="badge bg-secondary-subtle text-secondary small" style="font-size:0.7rem;">Anda</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Administrator</span>
                                @elseif($user->role === 'kitchen')
                                    <span class="badge bg-warning text-dark">Dapur (Kitchen)</span>
                                @elseif($user->role === 'courier')
                                    <span class="badge bg-info">Kurir (Courier)</span>
                                @else
                                    <span class="badge bg-secondary">{{ $user->role }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.staff.edit', $user->id) }}" class="btn btn-sm btn-outline-primary rounded-3">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.staff.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun staff ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3 opacity-25"></i>
                                <h5 class="text-muted">Tidak ada data staff ditemukan.</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($staff->hasPages())
        <div class="card-footer bg-white border-0 py-3 px-4">
            {{ $staff->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
