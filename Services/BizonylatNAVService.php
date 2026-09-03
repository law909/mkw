<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\BizonylatfejRepository;
use mkwhelpers\FilterDescriptor;

/**
 * A NAV Online Számla felé menő bizonylat műveletek: ellenőrzés, beküldés, eredmény
 * lekérdezés, riasztás-számlálás.
 *
 * A hibákat a NAVOnline HTML szövegeként adja vissza, kiírni nem ír ki semmit — azt a hívó
 * dönti el. Minden művelet csendben kihagyja azt a bizonylatot, amelyik nem beküldendő
 * (a típusa nem az, vagy a bizonylaton nincs bejelölve).
 */
class BizonylatNAVService
{
    /** Ettől a naptól van online adatszolgáltatás, a riasztások csak ez utánra néznek. */
    private const RIASZTASKEZDET = '2021-04-01';

    /**
     * Számla beküldése a NAV-hoz. Sikeres beküldés után a bizonylatra rákerül a NAV
     * válaszállapota (naveredmeny).
     *
     * @return string|false hibaszöveg, ha a beküldés nem sikerült
     */
    public function send($bizszam)
    {
        $biz = $this->getBekuldendo($bizszam);
        if (!$biz) {
            return false;
        }
        $no = $this->getApi();
        if ($no->sendSzamla($bizszam, $biz->toNAVOnlineXML())) {
            $this->saveEredmeny($biz, $no->getResult());
            return false;
        }
        \mkw\store::writelog($bizszam . ' sendToNAV', 'navonline.log');
        \mkw\store::writelog(print_r($no->getErrors(), true), 'navonline.log');
        return $no->getErrorsAsHtml();
    }

    /**
     * Számla ellenőriztetése a NAV-val, beküldés előtt.
     *
     * @return string|true hibaszöveg, vagy true, ha a NAV rendben találta (illetve ha a
     *                     bizonylat nem beküldendő)
     */
    public function validate($bizszam)
    {
        $biz = $this->getBekuldendo($bizszam);
        if (!$biz) {
            return true;
        }
        $no = $this->getApi();
        if (!$no->validate($biz->toNAVOnlineXML())) {
            \mkw\store::writelog($bizszam . ' validateWithNAV kapcsolat hiba', 'navonline.log');
            \mkw\store::writelog(print_r($no->getErrors(), true), 'navonline.log');
            return $no->getErrorsAsHtml();
        }
        if ($no->getResult() !== 'OK') {
            \mkw\store::writelog($bizszam . ' validateWithNAV result', 'navonline.log');
            \mkw\store::writelog(print_r($no->getResult(), true), 'navonline.log');
            return $no->getErrorsAsHtml();
        }
        return true;
    }

    /** A még nyitott beküldések állapotának lekérdezése és rávezetése a bizonylatokra. */
    public function processResults()
    {
        $bizszamok = $this->getRepo()->getNAVEredmenyFeldolgozando();
        if (!$bizszamok) {
            return;
        }
        $no = $this->getApi();
        if (!$no->getSomeSzamlaInfo($bizszamok)) {
            \mkw\store::writelog(print_r($bizszamok, true), 'navonline.log');
            \mkw\store::writelog(print_r($no->getErrors(), true), 'navonline.log');
            return;
        }
        foreach (json_decode($no->getResult()) as $res) {
            /** @var Bizonylatfej $biz */
            $biz = $this->getRepo()->find($res->bizszam);
            if ($biz) {
                $this->saveEredmeny($biz, $res->navstate);
            }
        }
    }

    /** Egy bizonylat állapotának újralekérdezése a NAV-tól. */
    public function requery($bizszam)
    {
        if ($this->getRepo()->find($bizszam)) {
            $this->getApi()->requeryFromNAV($bizszam);
        }
    }

    /**
     * A fejlécben mutatott riasztás számlálói.
     *
     * @return array{aborted: int, null: int} a NAV által elutasított és a még be nem küldött számlák darabszáma
     */
    public function countAlerts()
    {
        $aborted = $this->alertFilter();
        $aborted->addFilter('_xx.naveredmeny', '=', 'ABORTED');
        $nincs = $this->alertFilter();
        $nincs->addSql('(_xx.naveredmeny IS NULL)');
        return [
            'aborted' => (int)$this->getRepo()->getCount($aborted),
            'null' => (int)$this->getRepo()->getCount($nincs)
        ];
    }

    /** A bizonylat NAV XML-je; a régi 1.1-es felületen ezt kézzel kell beküldeni. */
    public function getXML($bizszam)
    {
        $biz = $this->getRepo()->find($bizszam);
        if ($biz && $biz->isNavbekuldendo()) {
            return $biz->toNAVOnlineXML();
        }
        return '';
    }

    /** A riasztás közös szűrője: az online adatszolgáltatás kezdete óta kelt számlák. */
    private function alertFilter()
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('kelt', '>=', self::RIASZTASKEZDET);
        $filter->addFilter('bizonylattipus', 'IN', ['szamla', 'esetiszamla']);
        return $filter;
    }

    /**
     * A bizonylat, ha tényleg beküldendő: a típusa is az, és a bizonylaton is be van jelölve
     * (a jelölőt a partner adószáma és országa dönti el, lásd Bizonylatfej::calcNAVBekuldendo).
     *
     * @return Bizonylatfej|null
     */
    private function getBekuldendo($bizszam)
    {
        /** @var Bizonylatfej $biz */
        $biz = $this->getRepo()->find($bizszam);
        if ($biz && $biz->getBizonylattipusNavbekuldendo() && $biz->isNavbekuldendo()) {
            return $biz;
        }
        return null;
    }

    /** A simpleedit kikapcsolja a listener újraszámolásait: csak a NAV állapot változik. */
    private function saveEredmeny(Bizonylatfej $biz, $eredmeny)
    {
        $biz->setSimpleedit(true);
        $biz->setNaveredmeny($eredmeny);
        \mkw\store::getEm()->persist($biz);
        \mkw\store::getEm()->flush();
    }

    private function getApi()
    {
        return new \mkwhelpers\NAVOnline(\mkw\store::getTulajAdoszam());
    }

    /**
     * @return BizonylatfejRepository
     */
    private function getRepo()
    {
        return \mkw\store::getEm()->getRepository(Bizonylatfej::class);
    }
}
