@extends('admin.layout')

@section('content')
    <div class="container mt-5">
        <h2>You unsubscribed</h2>
        <h6>You no longger have access to AllergenGo, as you cancelled your subscription. Your data has not been lost. Once
            you re subscribe you will regain access.

        </h6>
        <form method="POST" action="{{ route('admin.subscription.buy') }}">
            @csrf
            <button type="submit" class="btn-logout">Re buy subscription</button>
        </form>

    </div>
@endsection