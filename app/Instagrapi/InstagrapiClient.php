<?php

declare(strict_types=1);

namespace App\Instagrapi;

use App\Instagrapi\Data\UserShort;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class InstagrapiClient
{
    public function __construct(
        private InstagrapiConfig $instagrapiConfig,
        private Client $http,
    ) {}

    public function login(string $username, string $password): string
    {
        /** @var string */
        $response = $this->sendRequest('/auth/login', [
            'username' => $username,
            'password' => $password,
        ]);

        return $response;
    }

    public function idFromUsername(string $sessionId, string $username): int
    {
        /** @var int */
        $response = $this->sendRequest('/user/id_from_username', [
            'sessionid' => $sessionId,
            'username' => $username,
        ]);

        return (int) $response;
    }

    /**
     * @return UserShort[]
     */
    public function followers(string $sessionId, int $userId): array
    {
        $response = $this->sendRequest('/user/followers', [
            'sessionid' => $sessionId,
            'user_id' => $userId,
        ]);

        return array_map(
            UserShort::fromArray(...),
            $response,
        );
    }

    private function endpoint(string $path): string
    {
        return rtrim($this->instagrapiConfig->host, '/') . '/' . ltrim($path, '/');
    }

    private function sendRequest(string $path, array $body = []): array|string|int
    {
        try {
            $response = $this->http->post(
                $this->endpoint($path),
                [
                    RequestOptions::FORM_PARAMS => $body,
                ],
            );

            $response = json_decode($response->getBody()->getContents(), true);

            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $response = $exception->getResponse()->getBody()->getContents();
            $body = json_decode($response, true);

            throw new InstagrapiException($body, $exception);
        }
    }
}
