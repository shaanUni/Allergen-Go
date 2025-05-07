<h1>QR Code for {{ $restaurantCode }}</h1>

<div>
    {!! QrCode::size(250)->generate($url) !!}
</div>

<p>Scan this code to view your public page:</p>
<p><strong>{{ $url }}</strong></p>
