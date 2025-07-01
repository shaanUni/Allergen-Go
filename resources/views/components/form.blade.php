@csrf

@if (isset($failedSearchCount))
    <div class="admin-div-flex">
@endif
    <label for="restaurant_code"></label>
    @if (isset($code))
        <input type="text" class="form-control textbox" name="restaurant_code" id="restaurant_code" value="{{ $code  }}"
            readonly required>
    @else
        <input type="text" class="form-control" placeholder="Enter Restaurant Code:" name="restaurant_code"
            id="restaurant_code" required>
    @endif
    <button type="submit" class="submit-form-btn">Submit</button>
    @if (isset($failedSearchCount))
        </div>
    @endif


<div class="mb-3 allergy-form">
    <label class="form-label test">Select your allergies:</label><br>
    <div class="checkbox-grid">
        @foreach ($allergens as $allergen)
            <div class="border rounded p-2 mb-2 box">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="allergen-{{ $allergen }}" name="allergens[]"
                        value="{{ $allergen }}" {{ in_array($allergen, $selectedAllergens ?? []) ? 'checked' : '' }}>

                    <label class="form-check-label allergen-{{ $allergen }}" for="allergen-{{ $allergen }}">
                        {{ ucfirst($allergen) }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="mb-3 allergy-form">
    <label class="form-label test">Select your allergies:</label><br>
    <div class="checkbox-grid">
        @foreach ($diet as $diet_restriction)
            <div class="border rounded p-2 mb-2 box">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="allergen-{{ $allergen }}" name="allergens[]"
                        value="{{ $diet_restriction }}" {{ in_array($allergen, $selectedAllergens ?? []) ? 'checked' : '' }}>

                    <label class="form-check-label allergen-{{ $diet_restriction }}" for="allergen-{{ $diet_restriction }}">
                        {{ ucfirst($diet_restriction) }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>
