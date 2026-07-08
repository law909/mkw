<?php

namespace mkw;

/**
 * v0 full-page cache for the public (main) storefront.
 *
 * Serves cached HTML only for ANONYMOUS visitors with an EMPTY cart, on a fixed
 * allow-list of GET content routes. This is data-safe because the only
 * per-session parts of an anonymous empty-cart page are the (empty) mini-cart
 * and the "continue shopping" prevuri link -- and prevuri is neutralised while
 * a page is being captured (see \mkw\store::fillTemplate).
 *
 * OFF by default. Enable per deployment in <owner>config.ini:
 *     pagecache     = 1
 *     pagecache.ttl = 300          ; seconds, optional (default 300)
 *
 * Cache files live under <path.storage>/pagecache/. Invalidation is TTL-based;
 * bump the global version (purges everything at once) on deploy via
 * \mkw\pagecache::bumpVersion() or by deleting that directory.
 *
 * The allow-list of cacheable GET routes is read from the `pagecacheroutes`
 * parameter (comma-separated route names, seeded by runonce from
 * self::DEFAULT_ROUTES); edit that parameter to add/remove routes per deployment
 * without a code change. An empty parameter caches nothing.
 *
 * Logged-in visitors and non-empty carts always bypass the cache and render
 * live, so per-partner pricing / B2B deployments are never at risk. Product
 * detail pages ARE cached; if a deployment's mainController::termek() branch
 * increments per-product view counters, either remove the product routes from
 * the `pagecacheroutes` parameter or add a client-side beacon (a v1 item).
 */
final class pagecache
{
    /**
     * Default cacheable GET route names (see mainroute.php). Only the fallback:
     * the effective list comes from the `pagecacheroutes` parameter, which
     * runonce seeds from this constant. Exposed so runonce can write the CSV.
     */
    public const DEFAULT_ROUTES = [
        'home',
        'showtermek',
        'showtermekszin',
        'showproduct',
        'showproductszin',
        'showtermekfa',
        'showtermekmenu',
        'showmarka',
        'markak',
        'showstatlap',
        'showhir',
        'showhirlist',
        'newsindex',
        'news',
        'showblogposzt',
        'showblogposztlist',
    ];

    private static bool $capturing = false;
    private static bool $skip = false;

    public static function enabled(): bool
    {
        return (bool)store::getConfigValue('pagecache');
    }

    /** True while a cacheable page is being rendered into the output buffer. */
    public static function isCapturing(): bool
    {
        return self::$capturing;
    }

    /** A controller may call this to prevent the current page being stored. */
    public static function skip(): void
    {
        self::$skip = true;
    }

    /**
     * Serve-or-capture entry point, called right before dispatch.
     * On a cache HIT it echoes the page and exits the request.
     * Otherwise returns the cache key (capturing has started) or '' when the
     * request is not cacheable.
     */
    public static function begin($match): string
    {
        if (!self::isCandidate($match)) {
            return '';
        }
        // v0: cache/serve for anonymous visitors with an empty cart only.
        if (store::getLoggedInUser() || !self::cartIsEmpty()) {
            return '';
        }
        $key = self::keyFor(store::getOrszagId());
        $html = self::read($key);
        if ($html !== null) {
            header('X-Page-Cache: HIT');
            echo $html;
            exit;
        }
        header('X-Page-Cache: MISS');
        self::$capturing = true;
        ob_start();
        return $key;
    }

    /** Store the captured page (when 200 and not skipped) and flush it. */
    public static function commit(string $key): void
    {
        if (!self::$capturing) {
            return;
        }
        $html = ob_get_clean();
        self::$capturing = false;
        if ($html === false) {
            return;
        }
        if (http_response_code() === 200 && !self::$skip) {
            self::write($key, $html);
        }
        echo $html;
    }

    /** Drop the capture buffer without storing (used on 404 / error paths). */
    public static function discard(): void
    {
        if (self::$capturing) {
            @ob_end_clean();
            self::$capturing = false;
        }
    }

    /** Invalidate the whole page cache (call on deploy / mass content change). */
    public static function bumpVersion(): void
    {
        $dir = self::dir();
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        @file_put_contents($dir . 'VERSION', (string)time(), LOCK_EX);
    }

    // -- internals ---------------------------------------------------------

    private static function isCandidate($match): bool
    {
        return self::enabled()
            && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET'
            && is_array($match)
            && isset($match['name'])
            && in_array($match['name'], self::routes(), true);
    }

    /** The effective allow-list: from the `pagecacheroutes` parameter, or the
     *  built-in default until runonce writes it. */
    private static function routes(): array
    {
        $raw = store::getParameter(\mkw\consts::PagecacheRoutes, null);
        if ($raw === null) {
            return self::DEFAULT_ROUTES; // not seeded yet
        }
        return array_values(array_filter(array_map('trim', explode(',', $raw)), 'strlen'));
    }

    /** Comma-separated default route names (used by runonce to seed the param). */
    public static function defaultRoutesCsv(): string
    {
        return implode(',', self::DEFAULT_ROUTES);
    }

    private static function cartIsEmpty(): bool
    {
        try {
            return store::getEm()
                ->getRepository(\Entities\Kosar::class)
                ->isEmptyBySessionId(session::getId());
        } catch (\Throwable $e) {
            return false;
        }
    }

    private static function keyFor($orszagId): string
    {
        $parts = [
            store::getConfigValue('main.theme', ''),
            store::getSetupValue('webshopnum', '1'),
            (string)store::getWebshopLongLocale(),
            (string)$orszagId,
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            $_SERVER['REQUEST_URI'] ?? '',
        ];
        return sha1(implode('|', $parts)) . '.' . self::version();
    }

    private static function read(string $key): ?string
    {
        $f = self::dir() . $key . '.html';
        if (is_file($f) && (time() - filemtime($f)) < self::ttl()) {
            $html = file_get_contents($f);
            return $html === false ? null : $html;
        }
        return null;
    }

    private static function write(string $key, string $html): void
    {
        $dir = self::dir();
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        $tmp = $dir . $key . '.' . getmypid() . '.tmp';
        if (file_put_contents($tmp, $html, LOCK_EX) !== false) {
            @rename($tmp, $dir . $key . '.html'); // atomic replace
        } else {
            @unlink($tmp);
        }
    }

    private static function ttl(): int
    {
        return (int)store::getConfigValue('pagecache.ttl', 300);
    }

    private static function version(): string
    {
        $f = self::dir() . 'VERSION';
        return is_file($f) ? trim((string)file_get_contents($f)) : '0';
    }

    private static function dir(): string
    {
        $storage = store::getConfigValue('path.storage', 'storage/');
        if (strncmp($storage, '/', 1) !== 0) {
            $storage = dirname(__DIR__) . '/' . $storage;
        }
        return rtrim($storage, '/') . '/pagecache/';
    }
}
