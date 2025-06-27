@extends('admin.layout')

@section('content')
  <div class="container reset-request-page">
    <div class="reset-card">
      <h2 class="reset-title">Reset Password</h2>

      @if (session('status'))
        <div class="alert alert-success mb-4">
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('admin.password.email') }}" class="reset-form">
        @csrf

        <div class="form-group">
          <input
            type="email"
            name="email"
            class="form-control"
            placeholder="Your email address"
            required
            autofocus
          >
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-2">
          Send Reset Link
        </button>
      </form>
    </div>
  </div>
@endsection
