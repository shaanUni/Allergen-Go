@extends('emails.layout')

@section('title', 'Account Created')

@section('content')
  <p>Thanks for creating an account with AllergenGo, your free trial ends:  <strong>{{ $date }}</strong>.</p>
  <p>Please reach out if you need anything, and thanks for using AllergenGo!</p>
@endsection
