<?php

declare(strict_types=1);

namespace WendellAdriel\SimpleContainer\Container;

use Closure;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use WendellAdriel\SimpleContainer\Container\Exceptions\ContainerException;
use WendellAdriel\SimpleContainer\Container\Exceptions\NotFoundException;

final class Container implements ContainerInterface
{
    /** @var array<string|class-string,ContainerItemDefinition> */
    private array $definitions = [];

    /** @var array<string|class-string,mixed> */
    private array $instances = [];

    /**
     * @throws ContainerException|NotFoundException
     */
    public function get(string $id): mixed
    {
        if ($this->hasInstance($id)) {
            return $this->instances[$id];
        }

        if (! $this->has($id)) {
            if (! class_exists($id)) {
                throw new NotFoundException("'{$id}' is not a class name and is not set in the container");
            }

            $this->set(id: $id, value: $id);

            return $this->build($id);
        }

        $definition = $this->definitions[$id];
        if (is_string($definition->concrete) && class_exists($definition->concrete)) {
            $instance = $this->build($definition->concrete);
            if ($definition->singleton) {
                $this->addInstance(id: $id, value: $instance);
            }

            return $instance;
        }

        if ($definition->concrete instanceof Closure) {
            $result = call_user_func($definition->concrete, $this);
            if ($definition->singleton) {
                $this->addInstance(id: $id, value: $result);
            }

            return $result;
        }

        return $definition->concrete;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->definitions);
    }

    public function set(string $id, mixed $value = null, bool $singleton = false): void
    {
        $this->remove($id);
        if (is_null($value)) {
            $value = $id;
        }

        $this->definitions[$id] = new ContainerItemDefinition(concrete: $value, singleton: $singleton);
    }

    public function singleton(string $id, mixed $value = null): void
    {
        $this->remove($id);
        $this->set(id: $id, value: $value, singleton: true);
    }

    public function remove(string $id): void
    {
        if ($this->hasInstance($id)) {
            unset($this->instances[$id]);
        }
    }

    public function flush(): void
    {
        $this->definitions = [];
        $this->instances = [];
    }

    private function hasInstance(string $id): bool
    {
        return array_key_exists($id, $this->instances);
    }

    private function addInstance(string $id, mixed $value): void
    {
        $this->instances[$id] = $value;
    }

    /**
     * @throws ContainerException|NotFoundException
     */
    private function build(string $id): mixed
    {
        try {
            $reflector = new ReflectionClass($id);
        } catch (ReflectionException $exception) {
            throw new ContainerException("Failed to create object for '{$id}'", $exception);
        }

        if (! $reflector->isInstantiable()) {
            throw new ContainerException("'{$id}' is not instantiable");
        }

        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            try {
                return $reflector->newInstance();
            } catch (ReflectionException $exception) {
                throw new ContainerException("Failed to create object for '{$id}'", $exception);
            }
        }

        $classArguments = [];
        foreach ($constructor->getParameters() as $parameter) {
            $parameterType = $parameter->getType();
            if (is_null($parameterType) && ! $parameter->isDefaultValueAvailable()) {
                throw new ContainerException("Failed to create object for '{$id}' - Constructor parameter '{$parameter->getName()}' can't be resolved");
            }

            if (! is_null($parameterType)) {
                $typeName = $parameterType->getName();

                if (! $parameterType->isBuiltin() && (class_exists($typeName) || $this->has($typeName))) {
                    $classArguments[] = $this->get($typeName);

                    continue;
                }

                if ($parameterType->isBuiltin() && $typeName === 'array' && ! $parameter->isDefaultValueAvailable()) {
                    $classArguments[] = [];

                    continue;
                }
            }

            if ($parameter->isDefaultValueAvailable()) {
                $classArguments[] = $parameter->getDefaultValue();
            }
        }

        try {
            return $reflector->newInstanceArgs($classArguments);
        } catch (ReflectionException $exception) {
            throw new ContainerException("Failed to create object for '{$id}'", $exception);
        }
    }
}
