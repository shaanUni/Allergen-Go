<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f9fafb;
      margin: 0;
      padding: 2rem;
      color: #333;
    }

    .container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    h1 {
      color: #2d6c44;
    }

    a.button {
      display: inline-block;
      padding: 0.75rem 1.5rem;
      background: #40945c;
      color: white !important;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      margin-top: 1rem;
    }

    .footer {
      text-align: center;
      font-size: 0.875rem;
      color: #666;
      margin-top: 2rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Hello!</h1>
    <p>Click the button below to reset your password.</p>

    <a href="{{ $resetUrl }}" class="button">Reset Password</a>

    <p>If you didn’t request this, you can safely ignore this email.</p>

    <p>Regards,<br>AllergenGo Team</p>

    <div class="footer">
      AllergenGo — Allergy-Safe Dining Made Simple
    </div>
  </div>
</body>
</html>
