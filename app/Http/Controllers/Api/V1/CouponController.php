<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function create(StoreCouponRequest $request)
    {
        $data = [
            'code' => $request->code,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'value' => $request->value,
            'status' => 'pendding',
            'end_date' => $request->end_date,
            'location_url' => $request->location_url,
            'city_id' => $request->city_id,
            'user_id' => auth()->user()->id,
            'category_id' => $request->category_id,
            'brand_name' => $request->brand_name,
        ];

        if ($request->hasFile('brand_logo')) {
            $brand_logo = $request->file('brand_logo');
            $fileName = time() . rand(0, 999999999) . '.' . $brand_logo->getClientOriginalExtension();
            $request->brand_logo->move(public_path('storage/coupons'), $fileName);
            $data['brand_logo'] = $fileName;
        }

        $couponates = Coupon::create($data);
        $couponates = new CouponResource($couponates);

        return apiResponse(true, $couponates, null, null, 200);
    }

    public function myCoupons()
    {
        $couponates = Coupon::where('user_id',auth()->user()->id)->get();
        $couponates = CouponResource::collection($couponates);

        return apiResponse(true, $couponates, null, null, 200);
    }
    public function delete($id)
    {
        $item = Coupon::findOrFail($id);
        if ($item->delete()) {
            return apiResponse(true, null, __('coupons.messages.deleted'), null, 200);
        }
        return apiResponse(false, null, __('coupons.messages.deleted'), null, 201);
    }


}
