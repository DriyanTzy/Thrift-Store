<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'cost_price',
        'stock',
        'sizes',
        'image',
        'badge',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->orderBy('created_at', 'desc');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class)->orderBy('created_at', 'desc');
    }

    /**
     * Scope for products displayed in the public store catalog (hide out of stock items)
     */
    public function scopeAvailableInStore(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('stock', '>', 0);
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    public function getTotalStockInAttribute(): int
    {
        return (int) $this->stockMovements()->where('type', 'in')->sum('quantity');
    }

    public function getTotalStockOutAttribute(): int
    {
        return (int) $this->stockMovements()->where('type', 'out')->sum('quantity');
    }

    public function getSizeStocksAttribute(): array
    {
        if (empty($this->sizes)) {
            return [];
        }

        $decoded = json_decode($this->sizes, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        // Fallback if stored as comma-separated string e.g. "S,M,L,XL"
        $parts = array_map('trim', explode(',', $this->sizes));
        $result = [];
        $eachStock = max(1, (int) floor(($this->stock ?: 1) / max(1, count($parts))));
        foreach ($parts as $p) {
            if ($p !== '') {
                $result[$p] = $eachStock;
            }
        }

        return $result;
    }

    public function getAverageRatingAttribute(): float
    {
        $avg = $this->reviews()->avg('rating');

        return $avg ? round((float) $avg, 1) : 5.0;
    }

    public function getTotalReviewsAttribute(): int
    {
        return $this->reviews()->count();
    }
}
