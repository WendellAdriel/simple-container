<?php

declare(strict_types=1);

namespace WendellAdriel\SimpleContainer\App;

use WendellAdriel\SimpleContainer\App\Database\Database;
use WendellAdriel\SimpleContainer\Container\Container;

final class Application
{
    private Container $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    public function boot(): Application
    {
        echo "Booting application...\n";
        $this->container->singleton(self::class, $this);
        $this->container->singleton(Database::class);
        $this->container->get(Database::class);

        return $this;
    }

    public function run(): void
    {
        echo "Running application...\n";

        /** @var UserRepository $userRepository */
        $userRepository = $this->container->get(UserRepository::class);

        echo "-----\n";
        echo "CREATING USER\n";
        $result = $userRepository->create(
            name: 'John Doe',
            email: 'john.doe@example.com',
            password: 'secret',
        );
        echo "SAVE RESULT: {$result}\n";

        echo "-----\n";
        echo "FETCHING USERS\n";
        $users = $userRepository->all();

        foreach ($users as $user) {
            echo sprintf("USER DATA\n%s\n", json_encode($user, JSON_PRETTY_PRINT));
        }

        exit();
    }
}
