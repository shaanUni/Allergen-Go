<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, maximum-scale=5, width=device-width"/>
    <meta name="author" content="">
    <meta name="csrf_token" content="{{ csrf_token() }}"/>
    <title>User Area</title>
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

    @vite(['resources/js/user.js'])
</head>
<body>
    <div class="nav-div {{  app()->environment('local') ? 'local' : 'green' }}">
        <nav>
            <li class="logo">AllergenGo</li>
        </nav>
    </div>
    
    <div class="container py-4">
        @yield('content')
    </div>

</body>
</html>
