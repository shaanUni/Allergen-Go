@extends('admin.layout')

@section('content')
    <div class="container mt-5">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h2>You unsubscribed</h2>
        <h6>You no longger have access to AllergenGo, as you cancelled your subscription. Your data has not been lost. Once
            you re subscribe you will regain access.

        </h6>
        <form method="POST" action="{{ route('admin.subscription.buy') }}">
            @csrf
            <input type="hidden" name="payment_method" id="payment_method">
            <button type="submit">Resubscribe</button>
        </form>

    </div>
@endsection