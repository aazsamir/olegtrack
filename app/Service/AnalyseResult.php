<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\User;

readonly class AnalyseResult
{
    /**
     * @param User[] $lostFollowers
     */
    public function __construct(
        public int $allTimeFollowers,
        public int $latestFollowers,
        public array $lostFollowers,
    ) {}
}
