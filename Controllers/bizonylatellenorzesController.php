<?php

namespace Controllers;

use Entities\Bizonylatfej;
use Entities\Termek;
use Entities\TermekValtozat;

/**
 * Tételek ellenőrzése: a mentett bizonylat tételeit vonalkóddal (vagy kereséssel) végigpipálják,
 * és a becsipogott mennyiségeket a bizonylat tételeivel vetjük össze. Az összevetés a böngészőben
 * történik (bizonylatellenorzes.js), a szerver a tételeket és a termékazonosítást adja.
 */
class bizonylatellenorzesController extends \mkwhelpers\Controller
{

    public function view()
    {
        /** @var Bizonylatfej|null $bizonylat */
        $bizonylat = $this->getRepo(Bizonylatfej::class)->find($this->params->getStringRequestParam('id'));
        if (!$bizonylat) {
            http_response_code(404);
            echo t('A bizonylat nem található.');
            return;
        }

        $tetelek = [];
        foreach ($bizonylat->getBizonylattetelek() as $tetel) {
            /** @var \Entities\Bizonylattetel $tetel */
            $termek = $tetel->getTermek();
            $valtozat = $tetel->getTermekvaltozat();
            $tetelek[] = [
                'id' => $tetel->getId(),
                'termekid' => $termek ? $termek->getId() : 0,
                'valtozatid' => $valtozat ? $valtozat->getId() : 0,
                'cikkszam' => $tetel->getCikkszam(),
                'nev' => $tetel->getTermeknev(),
                'valtozatnev' => $valtozat ? trim($valtozat->getNev(), ' -') : '',
                'vonalkod' => ($valtozat && $valtozat->getVonalkod()) ? $valtozat->getVonalkod() : ($termek ? $termek->getVonalkod() : ''),
                'mennyiseg' => (float)$tetel->getMennyiseg(),
            ];
        }

        $view = $this->createView('bizonylatellenorzes.tpl');
        $view->setVar('pagetitle', t('Tételek ellenőrzése'));
        $view->setVar('egyed', [
            'id' => $bizonylat->getId(),
            'tipusnev' => $bizonylat->getBizonylattipus() ? $bizonylat->getBizonylattipus()->getNev() : $bizonylat->getBizonylatnev(),
            'partnernev' => $bizonylat->getPartnernev(),
            'keltstr' => $bizonylat->getKeltStr(),
            'listaurl' => $bizonylat->getListaUrl(),
        ]);
        $view->setVar('tetelek', $tetelek);
        $view->printTemplateResult(false);
    }

    /**
     * Vonalkód vagy cikkszám: előbb változat, aztán termék. Változattalálat esetén kész az azonosítás,
     * terméktalálatnál a változatai közül kell választani (ha vannak).
     */
    public function findtermek()
    {
        $kod = trim($this->params->getStringRequestParam('vonalkod'));
        if ($kod === '') {
            echo json_encode(['mode' => 'none']);
            return;
        }

        $valtozat = $this->getRepo(TermekValtozat::class)->findOneBy(['vonalkod' => $kod]);
        if ($valtozat) {
            echo json_encode($this->tetelResponse($valtozat->getTermek(), $valtozat));
            return;
        }
        // a változatok gyakran a termék cikkszámát viselik: több találat = termék szintű kód, választani kell
        $valtozatok = $this->getRepo(TermekValtozat::class)->findBy(['cikkszam' => $kod]);
        if (count($valtozatok) === 1) {
            echo json_encode($this->tetelResponse($valtozatok[0]->getTermek(), $valtozatok[0]));
            return;
        }
        if (count($valtozatok) > 1) {
            echo json_encode($this->termekResponse($valtozatok[0]->getTermek()));
            return;
        }

        /** @var Termek|null $termek */
        $termek = $this->getRepo(Termek::class)->getBoltieladasTermekPontos($kod);
        echo json_encode($termek ? $this->termekResponse($termek) : ['mode' => 'none']);
    }

    /**
     * Az autocomplete kereső forrása: név és cikkszám alapján.
     */
    public function kereses()
    {
        $term = trim($this->params->getStringRequestParam('term'));
        $ret = [];
        if (mb_strlen($term) >= 3) {
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

    public function gettermek()
    {
        /** @var Termek|null $termek */
        $termek = $this->getRepo(Termek::class)->find($this->params->getIntRequestParam('termekid'));
        echo json_encode($termek ? $this->termekResponse($termek) : ['mode' => 'none']);
    }

    private function termekResponse(Termek $termek)
    {
        $valtozatok = [];
        foreach ($termek->getValtozatok() as $valtozat) {
            /** @var TermekValtozat $valtozat */
            $valtozatok[] = [
                'id' => $valtozat->getId(),
                'nev' => trim($valtozat->getNev(), ' -'),
                'vonalkod' => $valtozat->getVonalkod(),
            ];
        }
        if ($valtozatok) {
            return [
                'mode' => 'valtozat',
                'termekid' => $termek->getId(),
                'nev' => trim($termek->getCikkszam() . ' ' . ($termek->getKiirtnev() ?: $termek->getNev())),
                'valtozatok' => $valtozatok,
            ];
        }
        return $this->tetelResponse($termek, null);
    }

    private function tetelResponse(Termek $termek, $valtozat)
    {
        $nev = $termek->getKiirtnev() ?: $termek->getNev();
        if ($valtozat && trim($valtozat->getNev(), ' -')) {
            $nev .= ' (' . trim($valtozat->getNev(), ' -') . ')';
        }
        return [
            'mode' => 'tetel',
            'termekid' => $termek->getId(),
            'valtozatid' => $valtozat ? $valtozat->getId() : 0,
            'cikkszam' => ($valtozat && $valtozat->getCikkszam()) ? $valtozat->getCikkszam() : $termek->getCikkszam(),
            'nev' => $nev,
        ];
    }
}
