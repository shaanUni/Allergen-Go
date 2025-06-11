@extends('emails.layout')

@section('title', 'Account Deleted')

@section('content')
  <p>We’re sorry to see you go. You have access until <strong>{{ $date }}</strong>.</p>
  <p>If there’s anything we could’ve done better, please let us know!</p>
@endsection
