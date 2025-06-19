<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\AssetStatusEnum;
class UpdateAssetRequest extends FormRequest
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
        $assetId = $this->route('asset'); // Assumes the route parameter is named 'asset'

        return [
            'category_id' => 'required|exists:categories,id', // Must reference an existing category
            'location_id' => 'nullable|exists:locations,id', // Optional but must reference an existing location
            'manufacturer_id' => 'nullable|exists:manufacturers,id', // Optional but must reference an existing manufacturer
            'assigned_to_user_id' => 'nullable|exists:users,id', // Optional but must reference an existing user
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'The category is required.',
            'category_id.exists' => 'The selected category does not exist.',
            'location_id.exists' => 'The selected location does not exist.',
            'manufacturer_id.exists' => 'The selected manufacturer does not exist.',
            'assigned_to_user_id.exists' => 'The selected user does not exist.',
            'asset_tag.unique' => 'The asset tag must be unique.',
            'name.required' => 'The asset name is required.',
            'name.unique' => 'The asset name must be unique.',
            'serial_number.required' => 'The serial number is required.',
            'serial_number.unique' => 'The serial number must be unique.',
            'model_name.max' => 'The model name may not be greater than 255 characters.',
            'purchase_date.date' => 'The purchase date must be a valid date.',
            'purchase_price.numeric' => 'The purchase price must be a number.',
            'purchase_price.min' => 'The purchase price must be at least 0.',
            'status.in' => 'The status must be one of the following: active, inactive, maintenance, or retired.',
            'notes.max' => 'The notes may not be greater than 1000 characters.',
        ];
    }
}
