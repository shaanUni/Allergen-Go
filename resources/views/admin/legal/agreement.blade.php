@extends('admin.layout')

@section('content')
<div class="page-header">

<a href="javascript:window.history.back()" class="back-link">Go Back</a>
</div>
<article class="privacy-policy">
  <h1>Restaurant Customer Agreement</h1>
  <p class="effective-date"><strong>Effective Date:</strong> 10/07/2025 <span><strong>Version:</strong> 1.0</span> <span><strong>Provider:</strong> AllergenGo</span> <span><strong>Contact:</strong> support@allergengo.com</span></p>

  <section class="legal-section">
    <h2>1. Overview</h2>
    <p>This Customer Agreement sets out the commercial terms under which your restaurant (“You”) may access and use the AllergenGo service (“Service”). This Agreement is binding when you create an account and agree to these terms before payment.</p>
  </section>

  <section class="legal-section">
    <h2>2. What You Get</h2>
    <ul>
      <li>Our online software for receiving user allergy submissions, and automatically returning edible dishes.</li>
      <li>An admin dashboard for viewing selected dishes per allergy and more analytics.</li>
      <li>Support via email during UK business hours.</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>3. License Scope</h2>
    <h3>Single Location</h3>
    <ul>
      <li>A standard subscription covers one physical restaurant location.</li>
      <li>Additional locations require additional/separate subscriptions, with different emails.</li>
    </ul>
    <h3>Multi-Site or Chains</h3>
    <p>If you operate multiple restaurants or locations under one brand, you must:</p>
    <ul>
      <li>Contact us to arrange a multi-location agreement (or have each branch purchase AllergenGo separately with a seperate email).</li>
      <li>Register and pay for each site or secure a chain-wide license (contact us).</li>
      <li>You may not share login credentials or access across unauthorized sites or users.</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>4. Payment Terms</h2>
    <ul>
      <li>Subscription fee: £30/month per location.</li>
      <li>Billing is handled via Stripe.</li>
      <li>Subscriptions auto-renew monthly unless canceled in advance.</li>
      <li>If you cancel, you retain access until the end of the billing period.</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>5. Access Restrictions</h2>
    <p>Access is limited to one location per subscription. (This is enforced with an IP checker).</p>
  </section>

  <section class="legal-section">
    <h2>6. Cancellation</h2>
    <p>You may cancel your subscription at any time through your account dashboard. Your access will continue until the end of the current billing cycle.</p>
  </section>

  <section class="legal-section">
    <h2>7. Your Responsibilities</h2>
    <ul>
      <li>Treat allergy data responsibly and confidentially</li>
      <li>Train your staff on how to interpret allergy flags</li>
      <li>Follow all relevant UK food safety and allergen laws</li>
      <li>Not misuse or distribute the Service or its data</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>8. Terms of Service</h2>
    <p>This Agreement supplements our full Terms of Service and Privacy Policy, which govern all use of the platform, including:</p>
    <ul>
      <li>Liability limitations</li>
      <li>Data handling</li>
      <li>Acceptable use</li>
      <li>Legal jurisdiction</li>
    </ul>
    <p>By agreeing to this Customer Agreement, you also agree to be bound by those documents.</p>
  </section>

  <section class="legal-section">
    <h2>9. Contact</h2>
    <p>If you have questions about this Agreement, please contact us at: <a href="mailto:support@allergengo.com">support@allergengo.com</a></p>
  </section>

  <section class="legal-section">
    <h2>Summary</h2>
    <ul>
      <li>£30/month per location</li>
      <li>One location/branch per subscription</li>
      <li>Chain licenses available — contact us or have each branch purchase AllergenGo seperately with different emails.</li>
      <li>Stripe handles billing</li>
      <li>Bound by this Agreement + full Terms of Service</li>
    </ul>
  </section>
</article>

@endsection
