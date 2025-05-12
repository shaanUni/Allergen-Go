<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, maximum-scale=5, width=device-width"/>
    <meta name="author" content="">
    <meta name="csrf_token" content="{{ csrf_token() }}"/>
    <title>Admin Area</title>
    @vite(['resources/js/admin.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin Panel</a>

        <div class="ms-auto">
            @auth('admin')
                <span class="me-3">Hello, {{ auth('admin')->user()->name }}</span>

                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            @else
                <a href="{{ route('admin.login') }}" class="btn btn-outline-primary btn-sm">Login</a>
            @endauth
        </div>
    </nav>

    <div class="container py-4">
        @yield('content')
    </div>
</body>
</html>
