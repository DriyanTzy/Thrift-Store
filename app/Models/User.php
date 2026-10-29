<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'player_tag',
        'is_admin',
        'phone',
        'avatar',
        'address',
        'bio',
        'store_name',
        'store_slogan',
        'store_description',
        'store_logo',
        'store_banner',
        'store_city',
        'store_phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    // ──────────────────────────────────────────────
    //  Relasi
    // ──────────────────────────────────────────────

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function conversationsAsBuyer(): HasMany
    {
        return $this->hasMany(Conversation::class, 'buyer_id');
    }

    public function conversationsAsSeller(): HasMany
    {
        return $this->hasMany(Conversation::class, 'seller_id');
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    // ──────────────────────────────────────────────
    //  Scopes
    // ──────────────────────────────────────────────

    /**
     * Scope untuk pengguna yang dapat dicari oleh publik (mengecualikan admin demi privasi).
     */
    public function scopeSearchablePublic($query, ?string $term = null)
    {
        $q = $query->where('is_admin', false);

        if ($term) {
            $q->where(function ($sub) use ($term) {
                $sub->where('name', 'like', '%'.$term.'%')
                    ->orWhere('store_name', 'like', '%'.$term.'%')
                    ->orWhere('player_tag', 'like', '%'.$term.'%')
                    ->orWhere('store_city', 'like', '%'.$term.'%');
            });
        }

        return $q;
    }

    // ──────────────────────────────────────────────
    //  Domain Methods
    // ──────────────────────────────────────────────

    /**
     * User ini adalah admin platform.
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * User ini adalah seller (memiliki toko / produk).
     */
    public function isSeller(): bool
    {
        return ! empty($this->getRawOriginal('store_name'));
    }

    // ──────────────────────────────────────────────
    //  Accessors
    // ──────────────────────────────────────────────

    public function getUnreadChatCountAttribute(): int
    {
        return ChatMessage::where('receiver_id', $this->id)
            ->where('is_read', false)
            ->count();
    }

    /**
     * URL Foto Profil / Avatar Pribadi
     */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? asset('storage/'.$this->avatar) : null;
    }

    /**
     * URL Logo Toko (fallback ke avatar pribadi jika logo toko belum diupload)
     */
    public function getStoreLogoUrlAttribute(): ?string
    {
        if ($this->store_logo) {
            return asset('storage/'.$this->store_logo);
        }

        return $this->avatar_url;
    }

    /**
     * Dapatkan foto yang tepat untuk konteks (toko vs pengguna)
     */
    public function getProfilePictureForContext(bool $isStoreContext = false): ?string
    {
        return $isStoreContext ? $this->store_logo_url : $this->avatar_url;
    }

    public function getStoreNameAttribute(?string $value): string
    {
        return $value ?: ($this->name."'s Thrift Shop");
    }

    public function getStoreSloganAttribute(?string $value): string
    {
        return $value ?: 'Koleksi Thrifting & Preloved Fashion Terkurasi';
    }

    public function getStoreCityAttribute(?string $value): string
    {
        return $value ?: 'Jakarta';
    }

    public function getStoreRatingAttribute(): float
    {
        $productIds = $this->products()->pluck('id');
        if ($productIds->isEmpty()) {
            return 5.0;
        }
        $avg = Review::whereIn('product_id', $productIds)->avg('rating');

        return $avg ? round((float) $avg, 1) : 5.0;
    }

    public function getStoreReviewCountAttribute(): int
    {
        $productIds = $this->products()->pluck('id');
        if ($productIds->isEmpty()) {
            return 0;
        }

        return Review::whereIn('product_id', $productIds)->count();
    }
}
