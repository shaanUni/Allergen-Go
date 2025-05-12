<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, maximum-scale=5, width=device-width"/>
    <meta name="author" content="">
    <meta name="csrf_token" content="{{ csrf_token() }}"/>
    <title>User Area</title>
    @vite(['resources/js/app.js'])
</head>
<body>
    <div class="container py-4">
        @yield('content')
    </div>
</body>
</html>
