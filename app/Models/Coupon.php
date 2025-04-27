<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Coupon extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "coupons";
    protected $fillable = [
        'code',
        'description_en',
        'description_ar',
        'value',
        'end_date',
        'status',
        'category_id',
        'city_id',
        'location_url',
        'user_id',
        'brand_logo',
        'brand_name',
    ];
    protected $appends = ['description','photo'];
    public function getPhotoAttribute()
    {
        return array_key_exists('brand_logo', $this->attributes) ? ($this->attributes['brand_logo'] != null ? asset('storage/coupons/' . $this->attributes['brand_logo']) : null) : null;

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getDescriptionAttribute()
    {
        if (App::isLocale('en')) {
            return $this->attributes['description_en'] ?? $this->attributes['description_ar'];
        } else {
            return $this->attributes['description_ar'] ?? $this->attributes['description_en'];
        }
    }
}
