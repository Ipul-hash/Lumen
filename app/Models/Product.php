<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'summary',
        'description',
        'specifications',
        'care_instructions',
        'base_price',
        'weight',
        'length',
        'width',
        'height',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'specifications' => 'array',
            'base_price' => 'decimal:2',
            'weight' => 'integer',
            'length' => 'decimal:2',
            'width' => 'decimal:2',
            'height' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    public function getStartingPriceAttribute(): float
    {
        $minVariantPrice = $this->variants()
            ->where('is_active', true)
            ->min('price');

        return $minVariantPrice ? (float)$minVariantPrice : (float)$this->base_price;
    }

    public function getTotalStockAttribute(): int
    {
        return (int) $this->variants()->sum('stock');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->starting_price, 0, ',', '.');
    }
}
