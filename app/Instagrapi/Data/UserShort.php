<?php

declare(strict_types=1);

namespace App\Instagrapi\Data;

readonly class UserShort
{
    public function __construct(
        public string $pk,
        public ?string $username,
        public ?string $fullName,
        public ?string $profilePicUrl,
        public ?string $profilePicUrlHd,
        public ?bool $isPrivate,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            pk: $data['pk'],
            username: $data['username'] ?? null,
            fullName: $data['full_name'] ?? null,
            profilePicUrl: $data['profile_pic_url'] ?? null,
            profilePicUrlHd: $data['profile_pic_url_hd'] ?? null,
            isPrivate: $data['is_private'] ?? null,
        );
    }
}
