<?php

declare(strict_types=1);

namespace App\Instagrapi;

readonly class InstagrapiConfig
{
    public function __construct(
        public string $host,
        public string $username,
        #[\SensitiveParameter]
        public string $password,
    ) {}
}
