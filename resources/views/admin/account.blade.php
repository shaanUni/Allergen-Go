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
    <p>You cancelled your subscription.<p>
    @else
    
    <form method="POST" action="{{ route('admin.subscription.cancel') }}">
            @csrf
            <button type="submit" class="btn-logout">Cancel subscription</button>
        </form>

    </div>
    @endif
@endsection