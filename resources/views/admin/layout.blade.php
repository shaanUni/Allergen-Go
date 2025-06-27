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
                    <a href="{{ route('admin.account') }}" class="admin-navbar__link">Account</a>
                    <form method="POST" action="{{ route('admin.logout') }}" class="admin-navbar__logout-form">
                        @csrf
                        <button type="submit" class="admin-navbar__logout-btn">Logout</button>
                    </form>
                @else
                    <a href="{{ route('admin.login') }}" class="admin-navbar__link">Login</a>
                @endauth
            </div>

        </div>
    </nav>


    @auth('admin')
        @php
            $admin = Auth::guard('admin')->user()->fresh();
        @endphp

        @if($admin->payment_failed && is_null($admin->account_delete_date))
            <div class="payment-banner">
                <div class="payment-banner__icon" aria-hidden="true">
                    <!-- Simple “warning” SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16"
                        fill="currentColor" aria-hidden="true">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.964 
                       0L.165 13.233c-.457.778.091 1.767.982 
                       1.767h13.706c.891 0 1.439-.99.982-1.767L8.982 
                       1.566zM8 5c.535 0 .954.462.9.995l-.35 
                       3.507a.552.552 0 0 1-1.1 0L7.1 
                       5.995A.905.905 0 0 1 8 5zm.002 
                       6a1 1 0 1 1-2.002 0 1 1 0 0 1 2.002 0z" />
                    </svg>
                </div>
                <div class="payment-banner__message">
                    You have a payment that <strong>failed</strong>. If you do not resolve this soon, your account will be
                    locked until you pay.
                    Please go <a href="{{ route('admin.account') }}">here</a> to update your card details.
                </div>
            </div>
        @endif
    @endauth




    <div class="container py-4">
        @yield('content')
    </div>

    @yield('scripts')

</body>

</html>