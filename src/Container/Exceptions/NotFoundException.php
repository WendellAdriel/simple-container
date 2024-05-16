<?php

namespace WendellAdriel\SimpleContainer\Container\Exceptions;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

final class NotFoundException extends Exception implements NotFoundExceptionInterface
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
