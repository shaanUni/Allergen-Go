@extends('admin.layout')

@section('content')
    <div class="container register-page">
        <div class="register-card">
            <div class="register-info">
                <h1 class="register-title">Create Organisation Account</h1>
                <p class="register-desc">
                    You are on the multi branch page, meaning you will be purchasing for multible branches.
                    You will do this by creating the parent Organisation account here, which will give you
                    access to a dashboard where you can add however many users you paid for.
                    <br>
                    <br>
                    It is £{{ config('service-info.monthly_price') }}/month for full access to AllergenGo.
                    You will not be charged until 2 weeks later.
                </p>
                <div class="register-legal-links documents">
                    <a class="green-link legal-long" href="{{ route('admin.agreement') }}">Restaurant Customer Agreement</a>
                    <a class="green-link" href="{{ route('admin.terms.of.service') }}">Terms of service</a>
                </div>
                <br>
                <br>
                <p><strong>
                        If you would like to discuss purchasing with sales, please email { email here} instead.
                    </strong>
                </p>
            </div>

            <div class="register-form-wrapper">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.register.submit') }}" class="register-form">
                    @csrf

                    <div class="form-group">
                        <label for="name">Organisation Name</label>
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required
                            autofocus>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity:<span class="tt" tabindex="0" aria-label="Help: Dish Name">
                                ?
                                <span class="tt__text">How many branches/locations do you want to purchase for.</span>
                            </span></label>
                        <input class="form-control" type="number" id="quantity" name="quantity" min="2"
                            value="{{ old('quantity') }}">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="form-control" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation"
                            required>
                    </div>

                    <button type="submit" class="btn btn-primary create-account-submit btn-submit w-100">
                        Create Account
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection