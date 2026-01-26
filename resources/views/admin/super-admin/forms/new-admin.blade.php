@extends('admin.layout')

@section('content')
  <div class="container register-page">
    <div class="register-card">
      <div class="register-info">
        
      <h1 class="register-title">Create New Admin Account</h1>
        <p class="register-desc">
         
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

        <form method="POST" action="{{ route('admin.super-admin.submit') }}" class="register-form">
          @csrf

          <div class="form-group">
            <label for="name">Restaurant Name</label>
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
          </div>

          <div class="form-group">
            <label for="city">City</label>
            <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}" >
          </div>

          <div class="form-group">
            <label for="street">Street/Area</label>
            <input id="street" type="text" class="form-control" name="street" value="{{ old('street') }}" >
          </div>

          <div class="form-group">
            <label for="postcode">Postcode</label>
            <input id="postcode" type="text" class="form-control" name="postcode" value="{{ old('postcode') }}" >
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" class="form-control" name="password" required>
          </div>

          <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
          </div>

          <button type="submit" class="btn btn-primary create-account-submit btn-submit w-100">
            Create Account
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection