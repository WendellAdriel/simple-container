<?php

declare(strict_types=1);

namespace WendellAdriel\SimpleContainer\App\Database;

use PDO;
use PDOException;

final readonly class Database
{
    public PDO $pdo;

    public function __construct(
        private Config $config,
    ) {
        try {
            $this->pdo = new PDO(
                dsn: "mysql:host={$this->config->host};dbname={$this->config->database};charset=UTF8",
                username: $this->config->user,
                password: $this->config->password,
            );

            echo "Connected to the {$this->config->database} database!\n";
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }
    }

    public function execute(string $query, array $parameters = []): bool
    {
        return $this->pdo->prepare($query)
            ->execute($parameters);
    }

    public function fetchAll(string $query, array $parameters = []): array
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($parameters);

        return $statement->fetchAll(PDO::FETCH_NAMED);
    }
}
