@extends('admin.layout')

@section('content')
<div class="admin-dashboard">
    <h1 class="dashboard-title">Hello</h1>
    <form method="POST" action="{{ route('admin.subscription.cancel') }}">
    @csrf
    <button type="submit" class="btn-logout">Cancel subscription</button>
    </form>
    
</div>
@endsection
