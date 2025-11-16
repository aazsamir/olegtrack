<?php

declare(strict_types=1);

namespace App\Instagrapi;

use App\Instagrapi\Session\SessionCache;
use Tempest\Cache\Cache;
use Tempest\DateTime\Duration;
use Tempest\Log\Logger;

class Instagrapi
{
    public function __construct(
        private InstagrapiClient $client,
        private InstagrapiConfig $instagrapiConfig,
        private SessionCache $sessionCache,
        private Cache $cache,
        private Logger $logger,
    ) {}

    public function followers(string $username)
    {
        $this->logger->debug("Fetching followers for user: {$username}");
        $cacheKey = 'followers_' . $username;
        
        return $this->cache->resolve($cacheKey, function () use ($username) {
            $this->logger->debug("Followers not found in cache, querying API");
            $sessionId = $this->sessionId();
            $userId = $this->idFromUsername($username);

            return $this->client->followers($sessionId, $userId);
        }, Duration::day());
    }

    private function idFromUsername(string $username): int
    {
        $this->logger->debug("Resolving user ID for username: {$username}");
        $cacheKey = 'user_id_' . $username;

        return $this->cache->resolve($cacheKey, function () use ($username) {
            $this->logger->debug("User ID not found in cache, querying API");
            $sessionId = $this->sessionId();

            return $this->client->idFromUsername($sessionId, $username);
        }, Duration::day());
    }

    private function sessionId(): string
    {
        $this->logger->debug("Retrieving session ID");
        $sessionId = $this->sessionCache->get(
            $this->instagrapiConfig->username,
            $this->instagrapiConfig->password,
        );

        if ($sessionId !== null) {
            $this->logger->debug("Using cached session ID");
            return $sessionId;
        }

        $this->logger->debug("Logging in to obtain new session ID");
        $sessionId = $this->client->login(
            $this->instagrapiConfig->username,
            $this->instagrapiConfig->password,
        );

        $this->sessionCache->set(
            $this->instagrapiConfig->username,
            $this->instagrapiConfig->password,
            $sessionId,
        );

        return $sessionId;
    }
}
