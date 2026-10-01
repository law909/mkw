<?php

namespace Services\Cron;

use Services\UnasKeszletArService;
use Services\UnasService;

/**
 * Készlet és ár feltöltés az UNAS-ba. Minden futás újraszámol és csak az eltérést küldi, tehát a
 * sűrű ütemezés olcsó. Kapcsolók: `--szaraz` (csak számol), `--teljes` (mindent újraküld).
 */
class UnasKeszletArTask implements CronTask
{

    public function getDescription(): string
    {
        return 'UNAS készlet és ár feltöltés (csak a változott tételek)';
    }

    public function isEnabled(): bool
    {
        return UnasService::isEnabled()
            && (UnasKeszletArService::isKeszletEnabled() || UnasKeszletArService::isArEnabled());
    }

    public function run(array $options = []): string
    {
        $r = (new UnasKeszletArService())->sync([
            'szaraz' => !empty($options['szaraz']),
            'teljes' => !empty($options['teljes']),
        ]);

        $uzenet = sprintf(
            '%s%d UNAS termék; készlet: %s; ár: %s; %d hívás',
            $r['szaraz'] ? '[szárazfutás] ' : '',
            $r['celok'],
            $r['keszletbe']
                ? sprintf('%d változott, %d küldve, %d hiba', $r['keszlet']['valtozott'], $r['keszlet']['kuldve'], $r['keszlet']['hiba'])
                : 'ki',
            $r['arbe']
                ? sprintf(
                    '%d változott, %d küldve (%d akcióval, %d akció lejárt), %d hiba, %d ár nélkül',
                    $r['ar']['valtozott'],
                    $r['ar']['kuldve'],
                    $r['ar']['akcios'],
                    $r['ar']['akciolejarat'],
                    $r['ar']['hiba'],
                    $r['ar']['nincsar']
                )
                : 'ki',
            $r['hivasok']
        );
        if ($r['kihagyva_hiba']) {
            $uzenet .= sprintf('; %d tétel %d hiba után kihagyva (--teljes küldi újra)', $r['kihagyva_hiba'], UnasKeszletArService::MAXHIBA);
        }
        if ($r['keszlet']['unasvaltozatos']) {
            $uzenet .= sprintf('; %d UNAS-változatos termék készlete nem megy ki', $r['keszlet']['unasvaltozatos']);
        }
        if ($r['duplikalt']) {
            $uzenet .= sprintf('; %d ismétlődő UNAS azonosító', $r['duplikalt']);
        }
        if ($r['valasz_tetel_nelkul']) {
            $uzenet .= sprintf('; %d válaszban nem volt tételes visszajelzés', $r['valasz_tetel_nelkul']);
        }
        if ($r['hivashiba'] !== '') {
            $uzenet .= '; a menet leállt: ' . $r['hivashiba'];
        }
        foreach ($r['hibak'] as $h) {
            $uzenet .= "\n" . $h['unasid'] . ': ' . $h['hiba'];
        }
        if ($r['szaraz']) {
            foreach ($r['minta'] as $m) {
                $uzenet .= "\n" . $m['unasid'] . ' ' . $m['mezo'] . ': ' . ($m['regi'] ?? '-') . ' → ' . $m['uj'];
            }
        }

        if ($r['fek'] || $r['keszlet']['hiba'] || $r['ar']['hiba']) {
            throw new CronWarning($uzenet);
        }
        return $uzenet;
    }
}
