<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AllergenGo — Allergy-Safe Dining Made Simple</title>


    <style>
        :root {
            --green: #40945c;
            --green-dark: #2d6c44;
            --bg: #f9fafb;
            --text: #333333;
            --text-light: #666666;
            --radius: 0.5rem;
            --transition: 0.2s ease-in-out;
            --container-width: 1200px;
        }

        * {
            box-sizing: border-box;
        }
     
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .btn {
            border-radius: var(--radius);
            font-weight: 600;
            transition: background var(--transition);
        }

        .btn-primary {
            background: var(--green);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--green-dark);
        }

        .get-started-btn {
            color: white !important;
            background-color: green !important;
            border-color: green !important;
        }

        .hero {
            background: linear-gradient(135deg, #e9f5e9, #cdeedb);
            text-align: center;
            padding: 5rem 1rem;
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--green-dark);
        }

        .hero p {
            max-width: 600px;
            margin: 0 auto 2rem;
            color: var(--text-light);
            font-size: 1.125rem;
        }

        section {
            padding: 4rem 0;
        }

        .section-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 600;
            color: var(--green-dark);
            margin-bottom: 2rem;
        }

        #features .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 2rem;
        }

        .feature {
            background: #fff;
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .feature h3 {
            margin-bottom: 0.5rem;
            color: var(--green);
            font-weight: 600;
        }

        .feature p {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        #why .text-block {
            background: #fff;
            padding: 2.5rem;
            border-radius: var(--radius);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
            max-width: 800px;
            margin: 0 auto;
        }

        #why .text-block p {
            margin-bottom: 1.5rem;
            color: var(--text-light);
        }

        footer {
            background: #f0f0f0;
            text-align: center;
            padding: 2rem 1rem;
            font-size: 0.875rem;
            color: var(--text-light);
        }

        @media (min-width: 768px) {
            .hero h1 {
                font-size: 3rem;
            }

            .hero p {
                font-size: 1.25rem;
            }
        }
    </style>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold text-success" href="#">AllergenGo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav gap-2 align-items-center">
                        <li class="nav-item">
                            <a class="btn" href="#features">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn" href="#why">Why Us?</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.search') }}" class="btn btn-primary get-started-btn">Get Started</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.login') }}" class="btn">Admin Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Safe, simple dining for people with allergies</h1>
                <p>Add your dishes and allergens onto the app, and generate a QR code users can scan to filter these dishes based on their allergies.</p>
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
    </main>

    <footer>
        AllergenGo — Allergy-Safe Dining Made Simple
    </footer>
    @vite('resources/js/app.js')

</body>

</html>
