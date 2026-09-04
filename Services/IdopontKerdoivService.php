<?php

namespace Services;

/**
 * Az időponthoz rendelt kérdőív és a foglaláskor adott válaszok kezelése.
 *
 * A kérdőív az Idopont `kerdoiv` mezőjében JSON:
 *   {cim, leiras, kerdesek: [{szoveg, tipus: egy|tobb|szoveg, kotelezo, valaszok: [..]}]}
 * A válasz az Idopontfoglalas `kerdoivvalasz` mezőjében JSON, önmagában olvasható (a kérdés
 * szövegét is tárolja, hogy a kérdőív későbbi átírása ne tegye értelmezhetetlenné):
 *   [{kerdes, valasz: "szöveg" | ["a", "b"]}]
 *
 * A publikus űrlapon a mezők neve kerdes_<sorszám>; a több választós kerdes_<sorszám>[].
 */
class IdopontKerdoivService
{

    public const TIPUS_EGY = 'egy';
    public const TIPUS_TOBB = 'tobb';
    public const TIPUS_SZOVEG = 'szoveg';

    private const TIPUSOK = [self::TIPUS_EGY, self::TIPUS_TOBB, self::TIPUS_SZOVEG];
    private const SZOVEGMAX = 2000;

    /**
     * A tárolt (vagy a szerkesztőből érkező) JSON normalizált tömbje. Hibás JSON-ra üres kérdőív.
     *
     * @return array{cim: string, leiras: string, kerdesek: array<int, array{szoveg: string, tipus: string, kotelezo: bool, valaszok: string[]}>}
     */
    public static function parse(?string $json): array
    {
        $adat = $json ? json_decode($json, true) : null;
        return self::normalize(is_array($adat) ? $adat : []);
    }

    /**
     * Csak sima szöveg maradhat: a szerkesztőből nyersen jön az érték, a sablonok escape-elik.
     */
    private static function normalize(array $adat): array
    {
        $kerdesek = [];
        foreach ((array)($adat['kerdesek'] ?? []) as $k) {
            if (!is_array($k)) {
                continue;
            }
            $szoveg = self::text($k['szoveg'] ?? '');
            if ($szoveg === '') {
                continue;
            }
            $tipus = in_array($k['tipus'] ?? '', self::TIPUSOK, true) ? $k['tipus'] : self::TIPUS_EGY;
            $valaszok = [];
            if ($tipus !== self::TIPUS_SZOVEG) {
                $forras = $k['valaszok'] ?? [];
                // a szerkesztő soronként egy választ küld, a tárolt JSON-ban tömb
                if (is_string($forras)) {
                    $forras = preg_split('/\r\n|\r|\n/', $forras);
                }
                foreach ((array)$forras as $v) {
                    $v = self::text($v);
                    if ($v !== '' && !in_array($v, $valaszok, true)) {
                        $valaszok[] = $v;
                    }
                }
            }
            $kerdesek[] = [
                'szoveg' => $szoveg,
                'tipus' => $tipus,
                'kotelezo' => (bool)($k['kotelezo'] ?? false),
                'valaszok' => $valaszok,
            ];
        }
        return [
            'cim' => self::text($adat['cim'] ?? ''),
            'leiras' => self::text($adat['leiras'] ?? '', true),
            'kerdesek' => $kerdesek,
        ];
    }

