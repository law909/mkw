<?php

namespace Controllers;

use Entities\Afa;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Partner;
use Entities\Raktar;
use Entities\Termek;
use Entities\TermekValtozat;
use Entities\Valutanem;

/**
 * A bizonylat karb vonalkódos (POS) tételfelvitelének kiszolgálója.
 *
 * Ugyanaz a keresési logika, mint a főoldali bolti eladásé (\Controllers\boltieladasController),
 * de ott a partner a bolti vevő és a raktár a globális alapraktár. Itt mindkettő – és a valutanem –
 * a bizonylat fejéből érkezik, ezért az árazás és a készletjelzés kérésenként más lehet.
 *
 * A kiadott tételsor eleve a klasszikus rögzítő mezőneveit viseli (tetelid[], tetel*_<uid>),
 * így a mentés a meglévő gyorsrögzítő ágon megy át – lásd bizonylatfejController::setFields().
 */
class bizonylatposController extends \mkwhelpers\Controller
{

    /**
     * Vonalkód vagy cikkszám: előbb változat, aztán termék. Változattalálat esetén rögtön
     * tételsor, terméktalálatnál változatválasztó (ha vannak változatai).
     */
    public function findtermek()
    {
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
            echo json_encode(['mode' => 'tetel', 'html' => $this->renderTetelRow($valtozat->getTermek(), $valtozat)]);
            return;
        }

        /** @var Termek|null $termek */
        $termek = $this->getRepo(Termek::class)->getBoltieladasTermekPontos($kod);
        if ($termek) {
            echo json_encode($this->termekResponse($termek));
            return;
        }

