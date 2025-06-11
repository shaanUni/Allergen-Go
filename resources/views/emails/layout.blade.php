<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>@yield('title')</title>
  <style>
    :root {
      --green: #40945c;
      --green-dark: #2d6c44;
      --bg: #f9fafb;
      --text: #333333;
      --text-light: #666666;
      --radius: 8px;
    }

    body {
      margin: 0;
      padding: 0;
      background: var(--bg);
      font-family: 'Inter', sans-serif;
      color: var(--text);
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      background: #fff;
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .header {
      background: var(--green);
      color: #fff;
      padding: 1.5rem;
      text-align: center;
    }

    .header h1 {
      margin: 0;
      font-size: 1.75rem;
    }

    .content {
      padding: 2rem;
    }

    .footer {
      background: #f0f0f0;
      color: var(--text-light);
      text-align: center;
      font-size: 0.875rem;
      padding: 1rem;
    }

    a.button {
      display: inline-block;
      background: var(--green);
      color: #fff !important;
      text-decoration: none;
      padding: 0.75rem 1.5rem;
      border-radius: var(--radius);
      font-weight: 600;
      margin-top: 1.5rem;
    }

    p {
      margin: 1rem 0;
      line-height: 1.6;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="header">
      <h1>{{ config('app.name') }}</h1>
    </div>

    <div class="content">
      @yield('content')

      <p>Thanks,</p>
      <p><strong>AllergenGo Team</strong></p>
    </div>

    <div class="footer">
      AllergenGo — Allergy-Safe Dining Made Simple
    </div>
  </div>

</body>
</html>
