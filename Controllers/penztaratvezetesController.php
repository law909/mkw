<?php

namespace Controllers;

use Entities\Bizonylattipus;
use Entities\Jogcim;
use Entities\Partner;
use Entities\Penztar;
use Entities\Penztarbizonylatfej;
use Entities\Penztarbizonylattetel;
use Entities\Valutanem;

/**
 * Pénztárak közti átvezetés rögzítője. Egy mentés két pénztárbizonylatot képez: a "honnan"
 * pénztárban egy kifizetést (irány -1), a "hová" pénztárban egy befizetést (irány +1),
 * mindkettőn egy tétellel, azonos összeggel. A rögzítő mezőkészlete a pénztárbizonylat
 * rögzítőé, azzal a különbséggel, hogy pénztárból kettő van és az irány nem választható.
 *
 * Saját listája nincs: a képzett bizonylatok a pénztárbizonylat listán jelennek meg, a
 * rögzítő is onnan (és a pénztárbizonylattétel listáról) indul.
 */
class penztaratvezetesController extends \mkwhelpers\MattableController
{
    const BIZTIPUS = 'penztar';

    public function __construct()
    {
        $this->setEntityName(Penztarbizonylatfej::class);
        $this->setKarbFormTplName('penztaratvezeteskarbform.tpl');
        $this->setKarbTplName('penztaratvezeteskarb.tpl');
        $this->setPageTitle('Pénztár átvezetés');
        parent::__construct();
    }

    /**
     * Az átvezetésnek nincs saját listája – a jquery.mattkarb.js a karb bezárásakor ide esik
     * vissza, ha nem a listáról jöttünk, ezért a pénztárbizonylat listára irányítunk.
     */
    public function viewlist()
    {
        header('Location: ' . \mkw\store::getRouter()->generate('adminpenztarbizonylatfejviewlist'));
    }

    /**
     * Az átvezetés nem meglévő bizonylatot szerkeszt, ezért a form mindig üresen indul;
     * a $t paramétert csak a MattableController jelenléte miatt vesszük át.
     *
     * @param \Entities\Penztarbizonylatfej|null $t
     * @param bool $forKarb
     *
     * @return array
     */
    protected function loadVars($t = null, $forKarb = false)
    {
        return [
            'id' => '',
            'keltstr' => date(\mkw\store::$DateFormat),
            'arfolyam' => 1,
            'erbizonylatszam' => '',
            'megjegyzes' => '',
            'szoveg' => '',
            'osszeg' => ''
        ];
    }

    protected function setVars($view)
    {
        $bt = $this->getRepo(Bizonylattipus::class)->find(self::BIZTIPUS);
        if ($bt) {
            $bt->setTemplateVars($view);
        }

        $penztar = new penztarController();
        $view->setVar('penztarlist', $penztar->getSelectList());

        $jogcim = new jogcimController();
        $view->setVar('jogcimlist', $jogcim->getSelectList());

        // a valutanemet nem lehet választani: a kiválasztott pénztáré (a JS állítja be)
        $valutanem = new valutanemController();
        $view->setVar('valutanemlist', $valutanem->getSelectList());

        // Az átvezetés belső pénzmozgás, ezért alapból a saját cég partnere (setup: tulajpartner)
        // kerül a bizonylatokra. A mező átállítható, sőt üresen is hagyható.
        $partner = new partnerController();
        $view->setVar('partnerlist', $partner->getSelectList(\mkw\store::getParameter(\mkw\consts::Tulajpartner)));
    }

    protected function _getkarb($tplname)
    {
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t($this->getPageTitle()));
        $view->setVar('oper', $this->params->getRequestParam($this->operationName, $this->addOperation));
        // a mentés útvonala zárt bolton nincs regisztrálva, ezért nem a routerből kérjük
        $view->setVar('formaction', '/admin/penztaratvezetes/save');
        $view->setVar('egyed', $this->loadVars(null, true));

        $this->setVars($view);

