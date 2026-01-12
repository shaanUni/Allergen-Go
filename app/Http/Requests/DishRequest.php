<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class DishRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dish_name'    => 'required|string|max:255',
            'description'  => 'nullable|string',
            'allergens'    => 'nullable|array',
            'price'        => 'required|numeric|min:0|max:999999',
            'diet'         => 'nullable|array',
            'no_allergens' => 'nullable|boolean',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {

            //allergens in the dish
            $allergens = $this->input('allergens', []);
            
            //bool for if a dish has no allergens
            $noAllergens = $this->boolean('no_allergens');

            //if there are no allergens selected, and the admin did not put the "no allergen flag"
            if (empty($allergens) && !$noAllergens) {
                $validator->errors()->add(
                    'allergens',
                    'You did not enter any allergens. Select allergens or check “No Allergens?”.'
                );
            }

            //if they selected both the no allergen flag, and some allergens
            if (!empty($allergens) && $noAllergens) {
                $validator->errors()->add(
                    'no_allergens',
                    'You selected allergens and also checked “No Allergens?”. Uncheck it if allergens are present.'
                );
            }
        });
    }
}
