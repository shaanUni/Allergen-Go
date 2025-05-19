@extends('layout')

@section('content')
    <section class="hero">
        <div class="container">
            <h1>Safe, simple dining for people with allergies</h1>
            <p>Scan any restaurant’s QR code, filter out your allergens, and instantly see only the dishes you can
                eat.</p>
            <a href="{{ route('user.search') }}" class="btn btn-primary get-started-btn">Get Started</a>
        </div>
    </section>

    <section id="features" class="container">
        <h2 class="section-title">Powerful Features</h2>
        <div class="features-grid">
            <div class="feature">
                <h3>Search Dishes</h3>
                <p>Scan a QR code, add your allergens, and see which dishes are suitable for you to eat.</p>
            </div>
            <div class="feature">
                <h3>Fully Accurate</h3>
                <p>Feel confident knowing that the restaurant themselves at added the dishes and it's details.</p>
            </div>
            <div class="feature">
                <h3>Other Dietary Needs</h3>
                <p>No allergies, but looking for halal dishes? AllergenGo can search by halal dishes, so we have you
                    covered.
                </p>
            </div>
        </div>
    </section>

    <section id="why">
        <div class="container">
            <h2 class="section-title">Why AllergenGo?</h2>
            <div class="text-block">
                <p>
                    When you go out to eat with allergies you often need to speak
                    to a chef, or read through a book. This method is outdated, unreliable, and prone to human
                    error.
                </p>

                <p>
                    With AllergenGo, you will scan a QR code, provided by the restaurant you are eating at.
                    You will then enter your allergies and any other dietary restrictions you have, using our simple
                    checkbox interface. You will then receive a list of edible dishes, which will help you decide which dish
                    to buy.
                </p>

                <p>
                    Best of all, it's free! So what do you have to lose? The traditional methods of
                    finding a meal to eat is still there - however, after trying our site, you won't want
                    to go back!
                </p>
            </div>
        </div>
    </section>
@endsection