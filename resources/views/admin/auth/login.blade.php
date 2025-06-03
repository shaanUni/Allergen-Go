@extends('admin.layout')

@section('content')
<div class="admin-login">
    <h2 class="login-title">Admin Login</h2>

    @if ($errors->any())
        <div class="alert-box alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login') }}" class="login-form">
        @csrf

        <div class="form-group">
            <input type="email" name="email" placeholder="Email" class="form-control" required>
        </div>

        <div class="form-group">
            <input type="password" name="password" placeholder="Password" class="form-control" required>
        </div>

        <button type="submit" class="btn-login">Login</button>
    </form>
    <a href="{{ route('admin.register') }}">Create account here!</a>
</div>
@endsection
