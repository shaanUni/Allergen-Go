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
</div>
@endsection
