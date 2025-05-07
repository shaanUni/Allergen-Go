@extends('user.layout')

@section('content')
    @if(session('failure'))
        <div class="alert alert-danger">
            {{ session('failure') }}
        </div>
    @endif

    <h2>search, User!</h2>
<form method="POST" action="{{ route('user.searchCode') }}">
    @include('user.form')
</form>
@endsection