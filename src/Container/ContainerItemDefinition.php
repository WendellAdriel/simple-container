<?php

namespace WendellAdriel\SimpleContainer\Container;

final readonly class ContainerItemDefinition
{
    public function __construct(
        public mixed $concrete,
        public bool $singleton = false,
    ) {
    }
}
