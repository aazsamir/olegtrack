<?php

declare(strict_types=1);

namespace App\Service;

use App\Instagrapi\Data\UserShort;
use App\Instagrapi\Instagrapi;
use App\Repository\DatabaseRepository;
use App\Repository\Follower;
use App\Repository\User;

class ScrapService
{
    public function __construct(
        private Instagrapi $instagrapi,
        private DatabaseRepository $repository,
    ) {}

    public function scrapAll(): void
    {
        $usernames = $this->repository->getTrackedUsers();

        foreach ($usernames as $username) {
            $this->scrap($username);
        }
    }

    public function scrap(string $username): void
    {
        $followers = $this->instagrapi->followers($username);

        $this->repository->beginTransaction();

        try {
            $this->saveLookedUpUser($username);
            $this->saveUsers($followers);
            $this->saveFollowers($username, $followers);

            $this->repository->commit();
        } catch (\Throwable $e) {
            $this->repository->rollBack();

            throw $e;
        }
    }

    public function saveLookedUpUser(string $username): void
    {
        $this->repository->saveUser(
            new User(
                username: $username,
            ),
        );
    }

    /**
     * @param UserShort[] $followers
     */
    public function saveUsers(array $followers): void
    {
        $users = \array_map(
            User::fromInstagrapi(...),
            $followers,
        );

        $this->repository->saveUsers($users);
    }

    /**
     * @param UserShort[] $followers
     */
    public function saveFollowers(string $username, array $followers): void
    {
        $followers = \array_map(
            static fn (UserShort $userShort) => Follower::fromInstagrapi($username, $userShort),
            $followers,
        );

        $this->repository->saveFollowers($followers);
    }
}
