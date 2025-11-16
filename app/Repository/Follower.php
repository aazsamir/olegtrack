<?php

declare(strict_types=1);

namespace App\Repository;

use App\Instagrapi\Data\UserShort;

readonly class Follower
{
    public function __construct(
        public string $follower,
        public string $follows,
        public \DateTimeImmutable $date = new \DateTimeImmutable(),
    ) {}

    public static function fromInstagrapi(
        string $follows,
        UserShort $follower,
    ): self {
        return new self(
            follower: $follower->username,
            follows: $follows,
        );
    }
}
