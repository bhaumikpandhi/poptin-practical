<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts.admin.includes.header')
<body>
    <nav class="navbar navbar-expand-lg navbar-admin">
        <div class="d-flex align-items-center w-100">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-bar-chart-fill"></i>
                {{ config('app.name') }}
            </a>

            <div class="admin-header-actions">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" style="color: #495057; text-decoration: none;">
                        <i class="bi bi-person-circle"></i>
                        <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                        <li>
                            <h6 class="dropdown-header">{{ Auth::user()->email }}</h6>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="admin-layout">
        @include('layouts.admin.includes.sidebar')

        <main class="admin-content">
            @yield('content')
        </main>
    </div>

    @include('layouts.admin.includes.footer')
</body>
</html>
