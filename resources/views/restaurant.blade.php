@extends('layout')

@section('content')

    <section class="hero">
        <div class="container">
            <h1>Safe, simple dining for people with allergies</h1>
            <p>Add your dishes and allergens onto the app, and generate a QR code users can scan to filter these dishes
                based on their allergies.</p>
            <a href="{{ route('user.search') }}" class="btn btn-primary get-started-btn">Get Started</a>
        </div>
    </section>

    <section id="features" class="container">
        <h2 class="section-title">Powerful Features</h2>
        <div class="features-grid">
            <div class="feature">
                <h3>Real-Time Statistics</h3>
                <p>See which allergens customers report most often and optimize your menu for safety.</p>
            </div>
            <div class="feature">
                <h3>You add the dishes</h3>
                <p>Add all your dishes and their allergens, so users can see the information.</p>
            </div>
            <div class="feature">
                <h3>QR Code Menu Access</h3>
                <p>Generate a unique QR code so diners can view and filter your menu instantly.
                </p>
            </div>
        </div>
    </section>

    <section id="why">
        <div class="container">
            <h2 class="section-title">Why AllergenGo?</h2>
            <div class="text-block">
                <p>
                    When a person with an allergy comes to your restaurant, it often leads to them needing to speak
                    to a chef, or reading through a book. This method is outdated, unreliable, and prone to human
                    error.
                </p>

                <p>
                    With AllergenGo, you will enter all your dishes on your admin page, with all the allergens. You
                    will then be able to generate a QR code, which users will then scan.
                    Once they scan the qr code, they will enter what they are allergic to, and the app will compare
                    their allergies to the allergens in your dishes, and return a list of dishes they can eat.
                </p>

                <p>
                    You will have access to a Statistics page, where you can gain further insights and information,
                    which can help you make better informed decisions in the future.
                </p>
            </div>
        </div>
    </section>
@endsection