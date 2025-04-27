<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CouponResource;
use App\Http\Resources\SliderResource;
use App\Models\Category;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Slider;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function sliders()
    {
        $data = Slider::where('active', 1)->get();
        $result = SliderResource::collection($data);
        return apiResponse(true, $result, null, null, 200);
    }
    public function cities()
    {
        $data = City::where('active', 1)->get();
        $result = CityResource::collection($data);
        return apiResponse(true, $result, null, null, 200);
    }
    public function categories()
    {
        $data = Category::where('active', 1)->get();
        $result = CategoryResource::collection($data);
        return apiResponse(true, $result, null, null, 200);
    }
    public function home()
    {
        $sliders = Slider::where('active', 1)->get();
        $data['sliders'] = SliderResource::collection($sliders);

        $categories = Category::where('active', 1)->limit(20)->orderBy('id', 'desc')->get();
        $data['categories'] = CategoryResource::collection($categories);

        return apiResponse(true, $data, null, null, 200);
    }
    public function coupons(Request $request)
    {

        $couponates = Coupon::with([ 'category', 'city'])
            ->where(function ($query) use ($request) {
                if ($request->filled('city_id')) {
                    $query->where('city_id', $request->city_id);
                }

            })->orderBy('id', 'desc')->get();
        $couponates = CouponResource::collection($couponates);

        return apiResponse(true, $couponates, null, null, 200);
    }
    public function categoryCoupons(Request $request, $id)
    {
        $couponates = Coupon::with(['category', 'city'])->where('category_id', $id);

        if ($request->filled('city_name')) {
            $couponates->whereHas('city', function ($query) use ($request) {
                $query->where('title_en', 'like', '%'.$request->city_name.'%')->orWhere('title_ar', 'like', '%'.$request->city_name.'%');
            });
        }

        if ($request->filled('order_by')) {
            $couponates->orderBy('value', $request->order_by);
        }
        $couponates = $couponates->get();
        $couponates = CouponResource::collection($couponates);

        return apiResponse(true, $couponates, null, null, 200);
    }
    public function coupon($id)
    {

        $coupon = Coupon::with(['category','city'])->findOrFail($id);
        $coupon = new CouponResource($coupon);

        return apiResponse(true, $coupon, null, null, 200);
    }
}
