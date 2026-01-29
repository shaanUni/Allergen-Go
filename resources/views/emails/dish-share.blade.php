@extends('emails.layout')

@section('title', 'Account Created')

@section('content')
  <p>Someone wants to share a dish with you. {{ $email }}</p>

  <a href="{{ URL::signedRoute('admin.dish-share.accept', $uuid) }}" class="button">Accept</a>
  <a href="{{ URL::signedRoute('admin.dish-share.decline', $uuid) }}" class="button">Decline</a>

@endsection
