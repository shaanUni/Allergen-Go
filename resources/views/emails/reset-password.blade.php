@extends('emails.layout')

@section('title', 'Account Deleted')

@section('content')
  <p>Click the button below to reset your password.</p>

  <a href="{{ $resetUrl }}" class="button">Reset Password</a>

  <p>If you didn’t request this, you can safely ignore this email.</p>

@endsection