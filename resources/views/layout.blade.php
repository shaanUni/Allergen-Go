<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
                            <a class="btn" href="#why">Why Us?</a>
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
