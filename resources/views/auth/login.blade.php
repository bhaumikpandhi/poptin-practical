@extends('layouts.guest.layout')

@section('content')
    <div class="px-3 w-100 d-flex justify-content-center">
        <div class="card login-card">
            <div class="card-body p-4 p-sm-5">
                <div class="text-center mb-4">
                    <div class="logo-circle">
                        <i class="bi bi-bar-chart-fill text-white fs-4"></i>
                    </div>
                    <h5 class="fw-bold mb-0">{{ config('app.name') }}</h5>
                    <p class="text-muted small mt-1">Sign in to your account</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger py-2 small">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success py-2 small">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('post.login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium" for="email">Email address</label>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="you@example.com"
                               autofocus autocomplete="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium" for="password">Password</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="••••••••"
                                   autocomplete="current-password">
                            <button type="button" class="btn btn-outline-secondary border-start-0"
                                    id="togglePassword" tabindex="-1">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-login">
                        Sign In
                    </button>

                </form>

                <p class="text-center text-muted small mt-4 mb-0">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-decoration-none fw-medium">Sign up</a>
                </p>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon  = document.getElementById('toggleIcon');
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    </script>
@endpush