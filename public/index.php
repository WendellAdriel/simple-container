<?php

use WendellAdriel\SimpleContainer\App\Application;

require __DIR__ . '/../vendor/autoload.php';

(new Application())
    ->boot()
    ->run();
