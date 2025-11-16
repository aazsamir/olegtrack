<?php

declare(strict_types=1);

namespace App;

use App\Service\AnalyseService;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\HasConsole;

class AnalyseCommand
{
    use HasConsole;

    public function __construct(
        private AnalyseService $analyseService,
    ) {}

    #[ConsoleCommand(
        name: 'analyse',
        description: 'Analyse data from database',
    )]
    public function __invoke(string $username): void
    {
        $result = $this->analyseService->analyse($username);

        $this->console->info('Analysis for user: ' . $username);
        $this->console->writeln('All-time followers: ' . $result->allTimeFollowers);
        $this->console->writeln('Latest followers: ' . $result->latestFollowers);

        if (count($result->lostFollowers) === 0) {
            $this->console->writeln('No lost followers.');

            return;
        }

        foreach ($result->lostFollowers as $follower) {
            $this->console->writeln('Lost follower: ' . $follower->username);
        }
    }
}
