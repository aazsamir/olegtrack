<?php

declare(strict_types=1);

namespace App\Repository;

use App\Instagrapi\Data\UserShort;

readonly class User
{
    public function __construct(
        public string $username,
        public ?string $fullname = null,
        public ?string $avatar = null,
        public \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
        public \DateTimeImmutable $updatedAt = new \DateTimeImmutable(),
    ) {}

    public static function fromInstagrapi(UserShort $userShort): self
    {
        return new self(
            username: $userShort->username ?? '',
            fullname: $userShort->fullName,
            avatar: $userShort->profilePicUrlHd ?? $userShort->profilePicUrl,
        );
    }

    public function avatarUrl(): ?string
    {
        if ($this->avatar === null) {
            return null;
        }

        return '/avatar?avatar=' . urlencode($this->avatar);
    }

    public function profileUrl(): string
    {
        return 'https://www.instagram.com/' . $this->username . '/';
    }
}
