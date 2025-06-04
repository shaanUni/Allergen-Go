@extends('admin.layout')

@section('content')
<div class="container mt-5">
    <h2>Create Admin Account</h2>
    <h6>Create your account to get started. You'll then be taken to our secure payment page to complete your subscription — £30/month for full access to AllergenGo.</h6>
    <form method="POST" action="{{ route('admin.register.submit') }}">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Create Account & Subscribe</button>
    </form>
</div>
@endsection
