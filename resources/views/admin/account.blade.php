@extends('admin.layout')

@section('content')
    <div class="admin-dashboard">
        <h1 class="dashboard-title">Hello</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if ($cancelled == 'true')
            <p>You cancelled your subscription. {{ $date }}
            <p>
        @else

                    <form method="POST" action="{{ route('admin.subscription.cancel') }}">
                        @csrf
                        <button type="submit" class="btn-logout">Cancel subscription</button>
                    </form>

                </div>
            @endif
    @if ($cancelled == '')

        <p>Next payment: </p>
        <p>£30 on {{ $date }}</p>
    @endif
    <h1>Billing History</h1>

@if ($invoices->isEmpty())
    <p>You have no invoices yet.</p>
@else
    <table class="table-auto w-full">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left">Date</th>
                <th class="px-4 py-2 text-left">Amount</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">View PDF</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $invoice)
                <tr class="border-b">
                    {{-- Format the date --}}
                    <td class="px-4 py-2">{{ $invoice->date()->format('d M Y') }}</td>

                    {{-- Convert cents to main currency unit (e.g. 500 → 5.00) --}}
                    <td class="px-4 py-2">
                        {{ $invoice->total()}}
                    </td>

                    {{-- Show “Paid” vs. “Pending” --}}
                    <td class="px-4 py-2">
                        @if ($invoice->total() == "£0.00" )
                        trial
                        @elseif($invoice->paid)
                        <span class="text-green-600">Paid</span>
                        @else
                            <span class="text-yellow-600">Pending</span>
                        @endif
                    </td>

                    {{-- Link to Stripe’s PDF (hosted) --}}
                    <td class="px-4 py-2">
                        @if ($invoice->invoice_pdf)
                            <a href="{{ $invoice->invoice_pdf }}" target="_blank" class="text-blue-600 underline">
                                Download
                            </a>
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif







<div class="max-w-lg mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Account & Billing</h1>

    {{-- Display flash messages --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- 1) Show Current Card (if exists) --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold">Current Payment Method</h2>
        @if($defaultMethod)
            <p>
                Card ending in <strong>{{ $defaultMethod->card->last4 }}</strong><br>
                Expires {{ $defaultMethod->card->exp_month }}/{{ $defaultMethod->card->exp_year }}
            </p>
        @else
            <p class="text-gray-600">No card on file.</p>
        @endif
    </div>
    <div class="mb-6">
    <h2 class="text-xl font-semibold">Saved Payment Methods</h2>

    @if($paymentMethods->isEmpty())
        <p class="text-gray-600">No card on file.</p>
    @else
        <ul>
            @foreach($paymentMethods as $method)
                <li class="mb-2">
                    Card ending in <strong>{{ $method->card->last4 }}</strong><br>
                    Expires {{ $method->card->exp_month }}/{{ $method->card->exp_year }}<br>
                    Brand: {{ ucfirst($method->card->brand) }}
                </li>
            @endforeach
        </ul>
    @endif
</div>


    {{-- 2) “Update Card” Form --}}
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold mb-2">Update Card Details</h2>
        <p class="text-sm text-gray-600 mb-4">
            Enter a new card below and click “Save” to replace your existing payment method.
        </p>

        {{-- This form does NOT contain any real card inputs. Stripe.js will mount the element. --}}
        <form id="update-card-form" action="{{ route('admin.account.updateCard') }}" method="POST">
            @csrf

            {{-- Placeholder for Stripe.js Card Element --}}
            <div id="card-element" class="mb-4 border rounded p-3">
                <!-- A Stripe Element will be inserted here. -->
            </div>

            {{-- Hidden input to store the returned PaymentMethod ID --}}
            <input type="hidden" name="payment_method" id="payment_method_input">

            <button
                id="submit-btn"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
                Save New Card
            </button>
        </form>

        {{-- Display any errors from Stripe.js --}}
        <div id="card-errors" role="alert" class="mt-3 text-red-600"></div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- 3) Load Stripe.js --}}
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 4) Initialize Stripe with your publishable key
            //    (Use the same STRIPE_KEY from your .env / config/services.php)
            const stripe = Stripe("{{ config('services.stripe.key') }}");

            // 5) Create a Card Element
            const elements = stripe.elements();
            const style = {
                base: {
                    fontSize: '16px',
                    color: '#32325d',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                },
                invalid: {
                    color: '#fa755a',
                },
            };
            const cardElement = elements.create('card', { style });
            cardElement.mount('#card-element');

            // 6) Handle real-time validation errors from the Element
            cardElement.addEventListener('change', function (event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // 7) When the form is submitted...
            const form = document.getElementById('update-card-form');
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                // Disable button to prevent multiple clicks
                document.getElementById('submit-btn').disabled = true;

                // Confirm the SetupIntent using the client_secret from the controller
                stripe.confirmCardSetup("{{ $intent->client_secret }}", {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            // Optionally send the admin’s name/email to Stripe for receipts
                            name: "{{ Auth::guard('admin')->user()->name }}",
                            email: "{{ Auth::guard('admin')->user()->email }}"
                        }
                    }
                }).then(function (result) {
                    if (result.error) {
                        // Display error.message in your UI
                        document.getElementById('card-errors').textContent = result.error.message;
                        document.getElementById('submit-btn').disabled = false;
                    } else {
                        // The SetupIntent has succeeded. Grab the payment_method ID…
                        const paymentMethodId = result.setupIntent.payment_method;
                        // Put it into the hidden input and submit the form to your server
                        document.getElementById('payment_method_input').value = paymentMethodId;
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection