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

                    <label class="form-check-label" for="allergen-{{ $allergen }}">
                        {{ ucfirst($allergen) }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="mb-3 allergy-form">
    <label class="form-label">Other dietary needs: </label>
    <div class="border rounded p-2 mb-2">
        <div class="form-check">
            <input type="hidden" name="halal" value="0">
            <input type="checkbox" class="form-check-input" id="halal" name="halal" value="1">

            <label class="form-check-label" for="halal">halal</label>
        </div>
    </div>
</div>