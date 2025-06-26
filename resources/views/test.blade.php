<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5"/>
  <title>Admin – Subscription</title>
  <!-- your compiled CSS (bootstrap + admin.scss) -->
  @vite(['resources/js/admin.js'])

</head>
<body>
  <!-- NAV -->
  <nav class="admin-navbar admin-navbar--green">
    <div class="admin-navbar__container">
      <a href="#" class="admin-navbar__brand">AllergenGo Admin Panel</a>
      <div class="admin-navbar__right">
        <span class="admin-navbar__greeting">Hi, Admin</span>
        <button class="btn btn-outline-light btn-sm">Logout</button>
      </div>
    </div>
  </nav>

  <div class="container py-4">
    <!-- BACK -->
    <form action="#" method="get" style="display:inline;" class="mb-4">
      <button type="submit" class="back-button btn btn-secondary">
        Back to Dashboard
      </button>
    </form>

    <div class="subscription-page">
      <div class="stats-grid">
        <!-- 1) Subscription -->
        <div class="stats-card">
          <h2 class="stats-title">Subscription</h2>
          <form method="POST" action="#" class="mb-3">
            <button type="submit" class="btn btn-danger w-100">
              Cancel subscription
            </button>
          </form>
          <p class="stat-info">
            Next payment: <strong>£30 on July 26, 2025</strong>
          </p>
        </div>

        <!-- 2) Billing History -->
        <div class="stats-card">
          <h2 class="stats-title">Billing History</h2>
          <div class="table-wrapper">
            <table class="dish-counts-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>View PDF</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>26 Jun 2025</td>
                  <td>£0.00</td>
                  <td>trial</td>
                  <td>
                    <a href="#"
                       class="btn btn-link btn-sm p-0 align-baseline"
                       target="_blank">
                      Download
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 3) Account & Billing -->
        <div class="stats-card">
          <h2 class="stats-title">Account &amp; Billing</h2>
          <div class="mb-3">
            <h3 class="font-semibold">Current Payment Method</h3>
            <p class="text-gray-600">No card on file.</p>
          </div>
          <div class="mb-3">
            <h3 class="font-semibold">Saved Payment Methods</h3>
            <ul class="list-unstyled">
              <li class="border p-3 rounded mb-2">
                <p>
                  Card ending in <strong>4242</strong><br/>
                  Expires 3/2033<br/>
                  Brand: Visa
                </p>
                <button class="btn btn-outline-success btn-sm me-2">Make Default</button>
                <button class="btn btn-outline-danger btn-sm">Delete</button>
              </li>
            </ul>
          </div>
        </div>

        <!-- 4) Update Card Details -->
        <div class="stats-card">
          <h2 class="stats-title">Update Card Details</h2>
          <p class="stat-info mb-3">
            Enter a new card below and click “Save” to replace your existing payment method.
          </p>
          <form id="update-card-form" action="#" method="POST">
            <div id="card-element" class="border p-3 rounded mb-3">
              <!-- Stripe Element mounts here -->
            </div>
            <button id="submit-btn"
                    type="submit"
                    class="btn btn-primary w-100">
              Save New Card
            </button>
          </form>
          <div id="card-errors" role="alert" class="mt-2 text-danger small"></div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://js.stripe.com/v3/"></script>
  <script>
    // Stripe.js init…
  </script>
</body>
</html>
