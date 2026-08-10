<?php

namespace Controllers;

use Entities\Leltarfej;
use Entities\Leltartetel;
use Entities\Termek;
use Entities\TermekValtozat;
use mkwhelpers\FilterDescriptor;

/**
 * Leltár felvételi lista vonalkódos rögzítése.
 *
 * A kezelés a bolti eladásé (vonalkód / keresés → sor), de itt nem bizonylat készül: a leltár
 * felvételi listájának (\Entities\Leltartetel) a tény mennyiségét írjuk. A lista üresen indul és
 * beolvasásról beolvasásra épül – leltáranként, termék+változat páronként egy sor, amit az ismételt
 * beolvasás növel. Ugyanabba a táblába ír, mint az Excel-alapú út
 * (leltarfejController::export()/import()), és a zárás ezt hasonlítja a tényleges készlethez.
 *
 * Minden beolvasás azonnal mentődik – nincs "mentés" gomb, mert a felvételi lista nem egy
 * atomi dokumentum, és egy félbeszakadt számolás nem veszhet el.
 *
 * Csak nyitott (nem zárt) leltárra enged írni; ezt minden módosító végpont külön ellenőrzi,
 * nem csak az oldal megnyitása.
 */
class leltarfelvetelController extends \mkwhelpers\Controller
{

    /**
     * A kérésben megadott leltár, ha az létezik és nyitott.
     *
     * @return Leltarfej|null
     */
    private function getNyitottLeltar()
    {
        /** @var Leltarfej|null $leltar */
        $leltar = $this->getRepo(Leltarfej::class)->find($this->params->getIntRequestParam('leltar'));
        if (!$leltar || $leltar->getZarva()) {
            return null;
        }
        return $leltar;
    }

    public function view()
    {
        $leltar = $this->getNyitottLeltar();
        $view = $this->createView('leltarfelvetel.tpl');
        $view->setVar('pagetitle', t('Leltár felvétel'));
        if (!$leltar) {
            $view->setVar('leltarhiba', t('Nincs ilyen nyitott leltár.'));
            $view->printTemplateResult();
            return;
        }
        $view->setVar('leltarhiba', '');
        $view->setVar('leltarid', $leltar->getId());
        $view->setVar('leltarnev', $leltar->getNev());
        $view->setVar('raktarnev', $leltar->getRaktarnev());
        $view->setVar('nyitasstr', $leltar->getNyitasStr());
        $view->setVar('tetelek', $this->getTetelSorok($leltar));
        $view->printTemplateResult();
    }

    /**
     * Az autocomplete kereső forrása: 4 karaktertől név és cikkszám alapján listáz termékeket.
     */
    public function kereses()
    {
        $term = trim($this->params->getStringRequestParam('term'));
        $ret = [];
        if (mb_strlen($term) >= 4) {
            foreach ($this->getRepo(Termek::class)->getBoltieladasTermekLista($term) as $termek) {
                $nev = $termek->getKiirtnev() ?: $termek->getNev();
                $ret[] = [
                    'id' => $termek->getId(),
                    'label' => trim($termek->getCikkszam() . ' ' . $nev),
                    'value' => $nev,
                ];
            }
        }
        echo json_encode($ret);
    }

    /**
     * Vonalkód vagy cikkszám: előbb változat, aztán termék. Változattalálatnál rögtön felvesszük
     * a mennyiséget, terméktalálatnál változatválasztó jön, ha vannak változatai.
     */
    public function findtermek()
    {
        $leltar = $this->getNyitottLeltar();
        if (!$leltar) {
            echo json_encode(['mode' => 'hiba', 'error' => t('A leltár nem nyitott.')]);
            return;
        }
        $kod = trim($this->params->getStringRequestParam('vonalkod'));
        if ($kod === '') {
            echo json_encode(['mode' => 'none']);
            return;
        }

        $valtozat = $this->getRepo(TermekValtozat::class)->findOneBy(['vonalkod' => $kod]);
        if (!$valtozat) {
            $valtozat = $this->getRepo(TermekValtozat::class)->findOneBy(['cikkszam' => $kod]);
        }
        if ($valtozat) {
            echo json_encode($this->rogzit($leltar, $valtozat->getTermek(), $valtozat));
            return;
        }

        /** @var Termek|null $termek */
        $termek = $this->getRepo(Termek::class)->getBoltieladasTermekPontos($kod);
        if ($termek) {
            echo json_encode($this->termekResponse($leltar, $termek));
            return;
        }

        echo json_encode(['mode' => 'none']);
    }

