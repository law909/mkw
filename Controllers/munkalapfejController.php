<?php

namespace Controllers;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Munkalapstatusz;

/**
 * Munkalap: a bizonylat gépezetét használó, saját fejadatokkal bővített bizonylattípus.
 * A tételei bizonylattételek, ezért a "Számla" gombbal a szokásos módon képezhető belőlük számla.
 *
 * Zárási szabályok:
 *  - felvett munkalap nem törölhető, csak rontható (a bizonylatlistán nincs törlés gomb),
 *  - kiszámlázott munkalap nem rontható és nem módosítható ({@see Bizonylatfej::isKiszamlazva()}),
 *  - rontott munkalapból nem képezhető számla (a bizonylatfejController::getkarb() nem enged
 *    rontott elődből bizonylatot képezni).
 */
class munkalapfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('munkalap');
        $this->setPageTitle('Munkalap');
        $this->setPluralPageTitle('Munkalapok');
    }

    public function setVars($view)
    {
        parent::setVars($view);
        $msc = new munkalapstatuszController();
        $view->setVar('munkalapstatuszszurolist', $msc->getSelectList());
    }

    protected function loadFilters($filter)
    {
        $filter = parent::loadFilters($filter);
        $f = $this->params->getIntRequestParam('munkalapstatuszfilter');
        if ($f) {
            $filter->addFilter('munkalapstatusz', '=', $f);
        }
        $f = $this->params->getStringRequestParam('munkalapegyediazonositofilter');
        if ($f) {
            $filter->addFilter('munkalapegyediazonosito', 'LIKE', '%' . $f . '%');
        }
        return $filter;
    }

    protected function loadVars($t, $forKarb = false, $oper = false)
    {
        $x = parent::loadVars($t, $forKarb, $oper);
        $msc = new munkalapstatuszController();
        $x['munkalapstatusz'] = $t ? $t->getMunkalapstatuszId() : '';
        $x['munkalapstatusznev'] = $t ? $t->getMunkalapstatusznev() : '';
        $x['munkalapstatuszlist'] = $msc->getSelectList($x['munkalapstatusz']);
        $x['munkalaptermek'] = $t ? $t->getMunkalaptermekId() : '';
        $x['munkalaptermeknev'] = $t ? $t->getMunkalaptermeknev() : '';
        $x['munkalapegyediazonosito'] = $t ? $t->getMunkalapegyediazonosito() : '';
        $x['munkalapkmoraallas'] = $t ? $t->getMunkalapkmoraallas() : '';
        $x['munkalaphibaleiras'] = $t ? $t->getMunkalaphibaleiras() : '';
        $x['munkalapkovetkezoszervizstr'] = $t ? $t->getMunkalapkovetkezoszervizStr() : '';
        $x['munkalapkovetkezoszervizkm'] = $t ? $t->getMunkalapkovetkezoszervizkm() : '';
        // képzett (inherit) vagy stornó bizonylat még nem létezik, azt nem zárja semmi
        $x['munkalapkiszamlazva'] = ($t && $t->getId() && ($oper !== $this->inheritOperation))
            ? $t->isKiszamlazva()
            : false;
        return $x;
    }

    protected function setFields(\Entities\Bizonylatfej $obj, $parancs)
    {
        $obj = parent::setFields($obj, $parancs);

        $obj->setMunkalapstatusz($this->params->getIntRequestParam('munkalapstatusz'));
        $azonosito = trim($this->params->getStringRequestParam('munkalapegyediazonosito'));
        $obj->setMunkalapegyediazonosito($azonosito);
        // a terméket mindig az azonosítóból oldjuk fel: a formról jövő termékazonosító csak
        // a képernyőn látszó név forrása volt
        $obj->setMunkalaptermek(
            $azonosito ? $this->getRepo(Bizonylattetel::class)->findTermekByEgyediazonosito($azonosito) : null
        );
        $obj->setMunkalapkmoraallas($this->params->getIntRequestParam('munkalapkmoraallas'));
        $obj->setMunkalaphibaleiras($this->params->getStringRequestParam('munkalaphibaleiras'));
        $obj->setMunkalapkovetkezoszerviz($this->params->getStringRequestParam('munkalapkovetkezoszerviz'));
        $obj->setMunkalapkovetkezoszervizkm($this->params->getIntRequestParam('munkalapkovetkezoszervizkm'));

        return $obj;
    }

    protected function validate($obj, $parancs)
    {
        $hibak = [];
        $azonosito = trim((string)$obj->getMunkalapegyediazonosito());
        if (!$azonosito) {
            $hibak['munkalapegyediazonosito'] = t('Adja meg a gép egyedi azonosítóját.');
        } elseif (!$obj->getMunkalaptermek()) {
            $hibak['munkalapegyediazonosito'] = sprintf(
                t('Nincs "%s" egyedi azonosítójú bizonylattétel.'),
                $azonosito
            );
        }
        return $hibak;
    }

    protected function isReadonly($record)
    {
        return parent::isReadonly($record) || ((bool)$record && $record->isKiszamlazva());
    }

    protected function afterSave($o, $parancs = null)
    {
        parent::afterSave($o, $parancs);
        if ($this->params->getBoolRequestParam('munkalapstatuszertesito')) {
            $this->sendStatuszEmail($o);
        }
    }

    public function ront()
    {
        /** @var Bizonylatfej|null $bf */
        $bf = $this->getRepo()->find($this->params->getStringRequestParam('id'));
        if ($bf && $bf->isKiszamlazva()) {
            $this->jsonError(t('A kiszámlázott munkalap nem rontható.'), 409);
            return;
        }
        parent::ront();
    }

    /** Státuszváltás a munkalap listáról – a bizonylatfejController::setStatusz() párja. */
    public function setMunkalapstatusz()
    {
        /** @var Bizonylatfej|null $bf */
        $bf = $this->getRepo()->find($this->params->getStringRequestParam('id'));
        if (!$bf) {
            return;
        }
        if ($bf->isKiszamlazva()) {
            $this->jsonError(t('A kiszámlázott munkalap nem módosítható.'), 409);
            return;
        }
        $statusz = $this->getRepo(Munkalapstatusz::class)->find($this->params->getIntRequestParam('statusz'));
        $bf->setKellszallitasikoltsegetszamolni(false);
        $bf->setSimpleedit(true);
        $bf->setMunkalapstatusz($statusz);
        $this->getEm()->persist($bf);
        $this->getEm()->flush();
        if ($statusz && $this->params->getBoolRequestParam('munkalapstatuszertesito')) {
            $this->sendStatuszEmail($bf);
        }
    }

    /** A munkalap egyedi azonosító autocomplete-je: a bizonylattételeken szereplő azonosítók. */
    public function egyediAzonositoLista()
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            $this->getRepo(Bizonylattetel::class)
                ->searchEgyediazonosito(trim($this->params->getStringRequestParam('term')))
        );
    }

    private function sendStatuszEmail(Bizonylatfej $bf)
    {
        $statusz = $bf->getMunkalapstatusz();
        if ($statusz) {
            $bf->sendStatuszEmail($statusz->getEmailtemplate());
        }
    }

}
