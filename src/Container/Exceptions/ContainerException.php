<?php

namespace WendellAdriel\SimpleContainer\Container\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final class ContainerException extends Exception implements ContainerExceptionInterface
{
    public function __construct(string $message, Exception $previous = null)
    {
        parent::__construct(message: $message, previous: $previous);
    }
}
