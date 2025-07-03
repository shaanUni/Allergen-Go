<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Standard favicon -->
<link rel="icon" href="/icons/favicon.ico" type="image/x-icon">

<!-- PNG favicons -->
<link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon-16x16.png">

<!-- Apple Touch Icon -->
<link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">

<!-- Web App Manifest -->
<link rel="manifest" href="/icons/site.webmanifest">

<!-- Safari pinned tab icon -->
<link rel="mask-icon" href="/icons/safari-pinned-tab.svg" color="#5bbad5">

<!-- Microsoft application tile -->
<meta name="msapplication-TileColor" content="#da532c">
<meta name="msapplication-config" content="/icons/browserconfig.xml">

<!-- Theme color for address bar (optional) -->
<meta name="theme-color" content="#ffffff">

    <title>AllergenGo — Allergy-Safe Dining Made Simple</title>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold text-success" href="#">AllergenGo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav gap-2 align-items-center">
                        <li class="nav-item">
                            <a class="btn" href="#features">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn" href="#why">What is AllergenGo?</a>
                        </li>
                        <li class="nav-item">
                        @if (request()->path() == '/')
                            <a class="btn" href="{{ route('restaurant') }}">For Restaurants</a>
                        @else                        
                            <a class="btn" href="{{ route('home') }}">For Users</a>
                        @endif    
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.search') }}" class="btn btn-primary get-started-btn">Get Started</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.login') }}" class="btn">Admin Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        AllergenGo — Allergy-Safe Dining Made Simple
    </footer>
    @vite('resources/js/app.js')

</body>

</html>
