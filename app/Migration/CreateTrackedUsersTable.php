<?php

declare(strict_types=1);

namespace App\Migration;

use Tempest\Database\MigratesDown;
use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final class CreateTrackedUsersTable implements MigratesUp, MigratesDown
{
    public string $name = '2025-11-16_tracked_users';

    public function up(): QueryStatement
    {
        return new CreateTableStatement('tracked_users')
            ->primary()
            ->string('username', 255)
            ->unique('username')
            ->datetime('created_at');
    }

    public function down(): QueryStatement
    {
        return new DropTableStatement('tracked_users');
    }
}
