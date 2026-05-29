@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:64px;height:64px;background:linear-gradient(135deg,#7c5cfc,#a78bfa)">
                    <i class="bi bi-mortarboard-fill text-white fs-3"></i>
                </div>
                <h4 class="fw-bold mb-1">Chào mừng trở lại!</h4>
                <p class="text-muted small">Đăng nhập vào SupperKids</p>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="email@example.com">
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="remember">Ghi nhớ</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="small" href="{{ route('password.request') }}">Quên mật khẩu?</a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                        </button>
                    </form>
                </div>
            </div>

            @if (Route::has('register'))
                <p class="text-center mt-3 small text-muted">
                    Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
