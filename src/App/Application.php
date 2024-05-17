<?php

declare(strict_types=1);

namespace WendellAdriel\SimpleContainer\App;

use WendellAdriel\SimpleContainer\Container\Container;

final class Application
{
    private Container $container;

    public function __construct()
    {
        $this->container = new Container();
        $this->container->singleton('app', $this);
    }

    public function boot(): Application
    {
        echo "Booting application...\n";
        // TODO: set container definitions

        return $this;
    }

    public function run(): void
    {
        echo "Running application...\n";
        // TODO: get items from container

        exit();
    }
}
