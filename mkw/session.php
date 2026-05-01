<?php

namespace mkw;

class session
{
    private static bool $started = false;

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            self::$started = true;
            return;
        }

        if (headers_sent($file, $line)) {
            throw new \RuntimeException(
                "Cannot start session: headers already sent in {$file}:{$line}"
            );
        }

        session_start();
        self::$started = true;
    }

    public static function writeClose(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
            self::$started = false;
        }
    }

    public static function getId(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            self::start();
        }
        return session_id();
    }

    public static function regenerateId(): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            self::start();
        }
        return session_regenerate_id(true);
    }

    public static function namespaceUnset(string $namespace = 'Default'): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            self::start();
        }

        $namespace = self::normalizeNamespace($namespace);

        unset($_SESSION[$namespace]);
    }

    public static function destroy(bool $removeCookie = true, bool $readonly = true): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            self::start();
        }

        $_SESSION = [];

        if ($removeCookie && ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'] ?? '/',
                $params['domain'] ?? '',
                $params['secure'] ?? false,
                $params['httponly'] ?? true
            );
        }

        self::$started = false;

        return session_destroy();
    }

    public static function normalizeNamespace(?string $namespace): string
    {
        if ($namespace === null || $namespace === '') {
            return 'Default';
        }

        return $namespace;
    }
}
