<?php

declare(strict_types=1);

namespace App;

use App\Service\ScrapService;
use Tempest\Console\ConsoleCommand;

class ScrapCommand
{
    public function __construct(
        private ScrapService $scrapService,
    ) {}

    #[ConsoleCommand(
        name: 'scrap',
        description: 'Scrap data from sources',
    )]
    public function __invoke(string $username): void
    {
        $this->scrapService->scrap($username);
    }
}
