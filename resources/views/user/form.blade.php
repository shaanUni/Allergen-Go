        @csrf
        <label for="restaurant_code">Enter Restaurant Code:</label>
        @if (isset($code))
        <input type="text" name="restaurant_code" id="restaurant_code" value="{{ $code  }}" readonly required>
        @else
        <input type="text" name="restaurant_code" id="restaurant_code" required>        
        @endif

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

