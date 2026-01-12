@csrf
@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif


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
        @php
            $allergenChecked = in_array(
                $allergen,
                old('allergens', $selectedAllergens ?? []),
                true
            );

            $removablesOld = old('removables');
            $removableChecked = is_array($removablesOld)
                ? !empty($removablesOld[$allergen])
                : (!empty($combined[$allergen] ?? false));
        @endphp

        <div class="border rounded p-2 mb-2">
            <div class="form-check">
                <input
                    type="checkbox"
                    class="form-check-input"
                    id="allergen-{{ $allergen }}"
                    name="allergens[]"
                    value="{{ $allergen }}"
                    @checked($allergenChecked)
                >

                <label class="form-check-label" for="allergen-{{ $allergen }}">
                    {{ ucfirst($allergen) }}
                </label>
            </div>

            <div class="form-check ms-4">
                <input
                    type="checkbox"
                    class="form-check-input"
                    id="removable-{{ $allergen }}"
                    name="removables[{{ $allergen }}]"
                    value="1"
                    @checked($removableChecked)
                >

                <label class="form-check-label" for="removable-{{ $allergen }}">
                    Removable
                </label>
            </div>
        </div>
    @endforeach
</div>


<div class="mb-3">
    <div class="border rounded p-2 mb-2">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="no_allergens" name="no_allergens" value="1" {{ (isset($no_allergens) && $no_allergens) || old(key: 'no_allergens', default: $dish->no_allergens ?? '') ? 'checked' : '' }}>
            <label class="form-check-label" for="no_allergens">
                No Allergens?
            </label>
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Other dietary restrictions: </label><br>
    @php
        $i = 0;
    @endphp
    @foreach ($diet as $diet_restriction)
        <div class="border rounded p-2 mb-2">
            <div class="form-check">
                <input type="hidden" name="diet[{{ $diet_restriction }}]" value="false">
                @php
                    $dietSetBool = false;
                    if (isset($dietSelected[$i])) {
                        if ($dietSelected[$i]) {
                            $dietSetBool = true;
                        }
                    }
                    
                    $key = "diet.$diet_restriction"; 
                    $defaultChecked = !empty($dietSelected[$diet_restriction] ?? false);
                    $checked = old($key, $defaultChecked) === 'true';
                @endphp

                <input type="checkbox" class="form-check-input" id="diet-{{ $diet_restriction }}"
                    name="diet[{{ $diet_restriction }}]" value="true" {{ $dietSetBool ? 'checked' : '' }} @checked($checked)>

                <label class="form-check-label" for="diet-{{ $diet_restriction }}">
                    {{ ucfirst($diet_restriction) }}
                </label>
            </div>
        </div>
        @php
            $i++;
        @endphp
    @endforeach
</div>



<div class="mb-3">
    <label for="price" class="form-label">£ Price</label>
    <input type="number" min="0" max="999999" name="price" id="price" step="0.01" class="form-control"
        value="{{ old('price', $dish->price ?? '') }}" required>
</div>

<button type="submit" class="btn btn-primary">
    {{ isset($dish->admin_id) ? 'Update Dish' : 'Create Dish' }}
</button>