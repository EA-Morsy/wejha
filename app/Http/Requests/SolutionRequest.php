<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolutionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name_ar' => 'required|string|unique:solutions,name_ar,' . $this->id,
            'name_en' => 'required|string|unique:solutions,name_en,' . $this->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }
}
