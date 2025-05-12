<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'section_type',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'content_ar',
        'content_en',
        'image',
        'order',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    public static function getSectionTypes()
    {
        return [
            'hero' => __('sections.types.hero'),
            'gallery' => __('sections.types.gallery'),
            'articles_list' => __('sections.types.articles_list'),
            'contact_form' => __('sections.types.contact_form'),
            'about_text' => __('sections.types.about_text'),
            'video' => __('sections.types.video'),
        ];
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function items()
    {
        return $this->hasMany(SectionItem::class)->orderBy('order');
    }
}
