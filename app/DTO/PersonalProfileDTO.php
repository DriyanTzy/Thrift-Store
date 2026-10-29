<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

readonly class PersonalProfileDTO
{
    public function __construct(
        public string $name,
        public ?string $phone = null,
        public ?string $playerTag = null,
        public ?string $address = null,
        public ?string $bio = null,
        public ?UploadedFile $avatar = null
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data, ?UploadedFile $avatar = null): self
    {
        return new self(
            name: (string) $data['name'],
            phone: $data['phone'] ?? null,
            playerTag: $data['player_tag'] ?? null,
            address: $data['address'] ?? null,
            bio: $data['bio'] ?? null,
            avatar: $avatar
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toModelAttributes(): array
    {
        return array_filter([
            'name' => $this->name,
            'phone' => $this->phone,
            'player_tag' => $this->playerTag,
            'address' => $this->address,
            'bio' => $this->bio,
        ], fn ($val) => $val !== null);
    }
}
