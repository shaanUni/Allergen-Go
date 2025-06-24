<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, maximum-scale=5, width=device-width" />
    <meta name="author" content="">
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    <title>Admin Area</title>
    @vite(['resources/js/admin.js'])
</head>

<body>
    <nav class="admin-navbar {{  app()->environment('local') ? 'admin-navbar--green local' : 'admin-navbar--green' }}">
        <div class="admin-navbar__container ">
            <a class="admin-navbar__brand" href="{{ route('admin.dashboard') }}">AllergenGo Admin Panel</a>

            <div class="admin-navbar__right">
                @auth('admin')
                    <span class="admin-navbar__greeting">Hi, {{ auth('admin')->user()->name }}</span>
                    <a href="{{ route('admin.account') }}">asd</a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                @else
                    <a href="{{ route('admin.login') }}" class="btn-login">Login</a>
                @endauth
            </div>
        </div>
    </nav>

 
    @auth('admin')

    @php
        $failedPayment = false;

        $admin = Auth::guard('admin')->user()->fresh();
        if ($admin->payment_failed) {
            $failedPayment = true;
        }
    @endphp
        @if($failedPayment)
            <div>You have a payment that failed. If you do not resolve this soon, your account will be locked until you pay. Please go <a href="{{ route('admin.account') }}">here</a> to update your card details.</div>
        @endif
    @endauth


   
    <div class="container py-4">
        @yield('content')
    </div>

    @yield('scripts')

</body>

</html>