    /** A tárolandó JSON; kérdés nélkül null, hogy a mező üres maradjon. */
    public static function encode(array $kerdoiv): ?string
    {
        if (!$kerdoiv['kerdesek'] && $kerdoiv['cim'] === '' && $kerdoiv['leiras'] === '') {
            return null;
        }
        return json_encode($kerdoiv, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * A publikus űrlap sablonjának kérdőíve: a kérdésekhez a beküldött (vagy üres) értékkel, a
     * választásoknál `checked` jelölővel, hogy a sablonnak ne kelljen tömbben keresnie.
     *
     * @param array $ertekek kérdés sorszáma => string|string[]
     */
    public static function forTemplate(array $kerdoiv, array $ertekek = []): array
    {
        foreach ($kerdoiv['kerdesek'] as $i => &$k) {
            $ertek = $ertekek[$i] ?? ($k['tipus'] === self::TIPUS_TOBB ? [] : '');
            $k['ertek'] = $k['tipus'] === self::TIPUS_SZOVEG ? (string)$ertek : '';
            $valasztott = is_array($ertek) ? $ertek : [$ertek];
            $k['opciok'] = [];
            foreach ($k['valaszok'] as $v) {
                $k['opciok'][] = ['szoveg' => $v, 'checked' => in_array($v, $valasztott, true)];
            }
        }
        unset($k);
        return $kerdoiv;
    }

    /**
     * A beküldött válaszok a kérdőív szerint ellenőrizve.
     *
     * @return array{sorok: array<int, array{kerdes: string, valasz: string|string[]}>, ertekek: array, hiba: ?string}
     */
    public static function readAnswers(array $kerdoiv, \mkwhelpers\IParameterHandler $params): array
    {
        $sorok = [];
        $ertekek = [];
        $hiba = null;
        foreach ($kerdoiv['kerdesek'] as $i => $k) {
            $mezo = 'kerdes_' . $i;
            if ($k['tipus'] === self::TIPUS_TOBB) {
                $ertek = [];
                foreach ($params->getArrayRequestParam($mezo) as $v) {
                    $v = self::text($v);
                    if (in_array($v, $k['valaszok'], true) && !in_array($v, $ertek, true)) {
                        $ertek[] = $v;
                    }
                }
                $ures = !$ertek;
            } elseif ($k['tipus'] === self::TIPUS_EGY) {
                $ertek = self::text($params->getStringRequestParam($mezo));
                if (!in_array($ertek, $k['valaszok'], true)) {
                    $ertek = '';
                }
                $ures = $ertek === '';
            } else {
                $ertek = mb_substr(self::text($params->getStringRequestParam($mezo), true), 0, self::SZOVEGMAX);
                $ures = $ertek === '';
            }
            $ertekek[$i] = $ertek;
            if ($ures && $k['kotelezo'] && !$hiba) {
                $hiba = sprintf(t('Kérjük, válaszolj a(z) %d. kérdésre: %s'), $i + 1, $k['szoveg']);
            }
            if (!$ures) {
                $sorok[] = ['kerdes' => $k['szoveg'], 'valasz' => $ertek];
            }
        }
        return ['sorok' => $sorok, 'ertekek' => $ertekek, 'hiba' => $hiba];
    }

    public static function encodeAnswers(array $sorok): ?string
    {
        return $sorok ? json_encode($sorok, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null;
    }

    /**
     * A tárolt válaszok olvasható sorai: a több választás vesszővel összefűzve.
     *
     * @return array<int, array{kerdes: string, valasz: string}>
     */
    public static function answersToRows(?string $json): array
    {
        $adat = $json ? json_decode($json, true) : null;
        $ret = [];
        foreach (is_array($adat) ? $adat : [] as $sor) {
            if (!is_array($sor) || !isset($sor['kerdes'])) {
                continue;
            }
            $valasz = $sor['valasz'] ?? '';
            $ret[] = [
                'kerdes' => (string)$sor['kerdes'],
                'valasz' => is_array($valasz) ? implode(', ', $valasz) : (string)$valasz,
            ];
        }
        return $ret;
    }

    /** Levélsablonba, naplóba: soronként „kérdés: válasz". */
    public static function answersToText(?string $json): string
    {
        $sorok = [];
        foreach (self::answersToRows($json) as $sor) {
            $sorok[] = $sor['kerdes'] . ': ' . $sor['valasz'];
        }
        return implode("\n", $sorok);
    }

    private static function text($s, bool $tobbsoros = false): string
    {
        $s = strip_tags((string)$s);
        $s = $tobbsoros ? preg_replace('/[ \t]+/', ' ', $s) : preg_replace('/\s+/', ' ', $s);
        return trim($s);
    }
}
