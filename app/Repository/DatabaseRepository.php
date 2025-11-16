<?php

declare(strict_types=1);

namespace App\Repository;

use Tempest\Database\Database;
use Tempest\Database\Query;
use Tempest\Database\Transactions\TransactionManager;

use function Tempest\Database\query;

class DatabaseRepository
{
    public function __construct(
        private Database $db,
        private TransactionManager $transactionManager,
    ) {}

    public function beginTransaction(): void
    {
        $this->transactionManager->begin();
    }

    public function commit(): void
    {
        $this->transactionManager->commit();
    }

    public function rollBack(): void
    {
        $this->transactionManager->rollBack();
    }

    /**
     * @param User[] $users
     */
    public function saveUsers(array $users): void
    {
        foreach ($users as $user) {
            $this->saveUser($user);
        }
    }

    public function saveUser(User $user): void
    {
        // check if it exists
        $existingUser = $this->findUser($user->username);

        if ($existingUser === null) {
            // insert
            query('users')
                ->insert([
                    'username' => $user->username,
                    'fullname' => $user->fullname,
                    'avatar' => $user->avatar,
                    'created_at' => $user->createdAt->format('Y-m-d H:i:s'),
                    'updated_at' => $user->updatedAt->format('Y-m-d H:i:s'),
                ])
                ->execute();
        } else {
            $data = [
                'username' => $user->username,
                'updated_at' => $user->updatedAt->format('Y-m-d H:i:s'),
            ];

            if ($user->avatar !== null) {
                $data['avatar'] = $user->avatar;
            }

            // update
            query('users')
                ->update(...$data)
                ->where('username = ?', $user->username)
                ->execute();
        }
    }

    public function findUser(string $username): ?User
    {
        $query = query('users')
            ->select()
            ->where('username = ?', $username);

        $result = $query->first();

        if ($result === null) {
            return null;
        }

        return new User(
            username: $result['username'],
            fullname: $result['fullname'],
            avatar: $result['avatar'],
            createdAt: new \DateTimeImmutable($result['created_at']),
            updatedAt: new \DateTimeImmutable($result['updated_at']),
        );
    }

    /**
     * @param Follower[] $followers
     */
    public function saveFollowers(array $followers): void
    {
        foreach ($followers as $follower) {
            $this->saveFollower($follower);
        }
    }

    public function saveFollower(Follower $follower): void
    {
        query('followers')
            ->insert([
                'follower' => $follower->follower,
                'follows' => $follower->follows,
                'date' => $follower->date->format('Y-m-d H:i:s'),
            ])
            ->execute();
    }

    /**
     * @return Follower[]
     */
    public function getAllTimeFollowers(string $username)
    {
        $query = <<<SQL
            SELECT
                DISTINCT follower
            FROM
                followers
            WHERE
                follows = ?
        SQL;
        $query = new Query($query, [$username]);

        $results = $this->db->fetch($query);
        $results = \array_map(
            static fn (array $row) => new Follower(
                follower: $row['follower'],
                follows: $username,
            ),
            $results,
        );

        return $results;
    }

    public function getLatestFollowers(string $username): array
    {
        $since = $this->getLastSaveFollowerDate($username);

        if ($since === null) {
            return [];
        }

        $query = <<<SQL
            SELECT
                DISTINCT follower, follows
            FROM
                followers
            WHERE
                follows = ?
                AND date >= ?
            ORDER BY
                date DESC
        SQL;

        $query = new Query($query, [
            $username,
            $since->format('Y-m-d'),
        ]);

        $results = $this->db->fetch($query);

        return \array_map(
            static fn (array $row) => new Follower(
                follower: $row['follower'],
                follows: $row['follows'],
            ),
            $results,
        );
    }

    public function getLastSaveFollowerDate(string $username): ?\DateTimeImmutable
    {
        $query = query('followers')
            ->select('MAX(date) as date')
            ->where('follows = ?', $username);

        $result = $query->first();

        if ($result === null) {
            return null;
        }

        return new \DateTimeImmutable($result['date']);
    }

    /**
     * @return string[]
     */
    public function getFollowedUsers(): array
    {
        $query = <<<SQL
            SELECT
                distinct follows
            FROM
                followers
        SQL;

        $query = new Query($query);

        $results = $this->db->fetch($query);

        return \array_map(
            static fn (array $row) => $row['follows'],
            $results,
        );
    }

    public function saveTrackedUser(string $username): void
    {
        query('tracked_users')
            ->insert([
                'username' => $username,
                'created_at' => new \DateTimeImmutable()->format('Y-m-d H:i:s'),
            ])
            ->execute();
    }

    /**
     * @return string[]
     */
    public function getTrackedUsers(): array
    {
        $query = query('tracked_users')
            ->select('username');

        $results = $this->db->fetch($query);

        return \array_map(
            static fn (array $row) => $row['username'],
            $results,
        );
    }

    public function removeTrackedUser(string $username): void
    {
        query('tracked_users')
            ->delete()
            ->where('username = ?', $username)
            ->execute();
    }
}