    /**
     * Egy kiválasztott termék felvétele vagy változatválasztója – a névszerinti választó után.
     */
    public function gettermek()
    {
        $leltar = $this->getNyitottLeltar();
        if (!$leltar) {
            echo json_encode(['mode' => 'hiba', 'error' => t('A leltár nem nyitott.')]);
            return;
        }
        /** @var Termek|null $termek */
        $termek = $this->getRepo(Termek::class)->find($this->params->getIntRequestParam('termekid'));
        if (!$termek) {
            echo json_encode(['mode' => 'none']);
            return;
        }
        echo json_encode($this->termekResponse($leltar, $termek));
    }

    /**
     * A változatválasztó után: a kiválasztott változat felvétele.
     */
    public function addtetel()
    {
        $leltar = $this->getNyitottLeltar();
        if (!$leltar) {
            echo json_encode(['mode' => 'hiba', 'error' => t('A leltár nem nyitott.')]);
            return;
        }
        /** @var Termek|null $termek */
        $termek = $this->getRepo(Termek::class)->find($this->params->getIntRequestParam('termekid'));
        if (!$termek) {
            echo json_encode(['mode' => 'none']);
            return;
        }
        $valtozat = null;
        $valtozatid = $this->params->getIntRequestParam('valtozatid');
        if ($valtozatid) {
            $valtozat = $this->getRepo(TermekValtozat::class)->find($valtozatid);
        }
        echo json_encode($this->rogzit($leltar, $termek, $valtozat));
    }

    /**
     * Egy sor tény mennyiségének felülírása (elgépelt darabszám javítása).
     */
    public function settetel()
    {
        $leltar = $this->getNyitottLeltar();
        if (!$leltar) {
            echo json_encode(['ok' => false, 'error' => t('A leltár nem nyitott.')]);
            return;
        }
        $tetel = $this->getTetel($leltar);
        if (!$tetel) {
            echo json_encode(['ok' => false, 'error' => t('Nincs ilyen tétel a leltárban.')]);
            return;
        }
        $tetel->setTenymennyiseg($this->params->getFloatRequestParam('mennyiseg'));
        $this->getEm()->persist($tetel);
        $this->getEm()->flush();
        echo json_encode(['ok' => true, 'html' => $this->renderTetelRow($tetel), 'tetelid' => $tetel->getId()]);
    }

    /**
     * Egy sor törlése a felvételi listáról.
     */
    public function deltetel()
    {
        $leltar = $this->getNyitottLeltar();
        if (!$leltar) {
            echo json_encode(['ok' => false, 'error' => t('A leltár nem nyitott.')]);
            return;
        }
        $tetel = $this->getTetel($leltar);
        if (!$tetel) {
            echo json_encode(['ok' => false, 'error' => t('Nincs ilyen tétel a leltárban.')]);
            return;
        }
        $tetelid = $tetel->getId();
        $this->getEm()->remove($tetel);
        $this->getEm()->flush();
        echo json_encode(['ok' => true, 'tetelid' => $tetelid]);
    }

    /**
     * A kérésben megadott tétel, de csak akkor, ha tényleg ehhez a leltárhoz tartozik.
     *
     * @return Leltartetel|null
     */
    private function getTetel(Leltarfej $leltar)
    {
        /** @var Leltartetel|null $tetel */
        $tetel = $this->getRepo(Leltartetel::class)->find($this->params->getIntRequestParam('tetelid'));
        if (!$tetel || $tetel->getLeltarfejId() != $leltar->getId()) {
            return null;
        }
        return $tetel;
    }

