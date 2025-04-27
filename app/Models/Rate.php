<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "rates";

    protected $fillable = ['value','message','user_id','type','model_id'];

    public const  TYPE_ORDER = 1;

    public const  TYPE_COMPANY=2;

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function companyProduct(){
        return $this->belongsTo(CompanyProduct::class,'model_id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'model_id');
    }
    public function company(){
        return $this->belongsTo(User::class,'model_id');
    }


}
