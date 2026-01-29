@extends('admin.layout')

@section('content')
    @if (session('success'))
        <div class="alert alert-success mb-3">
            {{ session('success') }}
        </div>
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
                @if (!$reachedLimit)
                    <div class="dishes-actions">
                        <a href="{{ route('admin.super-admin.new-admin-form') }}" class="btn-primary">+ Add New Account</a>
                    </div>
                @endif
            </div>
          

            <form method="GET" action="{{ route('admin.super-admin.dashboard') }}" class="mb-3 search-dishes-div">
                <input class="form-control text-box" type="text" name="search_admin" placeholder="search"
                    value="{{ request('search_admin') }}">
                <button type="submit" class="btn btn-primary">Search</button>
                <a type="submit" href="{{ route('admin.super-admin.dashboard') }}" class="btn btn-primary red-btn">Clear</a>
            </form>

            <div class="dishes-table-wrapper">
                <table class="dishes-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Location</th>
                            <th>View Individual Dishes</th>
                            <th>View Individual Stats</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($childrenAccounts as $child)
                            <tr>
                                <td>{{ $child->name }}</td>
                                <td>{{ $child->location->city ?? '' }}, {{ $child->location->street ?? '' }},
                                    {{ $child->location->postcode ?? '' }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.dishes.index', $child->id) }}" class="dashboard-link">View
                                        Dishes</a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.stats', $child->id) }}" class="dashboard-link">View Stats</a>
                                </td>
                                <td style="white-space: nowrap;">
                                    <form action="{{ route('admin.super-admin.delete-account', $child->id) }}" method="POST"
                                        class="inline-form" style="display:inline;">
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
        <div class="setting-card setting-card--share-dishes">
    <div class="setting-info">
        <h4 class="setting-title">Share dishes with sub accounts</h4>

        @if (!$admin->share_dishes)
            <p class="setting-desc">
                When enabled, your sub accounts will use the same dish list. Recommended if your menu is the same across locations.
            </p>
        @else
            <p class="setting-desc">
                Sharing is currently <strong>ON</strong>. Turning this off may cause sub accounts to lose access to shared dishes.
                <span class="setting-warn">Not recommended.</span>
            </p>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.super-admin.update-share-dish') }}" class="setting-form">
        @csrf
        <input type="hidden" name="share_dishes" value="0">

        <div class="setting-actions">
            <span class="status-pill {{ $admin->share_dishes ? 'is-on' : 'is-off' }}">
                {{ $admin->share_dishes ? 'On' : 'Off' }}
            </span>

            <label class="switch">
                <input
                    type="checkbox"
                    id="share_dishes"
                    name="share_dishes"
                    value="1"
                    onchange="this.form.submit()"
                    {{ old('share_dishes', $admin->share_dishes ?? false) ? 'checked' : '' }}
                >
                <span class="switch-ui" aria-hidden="true"></span>
                <span class="switch-text">Share</span>
            </label>

        </div>
    </form>
</div>
    </div>

    
@endsection