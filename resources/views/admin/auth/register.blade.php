@extends('admin.layout')

@section('content')
  <div class="container register-page">
    <div class="register-card">
      <div class="register-info">
        <h1 class="register-title">Create Admin Account</h1>
        <p class="register-desc">
          Create your account to get started. You’ll then be taken to our secure payment page to complete
          your subscription — £30/month for full access to AllergenGo.
        </p>
      </div>

      <div class="register-form-wrapper">
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('admin.register') }}" class="register-form">
          @csrf

          <div class="form-group">
            <label for="name">Name</label>
            <input id="name" type="text"
                   class="form-control"
                   name="name"
                   value="{{ old('name') }}"
                   required autofocus>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email"
                   class="form-control"
                   name="email"
                   value="{{ old('email') }}"
                   required>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password"
                   class="form-control"
                   name="password"
                   required>
          </div>

          <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password"
                   class="form-control"
                   name="password_confirmation"
                   required>
          </div>

          <button type="submit" class="btn btn-primary create-account-submit btn-submit w-100">
            Create Account &amp; Subscribe
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection
