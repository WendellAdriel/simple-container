<?php

declare(strict_types=1);

namespace WendellAdriel\SimpleContainer\App;

use WendellAdriel\SimpleContainer\App\Database\Database;

final readonly class UserRepository
{
    public function __construct(
        private Database $database,
    ) {
    }

    public function create(string $name, string $email, string $password): bool
    {
        return $this->database->execute(
            'INSERT INTO users (name, email, password) VALUES (?, ?, ?)',
            [
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
            ]
        );
    }

    public function all(): array
    {
        return $this->database->fetchAll('SELECT * FROM users');
    }
}
