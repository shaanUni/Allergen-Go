@extends('admin.layout')

@section('content')
<div class="admin-dashboard">
    <h1 class="dashboard-title">Welcome to the Admin Dashboard</h1>
    <p class="dashboard-subtitle">This is your secure area to manage content and settings.</p>

    <div class="dashboard-nav">
        <a href="{{ route('admin.dishes.index') }}" class="dashboard-link">Manage Dishes</a>
        <a href="{{ route('admin.qrcode') }}" class="dashboard-link">QR Code</a>
        <a href="{{ route('admin.stats') }}" class="dashboard-link">View Stats</a>
    </div>
    @if ($showIpForm == 1)
    <div class="modal fade" id="consentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">You have logged in from a new IP</h5>
                    </div>
                    <div class="modal-body">
                        <p>
                    We can see you have logged in from a new IP. This either means you are in a new location, or your IP has changed.
                    If you want to update your current IP, please click accept. If not, click decline, but you will not be able to use the app.
                    We do this to protect agaisnt password sharing, and for increased security.         
                    </p>
                            <br><p>You can read more in our  <a class="green-link" href="{{ route('privacy.policy') }}">Privacy Policy</a> and   <a class="green-link" href="{{ route('terms.of.service') }}">Terms of service</a>.</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" id="declineConsent">Decline</button>
                        <button type="button" class="btn btn-primary" id="acceptConsent">Accept</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
