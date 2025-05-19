@csrf

<div class="mb-3">
    <label for="dish_name" class="form-label">Dish Name</label>
    <input type="text" name="dish_name" id="dish_name" class="form-control"
        value="{{ old('dish_name', $dish->dish_name ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description"
        class="form-control">{{ old('description', $dish->description ?? '') }}</textarea>
</div>


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

            <div class="form-check ms-4">
                <input type="checkbox" name="removables[{{ $allergen }}]" value="1" {{ isset($combined[$allergen]) && $combined[$allergen] ? 'checked' : '' }}>

                <label class="form-check-label" for="removable-{{ $allergen }}">
                    Removable
                </label>
            </div>
        </div>
    @endforeach
</div>

<div class="mb-3">
    <label class="form-label">Other dietary needs: </label><br>
    <div class="border rounded p-2 mb-2">
        <div class="form-check">
            @if (isset($dish->halal) == true)
                <input type="hidden" name="halal" value="0">
            @endif
            <input type="checkbox" class="form-check-input" id="halal" name="halal" value="1" {{ old('halal', $dish->halal ?? false) ? 'checked' : '' }}>

            <label class="form-check-label" for="halal">halal</label>
        </div>
    </div>
</div>


<div class="mb-3">
    <label for="price" class="form-label">Price</label>
    <input type="number" name="price" id="price" step="0.01" class="form-control"
        value="{{ old('price', $dish->price ?? '') }}" required>
</div>

<button type="submit" class="btn btn-primary">
    {{ isset($dish->admin_id) ? 'Update Dish' : 'Create Dish' }}
</button>