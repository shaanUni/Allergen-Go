@extends('user.layout')

@section('content')
<div class="page-header">

<a href="javascript:window.history.back()" class="back-link">Go Back</a>
</div>

<article class="privacy-policy">
  <h1>Privacy Policy</h1>
  <p class="effective-date"><strong>Effective Date:</strong> [Insert Date]</p>

  <p>We take your privacy seriously. This Privacy Policy explains what data we collect, how we use it, and your rights under the UK General Data Protection Regulation (UK GDPR) and the Data Protection Act 2018.</p>

  <section>
    <h2>1. What We Collect</h2>
    <p>We only collect information about your food allergies — no names, emails, or other personal identifiers.</p>
  </section>

  <section>
    <h2>2. Why We Collect It</h2>
    <ul>
      <li>Help you find dishes that are suitable for your allergies</li>
      <li>Inform the restaurant you are ordering from so they can consider your dietary needs</li>
      <li>Generate anonymous statistics (e.g. allergy trends by dish) to help restaurants improve food safety — you can opt out of this.</li>
    </ul>
  </section>

  <section>
    <h2>3. Legal Basis for Processing</h2>
    <p>Under the UK GDPR, your allergy data is classified as “special category data”, which means we need a lawful basis to process it. Our legal basis is:</p>
    <ul>
      <li>Your explicit consent — we ask for your permission before collecting or sharing your allergy data</li>
    </ul>
    <p>You can withdraw your consent at any time by contacting us (see contact details below).</p>
  </section>

  <section>
    <h2>4. Who We Share It With</h2>
    <p>We only share your allergy data with:</p>
    <ul>
      <li>The restaurant you are ordering from, to ensure your food is prepared appropriately</li>
      <li>Our own development team, who maintain the system and ensure it works properly</li>
    </ul>
    <p>We do not sell your data or share it with any third parties for marketing purposes. Restaurants may receive aggregate, anonymised statistics (e.g. “10% of users were allergic to peanuts”), and you can opt out of giving this data.</p>
  </section>

  <section>
    <h2>5. How Long We Keep Your Data</h2>
    <p>We retain allergy data for up to 6 years, after which it will be permanently deleted. This helps us to improve and audit our allergy-matching systems, and provide data consistency if you use our service again within that period.</p>
    <p>If you would like your data to be deleted earlier, please contact us (see below).</p>
  </section>

  <section>
    <h2>6. Your Rights</h2>
    <p>Under the UK GDPR, you have the right to:</p>
    <ul>
      <li>Access the data we hold about you</li>
      <li>Correct any errors in your allergy information</li>
      <li>Request deletion of your data</li>
      <li>Withdraw your consent at any time</li>
      <li>Lodge a complaint with the Information Commissioner’s Office (ICO)</li>
    </ul>
    <p>You can exercise any of these rights by emailing us at: [insert contact email]</p>
  </section>

  <section>
    <h2>7. International Transfers</h2>
    <p>We store and process data using secure UK-based or GDPR-compliant cloud services. If any data is processed outside the UK, we ensure it is subject to appropriate safeguards as required by law (e.g., Standard Contractual Clauses or equivalent mechanisms).</p>
  </section>
</article>

@endsection