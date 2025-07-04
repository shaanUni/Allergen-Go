@extends('user.layout')

@section('content')
<div class="search-page">

  @if(session('failure'))
    <div class="alert alert-danger">
      <p>{{ session('failure') }}</p>
    </div>
  @endif

@php
    $optInPereferences = session('opt-in'); 
    $optInValue = 1;
@endphp

  @if (!$optInPereferences)
<!-- Consent Modal Markup -->
<div class="modal fade" id="consentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">Allow data usage?</h5>
      </div>
      <div class="modal-body">
        <p>We’d like your permission to collect and use your data to improve your experience. You can opt in or out at any time.</p>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" id="declineConsent">Decline</button>
        <button type="button" class="btn btn-primary"  id="acceptConsent">Accept</button>
      </div>
    </div>
  </div>
</div>
@endif
  <section class="search-section card">
    <h1 class="search-title">Search for Dishes</h1>

    <form method="POST" action="{{ route('user.searchCode') }}" class="allergy-form">
      @csrf
      @include('components.form')
      <input type="text" class="form-control textbox" name="opt-in" id="opt-in" value="{{ $optInValue }}"
      readonly required hidden>
      <input type="submit" value="Submit" class="submit-form-btn">
    </form>
  </section>

  <aside class="info-box card">
    <h2>About AllergenGo</h2>
    <p>If you're here, you probably have allergies. The code you enter will identify the restaurant you're eating at.</p>
    <p>Once you select your allergens and submit the form, we'll show you dishes that are safe for you.</p>
  </aside>

</div>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    var consentModal = document.getElementById('consentModal');

    // Show the modal manually (if not already shown by Laravel)
    consentModal.classList.add('show');
    consentModal.style.display = 'block';
    consentModal.removeAttribute('aria-hidden');
    consentModal.setAttribute('aria-modal', 'true');

    function hideModal() {
      consentModal.classList.remove('show');
      consentModal.style.display = 'none';
      consentModal.setAttribute('aria-hidden', 'true');
      consentModal.removeAttribute('aria-modal');
    }

    function sendChoice(value) {
      // Do something with the value if needed
      console.log(value);
      let opt_in = document.getElementById('opt-in');
      if(value == 0){
        opt_in.value = value;
      }
      //opt_in.
      hideModal();
    }

    document.getElementById('acceptConsent')
            .addEventListener('click', () => sendChoice(1));
    document.getElementById('declineConsent')
            .addEventListener('click', () => sendChoice(0));
  });
</script>

@endsection