        echo json_encode(['mode' => 'none']);
    }

    /**
     * Az autocomplete kereső forrása: 3 karaktertől név és cikkszám alapján listáz termékeket.
     */
    public function kereses()
    {
        $term = trim($this->params->getStringRequestParam('term'));
        $ret = [];
        if (mb_strlen($term) >= 3) {
            $termekek = $this->getRepo(Termek::class)->getBoltieladasTermekLista($term);
            $labels = $this->getRepo(TermekValtozat::class)->getAutocompleteLabels(
                termekController::toAutocompleteLabelInput($termekek),
                $term
            );
            foreach ($termekek as $termek) {
                $nev = $termek->getKiirtnev() ?: $termek->getNev();
                $ret[] = [
                    'id' => $termek->getId(),
                    'label' => $labels[$termek->getId()],
                    'value' => $nev,
                ];
            }
        }
        echo json_encode($ret);
    }

    /**
     * Egy kiválasztott termék tételsora vagy változatválasztója – a névszerinti választó után.
     */
    public function gettermek()
    {
        /** @var Termek|null $termek */
        $termek = $this->getRepo(Termek::class)->find($this->params->getIntRequestParam('termekid'));
        if (!$termek) {
            echo json_encode(['mode' => 'none']);
            return;
        }
        echo json_encode($this->termekResponse($termek));
    }

    /**
     * Egy adott termék + változat tételsora – a változatválasztó után.
     */
    public function gettetel()
    {
        /** @var Termek|null $termek */
        $termek = $this->getRepo(Termek::class)->find($this->params->getIntRequestParam('termekid'));
        if (!$termek) {
            echo json_encode(['ok' => false]);
            return;
        }
        $valtozat = null;
        $valtozatid = $this->params->getIntRequestParam('valtozatid');
        if ($valtozatid) {
            $valtozat = $this->getRepo(TermekValtozat::class)->find($valtozatid);
        }
        echo json_encode(['ok' => true, 'html' => $this->renderTetelRow($termek, $valtozat)]);
    }

    /**
     * @param Termek $termek
     *
     * @return array
     */
    private function termekResponse($termek)
    {
        $valtozatok = $termek->getValtozatok();
        if ($valtozatok && count($valtozatok)) {
            $tc = new termekController();
            $raktar = $this->getRaktar();
            $view = $this->createView('bizonylattetelposvaltozat.tpl');
            $view->setVar('termekid', $termek->getId());
            $view->setVar('termekcikkszam', $termek->getCikkszam());
            $view->setVar('termeknev', $termek->getKiirtnev() ?: $termek->getNev());
            $view->setVar('valtozatlist', $tc->getValtozatList($termek->getId(), 0, $raktar?->getId()));
            return ['mode' => 'valtozat', 'html' => $view->getTemplateResult()];
        }
        return ['mode' => 'tetel', 'html' => $this->renderTetelRow($termek, null)];
    }

    /**
     * Egy POS tételsor HTML-je. Az árak a fejben beállított partnerre és valutanemre,
     * a készletjelzés a fejben beállított raktárra szól.
     *
     * @param Termek $termek
     * @param TermekValtozat|null $valtozat
     *
     * @return string
     */
    private function renderTetelRow($termek, $valtozat)
    {
        $partner = $this->getPartner();
        $valutanem = $this->getValutanem();

        /** @var Afa|null $afa */
        $afa = $termek->getAfa();

        $enetto = $termek->getKedvezmenynelkuliNettoAr($valtozat, $partner, $valutanem);
        $netto = $termek->getNettoAr($valtozat, $partner, $valutanem);
        $brutto = $afa ? $afa->calcBrutto($netto) : $netto;
        $ebrutto = $afa ? $afa->calcBrutto($enetto) : $enetto;

        $nev = $termek->getKiirtnev() ?: $termek->getNev();
        if ($valtozat && $valtozat->getNev() && trim($valtozat->getNev(), ' -')) {
            $nev .= ' (' . $valtozat->getNev() . ')';
        }

        /** @var Bizonylattipus|null $biztipus */
        $biztipus = $this->getRepo(Bizonylattipus::class)->find($this->params->getStringRequestParam('type'));
        return $this->renderRow($biztipus, $termek, $valtozat, $this->getRaktar(), [
            'tetelid' => \mkw\store::createUID(),
            'teteloper' => 'add',
            'afaid' => $afa ? $afa->getId() : 0,
            'afakulcs' => $afa ? $afa->getErtek() : 0,
            'nev' => $nev,
            'cikkszam' => ($valtozat && $valtozat->getCikkszam()) ? $valtozat->getCikkszam() : $termek->getCikkszam(),
            'mennyiseg' => 1,
            'enettoegysar' => $enetto,
            'ebruttoegysar' => $ebrutto,
            'nettoegysar' => $netto,
            'bruttoegysar' => $brutto,
            'kedvezmeny' => $termek->getKedvezmeny($partner),
        ]);
    }

    /**
     * A meglévő bizonylat vonalkódos szerkesztésekor a már rögzített tételek sorai, a tétel saját
     * áraival – a mentés a gyorsrögzítő edit ágán írja vissza őket.
     *
     * @return string[]
     */
    public function renderExistingTetelRows(Bizonylatfej $bizonylat)
    {
        $rows = [];
        /** @var Bizonylattetel $tetel */
        foreach ($bizonylat->getRendezettTetelek() as $tetel) {
            $termek = $tetel->getTermek();
            if (!$termek) {
                continue;
            }
            $rows[] = $this->renderRow($bizonylat->getBizonylattipus(), $termek, $tetel->getTermekvaltozat(), $bizonylat->getRaktar(), [
                'tetelid' => $tetel->getId(),
                'teteloper' => 'edit',
                'afaid' => $tetel->getAfaId() ?: 0,
                'afakulcs' => $tetel->getAfakulcs(),
                'nev' => $tetel->getFullTermeknev(),
                'cikkszam' => $tetel->getDisplayCikkszam(),
                'mennyiseg' => $tetel->getMennyiseg(),
                'enettoegysar' => $tetel->getEnettoegysar(),
                'ebruttoegysar' => $tetel->getEbruttoegysar(),
                'nettoegysar' => $tetel->getNettoegysar(),
                'bruttoegysar' => $tetel->getBruttoegysar(),
                'kedvezmeny' => $tetel->getKedvezmeny(),
            ]);
        }
        return $rows;
    }

    private function renderRow(?Bizonylattipus $biztipus, Termek $termek, ?TermekValtozat $valtozat, ?Raktar $raktar, array $vars)
    {
        $keszlet = $termek->getMozgat() ? ($valtozat ?: $termek)->getAvailableStock(datum: null, raktarid: $raktar?->getId()) : null;

        $view = $this->createView('bizonylattetelposkarb.tpl');
        foreach ($vars as $key => $value) {
            $view->setVar($key, $value);
        }
        foreach (['enettoegysar', 'ebruttoegysar', 'nettoegysar', 'bruttoegysar', 'kedvezmeny'] as $key) {
            $view->setVar($key, number_format((float)$vars[$key], 2, '.', ''));
        }
        $view->setVar('termekid', $termek->getId());
        $view->setVar('valtozatid', $valtozat ? $valtozat->getId() : 0);
        $view->setVar('mozgat', $keszlet !== null);
        $view->setVar('raktaron', ($keszlet > 0));
        $view->setVar('keszlet', (float)$keszlet);
        $biztipus?->setTemplateVars($view);
        return $view->getTemplateResult();
    }

    private function getPartner()
    {
        return $this->getRepo(Partner::class)->find($this->params->getIntRequestParam('partner'));
    }

    private function getValutanem()
    {
        $valutanem = $this->getRepo(Valutanem::class)->find($this->params->getIntRequestParam('valutanem'));
        if (!$valutanem) {
            $valutanem = $this->getRepo(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
        }
        return $valutanem;
    }

    private function getRaktar()
    {
        return $this->getRepo(Raktar::class)->find($this->params->getIntRequestParam('raktar'));
    }

}
