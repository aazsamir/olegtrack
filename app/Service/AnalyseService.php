<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\DatabaseRepository;

class AnalyseService
{
    public function __construct(
        private DatabaseRepository $repository,
    ) {}

    public function analyse(string $username): AnalyseResult
    {
        $allTimeFollowers = $this->repository->getAllTimeFollowers($username);
        $latestFollowers = $this->repository->getLatestFollowers($username);
        // \array_splice($latestFollowers, 0, 10);

        $lost = [];

        foreach ($allTimeFollowers as $follower) {
            $found = false;

            foreach ($latestFollowers as $latestFollower) {
                if ($follower->follower === $latestFollower->follower) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $lost[] = $follower;
            }
        }

        $lostUsers = [];

        foreach ($lost as $lostFollower) {
            $lostUsers[] = $this->repository->findUser($lostFollower->follower);
        }

        return new AnalyseResult(
            allTimeFollowers: count($allTimeFollowers),
            latestFollowers: count($latestFollowers),
            lostFollowers: $lostUsers,
        );
    }
}
