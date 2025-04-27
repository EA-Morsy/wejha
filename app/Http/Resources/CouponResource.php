<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id'    => $this->id,
            'code'  => $this->code,
            'description'  => $this->description,
            'value'  => $this->value,
            'end_date'  => $this->end_date,
            'location_url'  => $this->location_url,
            'city_id'  => $this->city_id,
            'city_name'  => $this->city?->title,
            'category_name'  => $this->category?->title,
            'brand_name'  => $this->brand_name,
            'brand_logo'  => $this->photo,
            'category_image'  => $this->category?->photo,
        ];
    }

}
