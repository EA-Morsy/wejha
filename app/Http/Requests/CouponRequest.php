<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }
}
