@extends('admin.layout')

@section('content')
    <div class="container py-4">

        {{-- Back --}}
        <form action="{{ route('admin.dashboard') }}" method="get" style="display:inline;" class="mb-4">
            <button type="submit" class="back-button btn btn-secondary">
                Back to Dashboard
            </button>
        </form>

        <div class="subscription-page">
            <div class="stats-grid">
                {{-- 1) Subscription --}}
                <div class="stats-card">
                    <h2 class="stats-title">Subscription</h2>

                    @if ($cancelled === 'true')
                        <p class="stat-info">
                            You cancelled your subscription on <strong>{{ $date }}</strong>.
                        </p>
                    @else
                        <form method="POST" action="{{ route('admin.subscription.cancel') }}" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                Cancel subscription
                            </button>
                        </form>

                        @if ($cancelled === '')
                            <p class="stat-info">
                                Next payment: <strong>£30 on {{ $date }}</strong>
                            </p>
                        @endif
                    @endif
                </div>

                {{-- 2) Billing History --}}
                <div class="stats-card">
                    <h2 class="stats-title">Billing History</h2>

                    @if ($invoices->isEmpty())
                        <p class="stat-info">You have no invoices yet.</p>
                    @else
                        <div class="table-wrapper mt-2">
                            <table class="dish-counts-table w-full">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>View PDF</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->date()->format('d M Y') }}</td>
                                            <td>{{ $invoice->total() }}</td>
                                            <td>
                                                @if ($invoice->total() == "£0.00")
                                                    trial
                                                @elseif($invoice->paid)
                                                    <span class="text-green-600">Paid</span>
                                                @else
                                                    <span class="text-yellow-600">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($invoice->invoice_pdf)
                                                    <a href="{{ $invoice->invoice_pdf }}"
                                                       target="_blank"
                                                       class="btn btn-link btn-sm p-0 align-baseline">
                                                        Download
                                                    </a>
                                                @else
                                                    &mdash;
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- 3) Account & Billing --}}
                <div class="stats-card">
                    <h2 class="stats-title">Account &amp; Billing</h2>

                    {{-- Current Method --}}
                    <div class="mb-3">
                        <h3 class="font-semibold">Current Payment Method</h3>
                        @if($defaultMethod)
                            <p>
                                Card ending in <strong>{{ $defaultMethod->card->last4 }}</strong><br>
                                Expires {{ $defaultMethod->card->exp_month }}/{{ $defaultMethod->card->exp_year }}
                            </p>
                        @else
                            <p class="text-gray-600">No card on file.</p>
                        @endif
                    </div>

                    {{-- Saved Methods --}}
                    <div class="mb-3">
                        <h3 class="font-semibold">Saved Payment Methods</h3>
                        @if($paymentMethods->isEmpty())
                            <p class="text-gray-600">No card on file.</p>
                        @else
                            <ul class="list-unstyled">
                                @foreach($paymentMethods as $method)
                                    <li class="border p-3 rounded mb-2">
                                        <p>
                                            Card ending in <strong>{{ $method->card->last4 }}</strong><br>
                                            Expires {{ $method->card->exp_month }}/{{ $method->card->exp_year }}<br>
                                            Brand: {{ ucfirst($method->card->brand) }}
                                        </p>

                                        @if($method->id === $admin->default_payment_method)
                                            <span class="text-green-600 font-semibold">Default</span>
                                        @else
                                            <form action="{{ route('admin.payment-methods.default', $method->id) }}"
                                                  method="POST"
                                                  class="d-inline me-2">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-outline-success btn-sm">
                                                    Make Default
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.payment-methods.delete', $method->id) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this card?')">
                                                Delete
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                {{-- 4) Update Card Details --}}
                <div class="stats-card">
                    <h2 class="stats-title">Update Card Details</h2>
                    <p class="stat-info mb-3">
                        Enter a new card below and click “Save” to replace your existing payment method.
                    </p>

                    <form id="update-card-form"
                          action="{{ route('admin.payment-methods.update-card') }}"
                          method="POST">
                        @csrf

                        <div id="card-element" class="border p-3 rounded mb-3">
                            <!-- Stripe Element mounts here -->
                        </div>

                        <input type="hidden" name="payment_method" id="payment_method_input">

                        <button id="submit-btn"
                                type="submit"
                                class="btn btn-primary w-100">
                            Save New Card
                        </button>
                    </form>

                    <div id="card-errors"
                         role="alert"
                         class="mt-2 text-danger small">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Load Stripe.js --}}
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stripe = Stripe("{{ config('services.stripe.key') }}");
            const elements = stripe.elements();
            const style = {
                base: {
                    fontSize: '16px',
                    color: '#32325d',
                    '::placeholder': { color: '#aab7c4' }
                },
                invalid: { color: '#fa755a' },
            };
            const cardElement = elements.create('card', { style });
            cardElement.mount('#card-element');

            cardElement.on('change', event => {
                const displayError = document.getElementById('card-errors');
                displayError.textContent = event.error ? event.error.message : '';
            });

            document.getElementById('update-card-form')
                    .addEventListener('submit', e => {
                e.preventDefault();
                const btn = document.getElementById('submit-btn');
                btn.disabled = true;

                stripe.confirmCardSetup("{{ $intent->client_secret }}", {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: "{{ Auth::guard('admin')->user()->name }}",
                            email: "{{ Auth::guard('admin')->user()->email }}"
                        }
                    }
                }).then(result => {
                    if (result.error) {
                        document.getElementById('card-errors').textContent = result.error.message;
                        btn.disabled = false;
                    } else {
                        document.getElementById('payment_method_input').value =
                            result.setupIntent.payment_method;
                        e.target.submit();
                    }
                });
            });
        });
    </script>
@endsection
