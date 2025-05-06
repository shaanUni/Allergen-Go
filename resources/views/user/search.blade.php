@extends('user.layout')

@section('content')
    @if(session('failure'))
        <div class="alert alert-danger">
            {{ session('failure') }}
        </div>
    @endif

    <h2>search, User!</h2>
    <form method="POST" action="{{ route('user.searchCode') }}">
        @csrf
        <label for="restaurant_code">Enter Restaurant Code:</label>
        <input type="text" name="restaurant_code" id="restaurant_code" required>

        <button type="submit">Submit</button>

        <div class="mb-3">
            <label class="form-label">Allergens</label><br>
            @foreach ($allergens as $allergen)
                <div class="border rounded p-2 mb-2">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="allergen-{{ $allergen }}" name="allergens[]"
                            value="{{ $allergen }}" {{ in_array($allergen, $selectedAllergens ?? []) ? 'checked' : '' }}>

                        <label class="form-check-label" for="allergen-{{ $allergen }}">
                            {{ ucfirst($allergen) }}
                        </label>
                    </div>

            @endforeach
            </div>

    </form>
@endsection