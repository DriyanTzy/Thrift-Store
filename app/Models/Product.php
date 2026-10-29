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

    // ──────────────────────────────────────────────
    //  Relasi
    // ──────────────────────────────────────────────

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

    // ──────────────────────────────────────────────
    //  Query Scopes
    // ──────────────────────────────────────────────

    /**
     * Scope untuk produk yang tampil di katalog publik (aktif dan ada stok).
     */
    public function scopeAvailableInStore(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('stock', '>', 0);
    }

    /**
     * Scope untuk filter pencarian kata kunci pada nama, deskripsi, atau badge.
     */
    public function scopeSearch(Builder $query, ?string $term = null): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', '%'.$term.'%')
                ->orWhere('description', 'like', '%'.$term.'%')
                ->orWhere('badge', 'like', '%'.$term.'%');
        });
    }

    // ──────────────────────────────────────────────
    //  Domain Methods
    // ──────────────────────────────────────────────

    /**
     * Produk tersedia untuk dibeli jika aktif dan stok > 0.
     */
    public function isAvailable(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    /**
     * @deprecated Gunakan isAvailable() sebaliknya
     */
    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    /**
     * Reservasi stok — stok dikurangi sejumlah qty.
     * Catatan: pengurangan aktual dilakukan oleh StockLedgerService.
     * Method ini hanya menerapkan guard.
     *
     * @throws \InvalidArgumentException jika stok tidak mencukupi
     */
    public function reserveStock(int $qty): void
    {
        if ($this->stock < $qty) {
            throw new \InvalidArgumentException("Stok produk '{$this->name}' tidak mencukupi.");
        }
    }

    /**
     * Pulihkan stok — stok ditambah sejumlah qty.
     * Catatan: penambahan aktual dilakukan oleh StockLedgerService.
     */
    public function restoreStock(int $qty): void
    {
        // Guard: qty harus positif
        if ($qty <= 0) {
            throw new \InvalidArgumentException('Jumlah stok yang dipulihkan harus positif.');
        }
    }

    // ──────────────────────────────────────────────
    //  Accessors
    // ──────────────────────────────────────────────

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

        // Fallback jika tersimpan sebagai comma-separated string e.g. "S,M,L,XL"
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
