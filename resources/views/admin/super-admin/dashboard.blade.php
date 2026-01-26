@extends('admin.layout')

@section('content')
    @if (session('message'))
        <p>{{session('message')}}</p>
    @endif
    <div class="admin-dashboard">

        <h1 class="dashboard-title">Welcome to the Super Admin Dashboard</h1>
        <p class="dashboard-subtitle">Here you can manage the {{ $admin->quantity }} accounts you have paid for. Add
            accounts, remove them, or buy more. Also see
            dishes for each account. You also have access to a total stats page, where you can see all the stats combined
            for all your locations,
            as well as see them on a location by location basis.
        </p>
        <div class="dashboard-nav">
            <a href="{{ route('admin.dishes.index') }}" class="dashboard-link">Manage Dishes</a>
            <a href="{{ route('admin.stats') }}" class="dashboard-link">View Stats</a>
        </div>

        <div class="dishes-page">
            <div class="dishes-header">
                <h1 class="page-title">Manage Accounts</h1>
                <div class="dishes-actions">
                    <a href="{{ route('admin.super-admin.new-admin-form') }}" class="btn-primary">+ Add New Account</a>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.dishes.index') }}" class="mb-3 search-dishes-div">
                <input class="form-control text-box" type="text" name="search_dish" placeholder="search"
                    value="{{ request('search_dish') }}">
                <button type="submit" class="btn btn-primary">Search</button>
                <a type="submit" href="{{ route('admin.dishes.index') }}" class="btn btn-primary red-btn">Clear</a>
            </form>

            <div class="dishes-table-wrapper">
                <table class="dishes-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($childrenAccounts as $child)
                            <tr>
                                <td>{{ $child->name }}</td>
                                <td>{{ $child->location->city }}, {{ $child->location->street }}, {{ $child->location->postcode }}</td>
                                <td style="white-space: nowrap;">
                                    <form action="" method="POST" class="inline-form" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-small btn-danger"
                                            onclick="return confirm('Delete this dish?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-row">No Accounts Added.</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection