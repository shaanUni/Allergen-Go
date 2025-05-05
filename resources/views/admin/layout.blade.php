<!DOCTYPE html>
<html>
<head>
    <title>Admin Area</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
