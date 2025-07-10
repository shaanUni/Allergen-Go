@extends('emails.layout')

@section('title', 'Account Created')

@section('content')
<p>Hi there,</p>

<p>Thanks for signing up with <strong>AllergenGo</strong>! Your free trial is active and will expire on: <strong>{{ $date }}</strong>.</p>

<p>During your trial, you’ll have full access to our platform—designed to help you easily manage and share allergen and dietary information with your customers. We’re here to make providing information simple and dining safer for everyone.</p>

<p>
  If you have any questions or need help getting started, don’t hesitate to contact us — we're always happy to assist. 
  <a href="mailto:support@allergengo.com" style="color: #28a745; font-weight: bold; text-decoration: none;">support@allergengo.com</a>
</p>

<p>Helpful links:</p>
<div class="register-legal-links documents">
  <a class="green-link legal-long" href="{{ route('admin.agreement') }}">Restaurant Customer Agreement</a><br>
  <a class="green-link" href="{{ route('admin.terms.of.service') }}">Terms of Service</a>
</div>

<p>Thanks again for choosing AllergenGo—we’re excited to have you on board!</p>

</div>
@endsection
