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
                        {{ $invoice->currency}}
                    </td>

                    {{-- Show “Paid” vs. “Pending” --}}
                    <td class="px-4 py-2">
                        @if ($invoice->paid)
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
@endsection