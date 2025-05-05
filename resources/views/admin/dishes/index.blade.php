@extends('admin.layout')

@section('content')
    <h1>Dishes</h1>
    <a href="{{ route('admin.dashboard') }}">Back</a>

    <a href="{{ route('admin.dishes.create') }}" class="btn btn-primary mb-3">+ Add New Dish</a>

    @foreach($dishes as $dish)
                    <tr>
                        <td>{{ $dish->dish_name }}</td>
                        <td>{{ $dish->description }}</td>
                        <td>{{ $dish->allergen_string }}</td>
                        <td>${{ number_format($dish->price, 2) }}</td>
                        <td>
                            <a href="{{ route('admin.dishes.edit', $dish->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('admin.dishes.destroy', $dish->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this dish?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

@endsection
