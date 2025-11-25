@extends('layout')

@section('content')
@if (session('message'))
    <p>{{session('message')}}</p>
@endif
@endsection