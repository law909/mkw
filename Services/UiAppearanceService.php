<?php

namespace Services;

/**
 * Az admin megjelenése dolgozónként: jQuery UI téma (Dolgozo.uitheme) és a modern téma
 * kiemelő színe (dolgozoparameterek, `uiaccent`).
 *
 * A kiemelő szín tetszőleges lehet; hogy minden szín olvasható maradjon, a világos és a
 * sötét témához külön árnyalat és hozzá szövegszín számolódik (getAccentCssVars()).
 */
class UiAppearanceService
{
    const ACCENT_PARAM = 'uiaccent';

    const DEFAULT_THEME = 'sunny';

    const DEFAULT_ACCENT = '#2563eb';

    /** A sysadmin alapértelmezése, amíg az oldalsáv alján mást nem választ (parameterek) */
    const SYSADMIN_DEFAULT_THEME = 'modern-dark';

    const SYSADMIN_DEFAULT_ACCENT = '#ff70e0';

    const THEMES = [
        'modern',
        'modern-dark',
        'black-tie',
        'blitzer',
        'cupertino',
        'dark-hive',
        'dot-luv',
        'eggplant',
        'excite-bike',
        'flick',
        'hot-sneaks',
        'humanity',
        'le-frog',
        'mint-choc',
        'overcast',
        'pepper-grinder',
        'redmond',
        'smoothness',
        'south-street',
        'start',
        'sunny',
        'swanky-purse',
        'trontastic',
        'ui-darkness',
        'ui-lightness',
        'vader',
    ];

    /** Gyorsválasztó színminták; ezeken kívül bármilyen szín keverhető */
    const ACCENT_PRESETS = [
        'blue' => ['color' => '#2563eb', 'nev' => 'Kék'],
        'indigo' => ['color' => '#4f46e5', 'nev' => 'Indigó'],
        'violet' => ['color' => '#7c3aed', 'nev' => 'Lila'],
        'pink' => ['color' => '#db2777', 'nev' => 'Rózsaszín'],
        'red' => ['color' => '#dc2626', 'nev' => 'Piros'],
        'orange' => ['color' => '#ea580c', 'nev' => 'Narancs'],
        'green' => ['color' => '#16a34a', 'nev' => 'Zöld'],
        'teal' => ['color' => '#0d9488', 'nev' => 'Türkiz'],
        'cyan' => ['color' => '#0891b2', 'nev' => 'Cián'],
        'slate' => ['color' => '#475569', 'nev' => 'Grafit'],
    ];

    /** A modern-dark témában a panelek háttere (--mkw-surface), ehhez mérjük a sötét árnyalatot */
    const DARK_SURFACE = '#18181b';

    public static function isValidTheme($theme): bool
    {
        return in_array($theme, self::THEMES, true);
    }

    /**
     * '#rrggbb' kisbetűvel, vagy null, ha nem értelmezhető. A 2026-09-25-i első változat
     * színmintanevet mentett ('green'), azt is elfogadja.
     */
    public static function normalizeAccent($accent): ?string
    {
        $accent = strtolower(trim((string)$accent));
        if (array_key_exists($accent, self::ACCENT_PRESETS)) {
            return self::ACCENT_PRESETS[$accent]['color'];
        }
        return preg_match('/^#[0-9a-f]{6}$/', $accent) ? $accent : null;
    }

    public static function getAccentFor($dolgozoid): string
    {
        return self::normalizeAccent(DolgozoParameterService::getParameterFor($dolgozoid, self::ACCENT_PARAM))
            ?? self::DEFAULT_ACCENT;
    }

    public static function setAccentFor($dolgozoid, $accent): void
    {
        $accent = self::normalizeAccent($accent);
        if ($accent) {
            DolgozoParameterService::setParameterFor($dolgozoid, self::ACCENT_PARAM, $accent);
        }
    }

    /** A bejelentkezett dolgozó színe; a sysadminé a paraméterekben (setSysadmin()). */
    public static function getCurrentAccent(): string
    {
        if (\mkw\store::getAdminSession()->pk == -1) {
            return self::normalizeAccent(\mkw\store::getParameter(\mkw\consts::SysadminUiaccent)) ?? self::SYSADMIN_DEFAULT_ACCENT;
        }
        $dolgozoid = DolgozoParameterService::getDolgozoId();
        return $dolgozoid ? self::getAccentFor($dolgozoid) : self::DEFAULT_ACCENT;
    }

