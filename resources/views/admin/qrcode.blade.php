@extends('admin.layout')

@section('content')
<form action="{{ route('admin.dashboard') }}" method="get" style="display:inline;">
  <button type="submit" class="back-button">Back to Dashboard</button>
</form>

<div class="qr-page">
    <div class="qr-header">
        <h1>QR Code for <span>{{ $restaurantCode }}</span></h1>
        <p class="qr-subtitle">Scan or print this code to access your public page.</p>
    </div>

    <div class="qr-card" id="qr-code-section">
        <div class="qr-image">
            {!! QrCode::size(250)->generate($url) !!}
        </div>
        <p class="qr-url">{{ $url }}</p>
    </div>

    <button onclick="printQRCode()" class="btn-print">🖨️ Print QR Code</button>
</div>

<script>
    function printQRCode() {
        const qrContent = document.getElementById('qr-code-section').innerHTML;
        const printWindow = window.open('', '', 'height=500,width=500');
        printWindow.document.write('<html><head><title>Print QR Code</title>');
        printWindow.document.write('<style>body{display:flex;justify-content:center;align-items:center;height:100%;font-family:Inter,sans-serif;} img{width:250px;height:250px;}</style>');
        printWindow.document.write('</head><body >');
        printWindow.document.write(qrContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>
@endsection
