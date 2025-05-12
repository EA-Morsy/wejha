<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->route('id');
        $rules = [
            'model' => 'nullable|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'solution_type_id' => 'required|exists:solution_types,id',
            'is_active' => 'boolean',
            'image' => $id ? 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240' : 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            
            // Gallery images validation
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            
            // Specs validation
            'specs.*.key_ar' => 'required|string|max:255',
            'specs.*.key_en' => 'required|string|max:255',
            'specs.*.value_ar' => 'nullable|string|max:255',
            'specs.*.value_en' => 'nullable|string|max:255',
            'specs.*.sort_order' => 'nullable|integer',
            
            // Related products validation
            'related_products' => 'nullable|array',
            'related_products.*' => 'exists:products,id',
        ];

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name_ar.required' => __('products.validation.name_ar.required'),
            'name_en.required' => __('products.validation.name_en.required'),
            'solution_type_id.required' => __('products.validation.solution_type_id.required'),
            'solution_type_id.exists' => __('products.validation.solution_type_id.exists'),
            'image.required' => __('products.validation.image.required'),
            'image.image' => __('products.validation.image.image'),
            'image.mimes' => __('products.validation.image.mimes'),
            'image.max' => __('products.validation.image.max'),
        ];
    }
}