    /** Felületi beállítások, amelyeket a kliens menthet (setuipref); más név nem írható. */
    const PREFS = ['oldalsavrejtve', 'gyakrannyitva'];

    /** Dolgozónként a dolgozoparameterek-ben, a sysadminnál 'sysadmin' előtaggal a parameterek-ben. */
    public static function getPref(string $name, $default = null)
    {
        if (\mkw\store::getAdminSession()->pk == -1) {
            return \mkw\store::getParameter('sysadmin' . $name, $default);
        }
        return DolgozoParameterService::getParameter($name, $default);
    }

    public static function setPref(string $name, string $value): void
    {
        if (in_array($name, self::PREFS, true)) {
            self::savePref($name, $value);
        }
    }

    /** Szerveroldali mentés a PREFS-szűrés nélkül (pl. a menühasználat számlálója). */
    public static function savePref(string $name, string $value): void
    {
        if (\mkw\store::getAdminSession()->pk == -1) {
            \mkw\store::setParameter('sysadmin' . $name, $value);
            return;
        }
        DolgozoParameterService::setParameter($name, $value);
    }

    /** Csak a sysadmin belépésnek; az érvénytelen értéket figyelmen kívül hagyja. */
    public static function setSysadmin($theme, $accent): void
    {
        if (\mkw\store::getAdminSession()->pk != -1) {
            return;
        }
        if (self::isValidTheme($theme)) {
            \mkw\store::setParameter(\mkw\consts::SysadminUitheme, $theme);
        }
        $accent = self::normalizeAccent($accent);
        if ($accent) {
            \mkw\store::setParameter(\mkw\consts::SysadminUiaccent, $accent);
        }
    }

    /**
     * A <html> style attribútumába kerülő változók. A világos témában a szín addig sötétedik,
     * amíg fehér háttéren (és fehér gombfelirattal) legalább 3:1 a kontraszt, a sötétben addig
     * világosodik, amíg a panelháttéren 4.5:1 – így a sárga vagy a sötétkék is olvasható marad.
     */
    public static function getAccentCssVars(string $accent): string
    {
        $base = self::hexToRgb(self::normalizeAccent($accent) ?? self::DEFAULT_ACCENT);
        $white = [255, 255, 255];
        $light = self::mixUntil($base, [0, 0, 0], 0, fn($c) => self::contrast($c, $white) >= 3.0);
        $dark = self::mixUntil($base, $white, .3, fn($c) => self::contrast($c, self::hexToRgb(self::DARK_SURFACE)) >= 4.5);
        return '--mkw-accent-l: ' . self::rgbToHex($light) . '; --mkw-accent-d: ' . self::rgbToHex($dark) . ';';
    }

    private static function mixUntil(array $color, array $target, float $start, callable $ok): array
    {
        for ($t = $start; $t < 1; $t += .05) {
            $mixed = self::mix($color, $target, $t);
            if ($ok($mixed)) {
                return $mixed;
            }
        }
        return $target;
    }

    private static function mix(array $a, array $b, float $t): array
    {
        return array_map(fn($x, $y) => (int)round($x * (1 - $t) + $y * $t), $a, $b);
    }

    private static function contrast(array $a, array $b): float
    {
        $la = self::luminance($a);
        $lb = self::luminance($b);
        return (max($la, $lb) + .05) / (min($la, $lb) + .05);
    }

    private static function luminance(array $rgb): float
    {
        $lin = array_map(function ($c) {
            $c /= 255;
            return $c <= .03928 ? $c / 12.92 : (($c + .055) / 1.055) ** 2.4;
        }, $rgb);
        return .2126 * $lin[0] + .7152 * $lin[1] + .0722 * $lin[2];
    }

    private static function hexToRgb(string $hex): array
    {
        return array_map('hexdec', str_split(substr($hex, 1), 2));
    }

    private static function rgbToHex(array $rgb): string
    {
        return vsprintf('#%02x%02x%02x', $rgb);
    }
}
