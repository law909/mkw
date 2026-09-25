<?php

namespace Services;

/**
 * A modern admin téma kiemelő színe dolgozónként. A téma ebből az egy színből számolja
 * az árnyalatokat (themes/ui/modern/jquery-ui.css, --mkw-accent-base).
 *
 * Csak a listában szereplő színek választhatók: az érték style attribútumba kerül, és
 * mindegyik fehér szöveggel is olvasható gombot ad.
 */
class UiAccentService
{
    const PARAM = 'uiaccent';

    const DEFAULT = 'blue';

    const COLORS = [
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

    public static function getCurrent(): string
    {
        // a sysadmin nem dolgozó: nála a session tárolja, mint a témát
        if (\mkw\store::getAdminSession()->pk == -1) {
            $lu = \mkw\store::getAdminSession()->loggedinuser;
            $accent = is_array($lu) ? ($lu[self::PARAM] ?? null) : null;
        } else {
            $accent = DolgozoParameterService::getParameter(self::PARAM);
        }
        return array_key_exists((string)$accent, self::COLORS) ? $accent : self::DEFAULT;
    }

    public static function getCurrentColor(): string
    {
        return self::COLORS[self::getCurrent()]['color'];
    }

    public static function setCurrent(string $accent): void
    {
        if (!array_key_exists($accent, self::COLORS)) {
            return;
        }
        if (\mkw\store::getAdminSession()->pk == -1) {
            $lu = \mkw\store::getAdminSession()->loggedinuser;
            if (is_array($lu)) {
                $lu[self::PARAM] = $accent;
                \mkw\store::getAdminSession()->loggedinuser = $lu;
            }
            return;
        }
        DolgozoParameterService::setParameter(self::PARAM, $accent);
    }
}
