@extends('admin.layout')

@section('content')
<div class="page-header">

<a href="javascript:window.history.back()" class="back-link">Go Back</a>
</div>
<article class="privacy-policy">
  <h1>Terms of Service – Restaurants</h1>
  <p class="effective-date">
    <strong>Effective Date:</strong> 10/07/2025
    <strong>Last Updated:</strong> 10/07/2025
  </p>

  <p>These Terms of Service (“Terms”) govern your access to and use of AllergenGo (“the Service”), provided by AllergenGo Ltd (“we”, “us”, or “our”). By using the Service, you agree to these Terms.</p>

  <section class="legal-section">
    <h2>1. Service Description</h2>
    <p>We provide a tool that helps restaurants:</p>
    <ul>
      <li>Receive and review allergy information from users</li>
      <li>Offer dish recommendations based on allergy profiles</li>
      <li>View anonymised statistics (if opted in by the user) to improve menu safety</li>
    </ul>
    <p>The Service is intended as a decision-support tool for users, not a substitute for your own food safety procedures or allergen controls.</p>
  </section>

  <section class="legal-section">
    <h2>2. Your Responsibilities</h2>
    <p>By using our Service, you agree to:</p>
    <ul>
      <li>Treat user allergy data confidentially and responsibly</li>
      <li>Ensure staff are trained to interpret and act on allergy information</li>
      <li>Continue to follow all legal obligations regarding allergens (e.g., UK Food Information Regulations)</li>
    </ul>
    <p>We do not guarantee that allergy data is complete or accurate, as this information is provided by users. Final responsibility for food safety remains with you.</p>
  </section>

  <section class="legal-section">
    <h2>3. Data Sharing and Use</h2>
    <p>We share only anonymised allergy data with you—no personal identifiers (e.g., name or email) are collected. You may receive:</p>
    <ul>
      <li>Allergy profiles submitted for individual users</li>
      <li>Optional anonymised statistics (e.g., “20% of users avoid dairy”)</li>
    </ul>
    <p>You may opt out of receiving statistics at any time. You may not reuse or resell this data for other purposes.</p>
  </section>

  <section class="legal-section">
    <h2>4. Fees and Payments</h2>
    <ul>
      <li>Payment is handled securely through Stripe</li>
      <li>Subscriptions auto-renew unless cancelled in advance</li>
      <li>You are responsible for ensuring your payment details are current</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>5. Limitations of Liability</h2>
    <p>We are not liable for:</p>
    <ul>
      <li>Incorrect allergy data submitted by users</li>
      <li>Allergic reactions due to food preparation or miscommunication</li>
      <li>Business losses, lost revenue, or reputational harm</li>
    </ul>
    <p>You agree that the Service is provided “as is,” with no guarantee of uninterrupted access or error-free functionality.</p>
  </section>

  <section class="legal-section">
    <h2>6. Intellectual Property</h2>
    <p>All software, trademarks, and content are owned by us. You may use the Service only for your own restaurant’s internal operations. You may not:</p>
    <ul>
      <li>Copy or reverse-engineer the software</li>
      <li>Remove branding or disclaimers</li>
      <li>Resell access to the tool</li>
    </ul>
  </section>

  <section class="legal-section">
    <h2>7. Termination</h2>
    <p>We may suspend or terminate your access if:</p>
    <ul>
      <li>You breach these Terms</li>
      <li>You misuse user data</li>
      <li>You fail to pay applicable fees</li>
    </ul>
    <p>You may cancel at any time, and you will retain access until the end of the billing period.</p>
  </section>

  <section class="legal-section">
    <h2>8. Governing Law</h2>
    <p>These Terms are governed by the laws of England and Wales. Any disputes will be subject to the exclusive jurisdiction of the UK courts.</p>
  </section>

  <section class="legal-section">
    <h2>9. Contact</h2>
    <p>For questions or legal concerns, contact us at: <a href="mailto:{{ config('service-info.support-email') }}">{{ config('service-info.support-email') }}</a></p>
  </section>
</article>

@endsection
