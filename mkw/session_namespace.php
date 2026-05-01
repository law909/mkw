<?php

namespace mkw;

class session_namespace
{
    private string $namespace;

    public function __construct(string $namespace = 'Default', bool $singleInstance = false)
    {
        \mkw\session::start();

        $this->namespace = \mkw\session::normalizeNamespace($namespace);

        if (!isset($_SESSION[$this->namespace]) || !is_array($_SESSION[$this->namespace])) {
            $_SESSION[$this->namespace] = [];
        }
    }

    public function __get(string $name): mixed
    {
        return $_SESSION[$this->namespace][$name] ?? null;
    }

    public function __set(string $name, mixed $value): void
    {
        $_SESSION[$this->namespace][$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($_SESSION[$this->namespace][$name]);
    }

    public function __unset(string $name): void
    {
        unset($_SESSION[$this->namespace][$name]);
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function toArray(): array
    {
        return $_SESSION[$this->namespace] ?? [];
    }

    public function exchangeArray(array $data): void
    {
        $_SESSION[$this->namespace] = $data;
    }
}