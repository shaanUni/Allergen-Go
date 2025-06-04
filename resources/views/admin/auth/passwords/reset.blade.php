@extends('admin.layout')

@section('content')
    <h2>Reset Your Password</h2>

    <form method="POST" action="{{ route('admin.password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="email" name="email" value="{{ old('email', $email) }}" required>
        <input type="password" name="password" required placeholder="New password">
        <input type="password" name="password_confirmation" required placeholder="Confirm password">
        <button type="submit">Reset Password</button>
    </form>
@endsection
