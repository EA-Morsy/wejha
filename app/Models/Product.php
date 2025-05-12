<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'model',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'image',
        'solution_type_id',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the solution type that owns the product.
     */
    public function solutionType(): BelongsTo
    {
        return $this->belongsTo(SolutionType::class);
    }

    /**
     * Get the specifications for the product.
     */
    public function specs(): HasMany
    {
        return $this->hasMany(ProductSpec::class);
    }

    /**
     * Get the gallery images for the product.
     */
    public function gallery(): HasMany
    {
        return $this->hasMany(ProductGallery::class);
    }

    /**
     * Get the related products for this product.
     */
    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_related', 'product_id', 'related_product_id');
    }

    /**
     * Get the products that are related to this product.
     */
    public function relatedToProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_related', 'related_product_id', 'product_id');
    }
}
