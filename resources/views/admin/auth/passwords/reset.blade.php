@extends('admin.layout')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <h2 class="auth-title">Reset your password</h2>
        <p class="auth-subtitle">Choose a strong password you don’t use elsewhere.</p>

        <form method="POST" action="{{ route('admin.password.update') }}" class="auth-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <label class="auth-label" for="email">Email</label>
            <input id="email" class="auth-input" type="email" name="email"
                   value="{{ old('email', $email) }}" required>

            <label class="auth-label" for="password">New password</label>
            <input id="password" class="auth-input" type="password" name="password"
                   required placeholder="New password">

            <label class="auth-label" for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation"
                   required placeholder="Confirm password">

            <button type="submit" class="auth-btn">Reset password</button>
        </form>
    </div>
</div>
@endsection
