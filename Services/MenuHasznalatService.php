<?php

namespace Services;

/**
 * Dolgozónkénti „Gyakran használt" menüpontok. Minden megnyitott menüpont-oldal pontot kap,
 * a korábbiak minden új megnyitásnál halványulnak, így a lista a mostani szokásokat követi.
 * A pontszámok JSON-ként a felületi beállítások között vannak (UiAppearanceService::getPref/savePref).
 */
class MenuHasznalatService
{
    const PREF = 'menuhasznalat';

    const MAX_DB = 8;

    /** ennyi pont alatt még nem „gyakori": nagyjából két megnyitás kell */
    const MIN_PONT = 1.5;

    const HALVANYITAS = .97;

    /** ennél több menüpont pontszámát nem tároljuk */
    const TAROLT_DB = 40;

    /**
     * A megnyitott menüpont (a menü url-je pontosan) pontot kap. A csoport nélküli pontok
     * (Főoldal, Kijelentkezés, …) úgyis mindig látszanak, azok nem számítanak.
     */
    public static function record(string $url, array $menu): void
    {
        if (!in_array($url, self::getCsoportosUrlek($menu), true)) {
            return;
        }
        $pontok = [];
        foreach (self::load() as $u => $p) {
            $p = round($p * self::HALVANYITAS, 3);
            if ($p >= .05) {
                $pontok[$u] = $p;
            }
        }
        $pontok[$url] = ($pontok[$url] ?? 0) + 1;
        arsort($pontok);
        UiAppearanceService::savePref(self::PREF, json_encode(array_slice($pontok, 0, self::TAROLT_DB, true)));
    }

    /** A menü elemei (getMenu() formában) a gyakoriság sorrendjében, legfeljebb MAX_DB darab. */
    public static function getGyakori(array $menu): array
    {
        $elemek = [];
        foreach ($menu as $elem) {
            if ($elem['mcsnev'] && !isset($elemek[$elem['url']])) {
                $elemek[$elem['url']] = $elem;
            }
        }
        $gyakori = [];
        foreach (self::load() as $url => $pont) {
            if ($pont >= self::MIN_PONT && isset($elemek[$url])) {
                $gyakori[] = $elemek[$url];
            }
            if (count($gyakori) >= self::MAX_DB) {
                break;
            }
        }
        return $gyakori;
    }

    private static function load(): array
    {
        $pontok = json_decode((string)UiAppearanceService::getPref(self::PREF, ''), true);
        if (!is_array($pontok)) {
            return [];
        }
        arsort($pontok);
        return $pontok;
    }

    private static function getCsoportosUrlek(array $menu): array
    {
        return array_column(array_filter($menu, fn($elem) => (bool)$elem['mcsnev']), 'url');
    }
}
