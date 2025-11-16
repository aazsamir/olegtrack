<?php

declare(strict_types=1);

namespace App\Instagrapi\Session;

use Tempest\Cache\Cache;
use Tempest\DateTime\Duration;

class SessionCache
{
    private array $memoryCache = [];

    public function __construct(
        private Cache $cache,
    ) {}

    public function get(string $username, string $password): ?string
    {
        if (isset($this->memoryCache[$username])) {
            return $this->memoryCache[$username];
        }

        $key = $this->cacheKey($username, $password);

        /** @var string|null */
        $sessionId = $this->cache->get($key);

        return $sessionId;
    }

    public function set(string $username, string $password, string $sessionId): void
    {
        $this->memoryCache[$username] = $sessionId;

        $key = $this->cacheKey($username, $password);

        $this->cache->put(
            $key,
            $sessionId,
            Duration::hours(1),
        );
    }

    private function cacheKey(string $username, string $password): string
    {
        return md5($username . '|' . $password);
    }
}