        return $view->getTemplateResult();
    }

    /**
     * A két pénztárbizonylat képzése. Mindkettő vagy létrejön, vagy egyik sem – ezért megy
     * tranzakcióban: fél átvezetés (csak a kivét vagy csak a bevét) meghamisítaná a pénztárat.
     *
     * A felhasználót érintő ellenőrzéseket a rögzítő már mentés előtt elvégzi (lásd
     * penztaratvezetes.js), az itteniek visszacsapó biztosítékok: ha valamelyik mégis megfog
     * valamit, nem képzünk bizonylatot, és naplózzuk az okát.
     */
    public function save()
    {
        /** @var \Entities\Penztar $honnan */
        $honnan = $this->getRepo(Penztar::class)->find($this->params->getIntRequestParam('honnanpenztar'));
        /** @var \Entities\Penztar $hova */
        $hova = $this->getRepo(Penztar::class)->find($this->params->getIntRequestParam('hovapenztar'));
        if (!$honnan || !$hova) {
            $this->hiba('nincs kiválasztva mindkét pénztár');
            return;
        }
        if ($honnan->getId() == $hova->getId()) {
            $this->hiba('a két pénztár ugyanaz: ' . $honnan->getId());
            return;
        }
        if ($honnan->getValutanemId() != $hova->getValutanemId()) {
            $this->hiba('a két pénztár valutaneme különbözik: ' . $honnan->getId() . ' / ' . $hova->getId());
            return;
        }

        $osszeg = $this->params->getFloatRequestParam('osszeg');
        if ($osszeg <= 0) {
            $this->hiba('az összeg nem pozitív: ' . $osszeg);
            return;
        }

        /** @var \Entities\Jogcim $jogcim */
        $jogcim = $this->getRepo(Jogcim::class)->find($this->params->getIntRequestParam('jogcim'));
        if (!$jogcim) {
            $this->hiba('nincs kiválasztva jogcím');
            return;
        }

        $bizonylattipus = $this->getRepo(Bizonylattipus::class)->find(self::BIZTIPUS);
        if (!$bizonylattipus) {
            $this->hiba('hiányzik a pénztárbizonylat típus');
            return;
        }

        $kelt = $this->params->getStringRequestParam('kelt');
        foreach ([$honnan, $hova] as $p) {
            if ($this->penztarZart($p, $kelt)) {
                $this->hiba('a pénztár időszaka zárt: ' . $p->getId());
                return;
            }
        }

        // a pénztár valutaneme a mérvadó (a kettő egyezik, fent ellenőriztük); ha nincs
        // beállítva, az alapértelmezett valutanembe kerül az átvezetés
        $valutanem = $honnan->getValutanem();
        if (!$valutanem) {
            $valutanem = $this->getRepo(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
        }
        $arfolyam = $this->params->getNumRequestParam('arfolyam');
        if (!$arfolyam) {
            $arfolyam = 1;
        }
        $partner = $this->getRepo(Partner::class)->find($this->params->getIntRequestParam('partner'));

        // a két bizonylat egymásra hivatkozását a megjegyzés/szöveg hordozza – így a
        // pénztárbizonylat listán is látszik, hogy a pár melyik két pénztár közt mozgott
        $utal = t('Átvezetés') . ': ' . $honnan->getNev() . ' -> ' . $hova->getNev();
        $megjegyzes = trim($this->params->getStringRequestParam('megjegyzes'));
        $megjegyzes = $megjegyzes ? $utal . ' - ' . $megjegyzes : $utal;
        $szoveg = trim($this->params->getStringRequestParam('szoveg')) ?: $utal;
        $erbizonylatszam = $this->params->getStringRequestParam('erbizonylatszam');

        $this->getEm()->beginTransaction();
        try {
            $ki = $this->createBizonylat($bizonylattipus, $honnan, -1, $kelt, $valutanem, $arfolyam, $partner, $megjegyzes, $erbizonylatszam);
            $be = $this->createBizonylat($bizonylattipus, $hova, 1, $kelt, $valutanem, $arfolyam, $partner, $megjegyzes, $erbizonylatszam);
            foreach ([$ki, $be] as $fej) {
                $tetel = new Penztarbizonylattetel();
                $fej->addBizonylattetel($tetel);
                $tetel->setJogcim($jogcim);
                $tetel->setHivatkozottdatum($kelt);
                $tetel->setSzoveg($szoveg);
                $tetel->setBrutto($osszeg);
                $this->getEm()->persist($tetel);
            }
            $this->getEm()->flush();
            $this->getEm()->commit();
        } catch (\Exception $e) {
            $this->getEm()->rollback();
            $this->hiba($e->getMessage());
        }
    }

    /**
     * @param \Entities\Bizonylattipus $bizonylattipus
     * @param \Entities\Penztar $penztar
     * @param int $irany
     * @param string $kelt
     * @param \Entities\Valutanem|null $valutanem
     * @param float $arfolyam
     * @param \Entities\Partner|null $partner
     * @param string $megjegyzes
     * @param string $erbizonylatszam
     *
     * @return \Entities\Penztarbizonylatfej
     */
    private function createBizonylat($bizonylattipus, $penztar, $irany, $kelt, $valutanem, $arfolyam, $partner, $megjegyzes, $erbizonylatszam)
    {
        $fej = new Penztarbizonylatfej();
        // A sorrend kötött: a setBizonylattipus() a típus irányát teszi a fejre, ezért az
        // átvezetés iránya csak utána jöhet. A PenztarbizonylatfejListener::generateId() a
        // persist()-kor a bizonylattipus + penztar + irany + kelt mezőkből képzi az
        // azonosítót, tehát azoknak addigra készen kell lenniük.
        $fej->setBizonylattipus($bizonylattipus);
        $fej->setIrany($irany);
        $fej->setPenztar($penztar);
        $fej->setKelt($kelt);
        // a setPartner() a partner valutanemét is ráteszi a fejre, ezért utána írjuk
        // vissza a pénztárét
        if ($partner) {
            $fej->setPartner($partner);
        }
        if ($valutanem) {
            $fej->setValutanem($valutanem);
        }
        $fej->setArfolyam($arfolyam);
        $fej->setMegjegyzes($megjegyzes);
        $fej->setErbizonylatszam($erbizonylatszam);
        $this->getEm()->persist($fej);
        return $fej;
    }

    /**
     * Zárt-e a pénztár időszaka a megadott keltre. A teljes jogú felhasználót – a
     * penztarbizonylatfejController::checkZartIdoszak()-hoz hasonlóan – nem korlátozzuk.
     *
     * @param \Entities\Penztar $penztar
     * @param string $kelt
     *
     * @return bool
     */
    private function penztarZart($penztar, $kelt)
    {
        if (\mkw\store::getAdminSession()->loggedinuser['jog'] == 999) {
            return false;
        }
        $zart = \mkw\store::getParameter(\mkw\consts::PenztarZarva . $penztar->getId());
        if (!$zart) {
            return false;
        }
        $datum = new \DateTime(\mkw\store::convDate($kelt ?: date(\mkw\store::$DateFormat)));
        // a zárás dátuma Y-m-d alakban van tárolva, azon a lexikografikus összehasonlítás jó
        return $datum->format(\mkw\store::$SQLDateFormat) <= $zart;
    }

    private function hiba($uzenet)
    {
        \mkw\store::writelog($uzenet, 'penztaratvezetes.log');
    }
}
