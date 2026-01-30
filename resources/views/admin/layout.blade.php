<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="initial-scale=1, maximum-scale=5, width=device-width" />
  <meta name="author" content="">
  <meta name="csrf_token" content="{{ csrf_token() }}" />
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

  <title>Admin Area</title>
  @vite(['resources/js/admin.js'])
</head>

<body>
  <nav
    class="admin-navbar navbar navbar-expand-md navbar-dark {{ app()->environment('local') ? 'admin-navbar--green local' : 'admin-navbar--green' }}">
    <div class="admin-navbar__container container">
      <a class="admin-navbar__brand navbar-brand" href="{{ route('admin.dashboard') }}">
        AllergenGo Admin
      </a>

      <!-- Bootstrap hamburger toggle -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavMenu"
        aria-controls="adminNavMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- collapsed menu -->
      <div
        class="collapse navbar-collapse admin-navbar__right flex-column flex-md-row justify-content-md-end align-items-start align-items-md-center"
        id="adminNavMenu">
        @auth('admin')
          <span class="admin-navbar__greeting mobile-link nav-link mb-0">Hi, {{ auth('admin')->user()->name }}</span>
          @php $admin = Auth::guard('admin')->user()->fresh(); @endphp
          @if(is_null($admin->super_admin_id))
            <a href="{{ route('admin.account') }}" class="admin-navbar__link nav-link mobile-link">Account</a>
          @endif
          <form method="POST" action="{{ route('admin.logout') }}" class="admin-navbar__logout-form d-inline">
            @csrf
            <button type="submit" class="admin-navbar__logout-btn btn btn-outline-light btn-sm ms-md-2 mobile-link">
              Logout
            </button>
          </form>
        @else
          <a href="{{ route('admin.login') }}" class="admin-navbar__link nav-link mobile-link">Login</a>
        @endauth
      </div>
    </div>
  </nav>

  @auth('admin')
    @php $admin = Auth::guard('admin')->user()->fresh(); @endphp
    @if($admin->payment_failed && is_null($admin->account_delete_date))
      <div class="payment-banner">
        <div class="payment-banner__icon" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16"
            fill="currentColor">
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
          You have a payment that <strong>failed</strong>. If you do not resolve this soon,
          your account will be locked until you pay.
          Please go <a href="{{ route('admin.account') }}">here</a> to update your card details.
        </div>
      </div>
    @endif
  @endauth

  <div class="container py-4">
    @yield('content')
  </div>

  @yield('scripts')
  @vite('resources/js/app.js')

</body>


</html>