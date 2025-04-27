<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class StoreCouponRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|unique:coupons,code,'.$this->id,
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'value' => 'required',
            'end_date' => 'required|date',
            'brand_name' => 'required|string|min:2',
            'brand_logo' => 'required|image',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(apiResponse(false, $errors, 'Validation Error', null, 422));
    }
}
