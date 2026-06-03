<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SupperKids CRM') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:300,400,500,600,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <i class="bi bi-mortarboard-fill me-2"></i>SupperKids
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    @auth
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="bi bi-grid-1x2 me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}" href="{{ route('classes.index') }}">
                                    <i class="bi bi-collection me-1"></i> Lớp học
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                                    <i class="bi bi-people me-1"></i> Học sinh
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}" href="{{ route('teachers.index') }}">
                                    <i class="bi bi-person-workspace me-1"></i> Giáo viên
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                                    <i class="bi bi-wallet2 me-1"></i> Học phí
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                    <i class="bi bi-bar-chart-line me-1"></i> Báo cáo
                                </a>
                            </li>
                        </ul>
                    @endauth

                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus me-1"></i> Đăng ký</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="user-avatar-sm me-2">{{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}</span>
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                                        </a>
                                    </li>
                                </ul>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2 flex-shrink-0"></i>
                        <span>{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 flex-shrink-0"></i>
                        <span>{{ session('error') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')

    {{-- Currency Input Formatter — Chuẩn Việt Nam --}}
    <script>
    (function() {
        function formatCurrency(value) {
            var num = String(value).replace(/[^\d]/g, '');
            num = num.replace(/^0+(?=\d)/, '');
            return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function parseCurrency(value) {
            // Truncate at decimal point first (handles "1500000.00" from DB)
            var str = String(value).split('.')[0].split(',')[0];
            // Then strip any remaining non-digits (handles "1.500.000" formatted strings)
            // But first check: if string has dots as thousand separators (3-digit groups), strip them
            var cleaned = String(value);
            // If value looks like a decimal number (e.g., "1500000.00"), take integer part only
            if (/^\d+\.\d{1,2}$/.test(cleaned)) {
                return cleaned.split('.')[0];
            }
            // Otherwise strip all non-digits (handles "1.500.000" formatted or plain "1500000")
            return cleaned.replace(/[^\d]/g, '');
        }

        function initCurrencyInputs() {
            var inputs = document.querySelectorAll('.currency-input');
            inputs.forEach(function(input) {
                if (input.dataset.currencyInitialized) return;
                input.dataset.currencyInitialized = 'true';

                var targetName = input.getAttribute('data-target');
                var form = input.closest('form');
                var hiddenInput = form ? form.querySelector('input[type="hidden"][name="' + targetName + '"]') : null;

                // Format initial value
                if (input.value) {
                    var raw = parseCurrency(input.value);
                    input.value = (raw && raw !== '0') ? formatCurrency(raw) : '0';
                    if (hiddenInput) hiddenInput.value = raw || '0';
                }

                // Real-time formatting
                input.addEventListener('input', function() {
                    var cursorPos = input.selectionStart;
                    var oldLen = input.value.length;
                    var raw = parseCurrency(input.value);
                    input.value = formatCurrency(raw);
                    if (hiddenInput) hiddenInput.value = raw;
                    var newLen = input.value.length;
                    var newPos = Math.max(0, cursorPos + (newLen - oldLen));
                    input.setSelectionRange(newPos, newPos);
                });

                // Sync on blur
                input.addEventListener('blur', function() {
                    var raw = parseCurrency(input.value);
                    if (hiddenInput) hiddenInput.value = raw || '0';
                    input.value = raw ? formatCurrency(raw) : '0';
                });

                // Safety net on submit
                if (form) {
                    form.addEventListener('submit', function() {
                        if (hiddenInput) hiddenInput.value = parseCurrency(input.value);
                    });
                }
            });
        }

        // Run immediately (script is at bottom of body, DOM is ready)
        initCurrencyInputs();
        // Also run on DOMContentLoaded as fallback
        document.addEventListener('DOMContentLoaded', initCurrencyInputs);
    })();
    </script>
</body>
</html>
