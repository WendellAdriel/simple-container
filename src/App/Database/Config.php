<?php

declare(strict_types=1);

namespace WendellAdriel\SimpleContainer\App\Database;

final readonly class Config
{
    public function __construct(
        public string $host = '127.0.0.1',
        public string $user = 'root',
        public ?string $password = null,
        public string $database = 'simple_container',
    ) {
    }
}
