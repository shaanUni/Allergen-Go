@extends('admin.layout')

@section('content')
    <h2>Admin Login</h2>

    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <div class="mb-3">
            <input type="email" name="email" placeholder="Email" class="form-control" required>
        </div>

        <div class="mb-3">
            <input type="password" name="password" placeholder="Password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
@endsection
