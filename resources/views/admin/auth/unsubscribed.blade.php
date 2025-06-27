@extends('admin.layout')

@section('content')
    <div class="container mt-5">
        <div class="container mt-5">

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mx-auto shadow-sm" style="max-width: 600px;">
                {{-- only this wrapper should flex/center --}}
                <div class="unsubscribe-wrapper">
                    <div class="card mx-auto shadow-sm" style="max-width: 600px;">
                            {{-- Optional “unsubscribed” icon --}}
                            <div class="mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#dc3545"
                                    class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 1 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                                </svg>
                            </div>

                            <h2 class="card-title text-danger mb-3">You’ve Unsubscribed</h2>
                            <p class="card-text text-muted mb-4">
                                You no longer have access to AllergenGo because you’ve cancelled your subscription.
                                Don’t worry—your data is safe. Simply click the button below to resubscribe and regain
                                access.
                            </p>

                            <form method="POST" action="{{ route('admin.subscription.buy') }}">
                                @csrf
                                <input type="hidden" name="payment_method" id="payment_method">
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    Resubscribe
                                </button>
                            </form>
                    </div>
                </div>
            </div>
@endsection