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

    /**
     * Get unread chat messages count for this user
     */
    public function getUnreadChatCountAttribute(): int
    {
        return ChatMessage::where('receiver_id', $this->id)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get store display name (distinct from user personal name, even under same email)
     */
    public function getStoreNameAttribute(?string $value): string
    {
        return $value ?: ($this->name."'s Thrift Shop");
    }

    /**
     * Get store slogan
     */
    public function getStoreSloganAttribute(?string $value): string
    {
        return $value ?: 'Koleksi Thrifting & Preloved Fashion Terkurasi';
    }

    /**
     * Get store city
     */
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
