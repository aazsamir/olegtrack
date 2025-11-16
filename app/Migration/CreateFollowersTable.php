<?php

namespace App\Migration;

use Tempest\Database\MigratesDown;
use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final class CreateFollowersTable implements MigratesUp, MigratesDown
{
    public string $name = '2025-11-15_followers';

    public function up(): QueryStatement
    {
        return new CreateTableStatement('followers')
            ->primary()
            ->string('follower')
            ->string('follows')
            ->datetime('date')
            ->index('follower', 'follows');
    }

    public function down(): QueryStatement
    {
        return new DropTableStatement('followers');
    }
}