    /**
     * Terméktalálat feldolgozása: változatos terméknél választó, egyébként rögtön felvétel.
     *
     * @param Termek $termek
     *
     * @return array
     */
    private function termekResponse(Leltarfej $leltar, $termek)
    {
        $valtozatok = $termek->getValtozatok();
        if ($valtozatok && count($valtozatok)) {
            $tc = new termekController();
            $view = $this->createView('leltarfelvetelvaltozat.tpl');
            $view->setVar('termekid', $termek->getId());
            $view->setVar('termekcikkszam', $termek->getCikkszam());
            $view->setVar('termeknev', $termek->getKiirtnev() ?: $termek->getNev());
            $view->setVar('valtozatlist', $tc->getValtozatList($termek->getId(), 0, $leltar->getRaktarId()));
            return ['mode' => 'valtozat', 'html' => $view->getTemplateResult()];
        }
        return $this->rogzit($leltar, $termek, null);
    }

    /**
     * Egy beolvasás könyvelése: leltáranként és termék+változat páronként egy sor, a beolvasás
     * eggyel növeli a tény mennyiségét – aki kétszer szkennel, kettőt számolt. A lista üresen
     * indul, ezért az első beolvasásnál a sort itt hozzuk létre.
     *
     * @param Termek $termek
     * @param TermekValtozat|null $valtozat
     *
     * @return array
     */
    private function rogzit(Leltarfej $leltar, $termek, $valtozat)
    {
        /** @var Leltartetel|null $tetel */
        $tetel = $this->getRepo(Leltartetel::class)->findOneBy([
            'leltarfej' => $leltar,
            'termek' => $termek,
            'termekvaltozat' => $valtozat,
        ]);
        $ujsor = !$tetel;
        if ($ujsor) {
            $tetel = new Leltartetel();
            $tetel->setLeltarfej($leltar);
            $tetel->setTermek($termek);
            $tetel->setTermekvaltozat($valtozat);
            // a gépi mennyiség a leltár raktárának mai készlete – ugyanaz, amit a felvételi ív ad
            $tetel->setGepimennyiseg(($valtozat ?: $termek)->getKeszlet(null, $leltar->getRaktarId()));
            $tetel->setTenymennyiseg(0);
        }
        $tetel->setTenymennyiseg($tetel->getTenymennyiseg() + 1);
        $this->getEm()->persist($tetel);
        $this->getEm()->flush();

        return [
            'mode' => 'tetel',
            'ujsor' => $ujsor,
            'tetelid' => $tetel->getId(),
            'html' => $this->renderTetelRow($tetel),
        ];
    }

    /**
     * A leltár már felvett sorai, legutóbb felvitt elöl.
     *
     * @return string[]
     */
    private function getTetelSorok(Leltarfej $leltar)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('leltarfej', '=', $leltar->getId());
        $ret = [];
        /** @var Leltartetel $tetel */
        foreach ($this->getRepo(Leltartetel::class)->getWithJoins($filter, ['_xx.id' => 'DESC']) as $tetel) {
            $ret[] = $this->renderTetelRow($tetel);
        }
        return $ret;
    }

    /**
     * @param Termek|null $termek
     * @param TermekValtozat|null $valtozat
     */
    private function getTetelNev($termek, $valtozat)
    {
        $nev = $termek ? ($termek->getKiirtnev() ?: $termek->getNev()) : '';
        if ($valtozat && $valtozat->getNev() && trim($valtozat->getNev(), ' -')) {
            $nev .= ' (' . $valtozat->getNev() . ')';
        }
        return $nev;
    }

    private function renderTetelRow(Leltartetel $tetel)
    {
        $termek = $tetel->getTermek();
        $valtozat = $tetel->getTermekvaltozat();
        $nev = $this->getTetelNev($termek, $valtozat);
        $cikkszam = ($valtozat && $valtozat->getCikkszam()) ? $valtozat->getCikkszam() : $termek?->getCikkszam();

        $view = $this->createView('leltarfelveteltetel.tpl');
        $view->setVar('tetelid', $tetel->getId());
        $view->setVar('cikkszam', $cikkszam);
        $view->setVar('nev', $nev);
        $view->setVar('gepimennyiseg', (float)$tetel->getGepimennyiseg());
        $view->setVar('tenymennyiseg', (float)$tetel->getTenymennyiseg());
        return $view->getTemplateResult();
    }

}
