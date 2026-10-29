<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

readonly class CreateProductDTO
{
    /**
     * @param  array<string>|null  $sizes
     * @param  array<string, int>|null  $sizeStocks
     * @param  array<UploadedFile>  $images
     */
    public function __construct(
        public string $name,
        public int $categoryId,
        public float $price,
        public int $stock,
        public string $description,
        public ?float $costPrice = null,
        public ?string $badge = null,
        public ?array $sizes = null,
        public ?array $sizeStocks = null,
        public array $images = []
    ) {}

    /**
     * Factory dari array request yang telah tervalidasi.
     *
     * @param  array<string, mixed>  $data
     * @param  array<UploadedFile>  $images
     */
    public static function fromArray(array $data, array $images = []): self
    {
        return new self(
            name: (string) $data['name'],
            categoryId: (int) $data['category_id'],
            price: (float) $data['price'],
            stock: (int) $data['stock'],
            description: (string) $data['description'],
            costPrice: isset($data['cost_price']) && $data['cost_price'] !== '' ? (float) $data['cost_price'] : null,
            badge: $data['badge'] ?? null,
            sizes: $data['sizes'] ?? null,
            sizeStocks: $data['size_stocks'] ?? null,
            images: $images
        );
    }
}
