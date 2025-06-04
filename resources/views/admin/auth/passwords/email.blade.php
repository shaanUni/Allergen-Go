@extends('admin.layout')

@section('content')
    <h2>Reset Password</h2>

    <form method="POST" action="{{ route('admin.password.email') }}">
        @csrf
        <input type="email" name="email" required placeholder="Your email">
        <button type="submit">Send Reset Link</button>
    </form>
@endsection
