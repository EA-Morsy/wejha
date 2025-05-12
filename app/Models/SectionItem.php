<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
use App\Models\Article;
use App\Models\Solution;

class SectionItem extends Model
{
    protected $fillable = [
        'section_id', 'type', 'content', 'order', 'article_id', 'business_id'
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function business()
    {
        return $this->belongsTo(Solution::class);
    }
}
