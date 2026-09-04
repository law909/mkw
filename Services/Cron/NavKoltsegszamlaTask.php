<?php

namespace Services\Cron;

use Services\KoltsegszamlaJSONImportService;
use Services\NAVKoltsegszamlaImportService;

/**
 * A NAV-nál lévő bejövő számlák letöltése költségszámlának – ugyanaz, amit az admin
 * „NAV bejövő számla import" képernyője csinál, csak ütemezve.
 *
 * Az időszakot a szolgáltatás adja: az utolsó sikeres import vége utáni naptól a mai napig,
 * legfeljebb 30 nap; ha még nem volt import, az elmúlt 30 nap. Az utolsó importált napot a
 * `koltsegszamlaimportdatum` paraméter tárolja, és csak hibátlan futás után lép tovább, ezért
 * egy elakadt számla a következő futáskor újra sorra kerül – amíg ez így van, a feladat hibával
 * (levéllel) zár, hogy valaki ránézzen az import naplóra.
 */
class NavKoltsegszamlaTask implements CronTask
{

    public function getDescription(): string
    {
        return 'NAV bejövő számlák letöltése költségszámlának az utolsó import óta (max. 30 nap)';
    }

    public function isEnabled(): bool
    {
        if (!\mkw\store::getTulajAdoszam()) {
            return false;
        }
        try {
            (new KoltsegszamlaJSONImportService())->getKoltsegTermek();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function run(array $options = []): string
    {
        $service = new NAVKoltsegszamlaImportService();
        $idoszak = $service->kovetkezoIdoszak();
        $eredmeny = $service->import($idoszak['tol'], $idoszak['ig']);

        $osszefoglalo = sprintf(
            '%s: %d számla a NAV listáján, %d új költségszámla, %d már megvolt, %d hiba',
            $eredmeny['idoszak'],
            $eredmeny['digestdb'],
            $eredmeny['ujdb'],
            $eredmeny['letezodb'],
            $eredmeny['hibadb']
        );
        if ($eredmeny['megszakadt']) {
            throw new \Exception($osszefoglalo . ' – a feldolgozás megszakadt, lásd az import naplót');
        }
        if ($eredmeny['hibadb']) {
            throw new \Exception($osszefoglalo . ' – az időszak nem lépett tovább, lásd az import naplót');
        }
        return $osszefoglalo;
    }
}
