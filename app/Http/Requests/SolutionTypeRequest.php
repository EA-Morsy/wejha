<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolutionTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'icon' => 'nullable|image|max:2048',
            'remove_icon' => 'nullable|boolean',
            'is_active' => 'nullable|boolean'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'name_ar.required' => __('solution_types.validation.name_ar_required'),
            'name_en.required' => __('solution_types.validation.name_en_required'),
            'icon.image' => __('solution_types.validation.icon_image'),
            'icon.max' => __('solution_types.validation.icon_max'),
        ];
    }
}
