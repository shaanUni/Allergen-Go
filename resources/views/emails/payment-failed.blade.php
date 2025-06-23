@extends('emails.layout')

@section('title', 'Account Created')

@section('content')
  <p>Your most recent payment failed. You have until   <strong>{{ $date }}</strong>, then you lose access to your account.</p>
  <p>If you lose access to your account, your data will be safe, until you re-purchase AllergenGo.</p>
@endsection
