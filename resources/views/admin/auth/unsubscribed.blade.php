@extends('admin.layout')

@section('content')
  <div class="container mt-5">
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"></button>
      </div>
    @endif

    <div class="unsubscribe-wrapper">
      <div class="card unsubscribed-card">
        <div class="card-body">
          {{-- Icon --}}
          <div class="unsubscribed-icon">
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 16 16"
                 class="bi bi-x-circle-fill"
                 fill="currentColor"
                 aria-hidden="true">
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0z
                       M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8
                       L4.646 10.646a.5.5 0 0 0 .708.708L8 8.707
                       l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8
                       l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293
                       5.354 4.646z"/>
            </svg>
          </div>

          <h2 class="card-title">You’ve Unsubscribed</h2>

          <p class="card-text">
            Your access to AllergenGo has been revoked: either because you cancelled your subscription, or you had failed payments which you failed to resolve.&nbsp;
            Don’t worry—your data is safe. Simply click the button below to resubscribe and regain access.
          </p>

          <form method="POST" action="{{ route('admin.subscription.buy') }}">
            @csrf
            <input type="hidden" name="payment_method" id="payment_method">
            <button type="submit" class="btn btn-resubscribe">
              Resubscribe
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
