@extends('admin.layout')

@section('content')
<div class="dishes-page">
    <div class="dishes-header">
        <h1 class="page-title">Manage Dishes</h1>
        <div class="dishes-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn-secondary">Back to Dashboard</a>
            <a href="{{ route('admin.dishes.create') }}" class="btn-primary">+ Add New Dish</a>
        </div>
    </div>

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
                    <td>{{ $dish->allergen_string }}</td>
                    <td>£{{ number_format($dish->price, 2) }}</td>
                    <td>
                        <a href="{{ route('admin.dishes.edit', $dish->id) }}" class="btn-small btn-warning">Edit</a>
                        <form action="{{ route('admin.dishes.destroy', $dish->id) }}" method="POST" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-small btn-danger" onclick="return confirm('Delete this dish?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-row">No dishes found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
