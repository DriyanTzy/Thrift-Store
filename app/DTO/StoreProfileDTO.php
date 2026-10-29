<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

readonly class StoreProfileDTO
{
    public function __construct(
        public string $storeName,
        public string $storeCity,
        public ?string $storeSlogan = null,
        public ?string $storePhone = null,
        public ?string $storeDescription = null,
        public ?UploadedFile $logo = null,
        public ?UploadedFile $banner = null
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data, ?UploadedFile $logo = null, ?UploadedFile $banner = null): self
    {
        return new self(
            storeName: (string) $data['store_name'],
            storeCity: (string) $data['store_city'],
            storeSlogan: $data['store_slogan'] ?? null,
            storePhone: $data['store_phone'] ?? null,
            storeDescription: $data['store_description'] ?? null,
            logo: $logo,
            banner: $banner
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toModelAttributes(): array
    {
        return array_filter([
            'store_name' => $this->storeName,
            'store_city' => $this->storeCity,
            'store_slogan' => $this->storeSlogan,
            'store_phone' => $this->storePhone,
            'store_description' => $this->storeDescription,
        ], fn ($val) => $val !== null);
    }
}
