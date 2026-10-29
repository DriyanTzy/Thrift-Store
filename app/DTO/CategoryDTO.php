<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

readonly class CategoryDTO
{
    public function __construct(
        public string $name,
        public ?string $bannerColor = null,
        public ?string $icon = null,
        public ?UploadedFile $image = null
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data, ?UploadedFile $image = null): self
    {
        return new self(
            name: (string) $data['name'],
            bannerColor: $data['banner_color'] ?? null,
            icon: $data['icon'] ?? null,
            image: $image
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toModelAttributes(): array
    {
        return array_filter([
            'name' => $this->name,
            'banner_color' => $this->bannerColor,
            'icon' => $this->icon,
        ], fn ($val) => $val !== null);
    }
}
