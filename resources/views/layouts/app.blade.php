<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SupperKids CRM') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">SupperKids CRM</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    @auth
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('classes.index') }}">Lớp học</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('students.index') }}">Học sinh</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('teachers.index') }}">Giáo viên</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('payments.index') }}">Học phí</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('reports.index') }}">Báo cáo</a></li>
                        </ul>
                    @endauth

                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Đăng ký</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            Đăng xuất
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
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
