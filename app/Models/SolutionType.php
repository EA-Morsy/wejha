<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolutionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'icon',
        'solution_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع الحلول (العديد من أنواع الحلول تنتمي إلى حل)
     */
    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }
    
    /**
     * العلاقة مع الحلول (نوع الحل لديه العديد من الحلول)
     */
    public function solutions(): HasMany
    {
        return $this->hasMany(Solution::class);
    }

    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $column = "name_$locale";
        return $this->$column;
    }

    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $column = "description_$locale";
        return $this->$column;
    }
}
