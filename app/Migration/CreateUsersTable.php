<?php

namespace App\Migration;

use Tempest\Database\MigratesDown;
use Tempest\Database\MigratesUp;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final class CreateUsersTable implements MigratesUp, MigratesDown
{
    public string $name = '2025-11-15_users';

    public function up(): QueryStatement
    {
        return new CreateTableStatement('users')
            ->primary()
            ->string('username')
            ->string('fullname', nullable: true)
            ->string('avatar', nullable: true)
            ->datetime('created_at')
            ->datetime('updated_at')
            ->index('username');
    }

    public function down(): QueryStatement
    {
        return new DropTableStatement('users');
    }
}
