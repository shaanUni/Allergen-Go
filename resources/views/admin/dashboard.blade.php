@extends('admin.layout')

@section('content')
<div class="admin-dashboard">
    <h1 class="dashboard-title">Welcome to the Admin Dashboard</h1>
    <p class="dashboard-subtitle">This is your secure area to manage content and settings.</p>

    <div class="dashboard-nav">
        <a href="{{ route('admin.dishes.index') }}" class="dashboard-link">Manage Dishes</a>
        <a href="{{ route('admin.share-dish.index') }}" class="dashboard-link">Share Dishes</a>
        <a href="{{ route('admin.qrcode') }}" class="dashboard-link">QR Code</a>
        <a href="{{ route('admin.stats') }}" class="dashboard-link">View Stats</a>
    </div>

    <div class="code-box">
        @if ($restaurant_code == null)
            <p class="code-warning">You need to generate a new code for users to access the app.</p>
            <form action="{{ route('admin.generate') }}" method="GET">
                @csrf
                <button type="submit" class="dashboard-button">Generate Code</button>
            </form>
        @else
            <p class="code-label">Your restaurant's unique access code:</p>
            <div class="code-display">{{ $restaurant_code }}</div>
        @endif
    </div>

    <div class="code-box">
    <p class="manual-entry documents">
            <a class="green-link" href="{{ route('admin.agreement') }}">Restaurant Customer Agreement</a>
            <a class="green-link" href="{{ route('admin.terms.of.service') }}">Terms of service</a>
        </p>
    </div>
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
            if (value == 0) {
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
