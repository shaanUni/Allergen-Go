@extends('emails.layout')

@section('title', 'Account Created')

@section('content')
<p>Hi there,</p>

<p>Thanks for signing up with <strong>AllergenGo</strong>! Your free trial is active and will expire on: <strong>{{ $date }}</strong>.</p>

<p>You’ll have full access to our platform—designed to help you easily manage and share allergen and dietary information with your customers. We’re here to make providing information simple and dining safer for everyone.</p>
<p>Once the trial is over, billing was start. Head to the account page at anytime to cancel.</p>

<p>
  If you have any questions or need help getting started, don’t hesitate to contact us — we're always happy to assist. 
  <a href="mailto:support@allergengo.com" style="color: #28a745; font-weight: bold; text-decoration: none;">support@allergengo.com</a>
</p>

<p style="font-family: Arial, sans-serif; color: #333;">Helpful links:</p>
<div style="margin-top: 4px; font-family: Arial, sans-serif;">
  <a href="{{ route('admin.agreement') }}" 
     style="color: #28a745; text-decoration: none; display: block; margin-bottom: 6px;">
    Restaurant Customer Agreement
  </a>
  <a href="{{ route('admin.terms.of.service') }}" 
     style="color: #28a745; text-decoration: none; display: block;">
    Terms of Service
  </a>
</div>


<p>Thanks again for choosing AllergenGo—we’re excited to have you on board!</p>

@endsection
