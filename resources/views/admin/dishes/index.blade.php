@extends('admin.layout')

@section('content')
    <form action="{{ route('admin.dashboard') }}" method="get" style="display:inline;">
        <button type="submit" class="back-button">Back to Dashboard</button>
    </form>
    @if ($dishShareStatus)
        <p>You are involved in a dish share! This means some dishes you see belong to the parent restaurant.</p>
    @endif
    <div class="dishes-page">
        <div class="dishes-header">
            <h1 class="page-title">Manage Dishes</h1>
            <div class="dishes-actions">
                <a href="{{ route('admin.dishes.create') }}" class="btn-primary">+ Add New Dish</a>
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
                        <th>Description</th>
                        <th>Allergens</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dishes as $dish)
                        <tr>
                            <td>{{ $dish->dish_name }}</td>
                            <td>{{ $dish->description }}</td>
                            <td>{{ $dish->formatted_allergen_string }}</td>
                            <td>£{{ number_format($dish->price, 2) }}</td>
                            @if ($dish->admin_id == Auth::guard('admin')->id())
                                <td style="white-space: nowrap;">
                                    <a href="{{ route('admin.dishes.edit', $dish->id) }}" class="btn-small btn-warning">Edit</a>
                                    <form action="{{ route('admin.dishes.destroy', $dish->id) }}" method="POST" class="inline-form"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-small btn-danger"
                                            onclick="return confirm('Delete this dish?')">Delete</button>
                                    </form>
                                </td>
                            @else
                                <td>
                                    Shared dish: owner - {{  $dish->admin->email }}
                                </td>
                            @endif

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-row">No dishes found.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        {{ $dishes->links('pagination::bootstrap-5') }}
<br>
<br>
<br>

        @if (count($children))
        <div class="dishes-table-wrapper">
            <table class="dishes-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Revoke Access</th>
                    </tr>
                </thead>
                <tbody>
                    You are sharing your dishes with some other restaurants! Please see which ones below, and you can control
                    access from here.
                    <br>
                    <br>


                    @foreach ($children as $child)
                        <tr>
                            <td>{{ $child->childAdmin->name }}</td>
                            <td style="white-space: nowrap;">
                                <form action="{{ route('admin.dish-share.delete', $child->childAdmin->id) }}" method="POST" class="inline-form"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-small btn-danger"
                                        onclick="return confirm('Delete this dish?')">Delete</button>
                                </form>
                            </td>
                        </tr>

                    @endforeach
                </tbody>

            </table>
            </div>
        @endif
    </div>
@endsection