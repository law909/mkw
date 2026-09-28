<?php

namespace Controllers;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Termek;
use Entities\TermekValtozat;

/**
 * Munkalap: a bizonylat gépezetét használó, saját fejadatokkal bővített bizonylattípus.
 * A tételei bizonylattételek, ezért a "Számla" gombbal a szokásos módon képezhető belőlük számla.
 *
 * A jármű kétféleképpen választható: a tételekével azonos termékválasztóval (+ változat), vagy egy
 * bizonylattételen szereplő egyedi azonosítóval. Az azonosító az erősebb: ha ki van töltve, a
 * terméket és a változatot mentéskor abból oldjuk fel, mert az konkrét példányt jelöl.
 * Nem nálunk vett (külső vagy a rendszer előtt eladott) járműnek nincs bizonylattétele: az
 * azonosítója ugyanúgy beírható, a termék és a változat ilyenkor a formról jön, és nem kötelező.
 *
 * A státusza a közös bizonylatstatusz törzsből jön: a munkalap bizonylattípusához kötött és a
 * bizonylattípus nélküli státuszok közül lehet választani.
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

    protected function loadFilters($filter)
    {
        $filter = parent::loadFilters($filter);
        $f = $this->params->getStringRequestParam('munkalapegyediazonositofilter');
        if ($f) {
            $filter->addFilter('munkalapegyediazonosito', 'LIKE', '%' . $f . '%');
        }
        return $filter;
    }

    protected function loadVars($t, $forKarb = false, $oper = false)
    {
        $x = parent::loadVars($t, $forKarb, $oper);
        $x['munkalaptermek'] = $t ? $t->getMunkalaptermekId() : '';
        $x['munkalaptermeknev'] = $t ? $t->getMunkalaptermeknev() : '';
        $x['munkalaptermekvaltozat'] = $t ? $t->getMunkalaptermekvaltozatId() : '';
        $x['munkalaptermekvaltozatnev'] = $t ? $t->getMunkalaptermekvaltozatnev() : '';
        if ($forKarb) {
            $termekCtrl = new termekController();
            $x['munkalapvaltozatlist'] = $termekCtrl->getValtozatList($x['munkalaptermek'], $x['munkalaptermekvaltozat']);
            // autocomplete nélkül a tételekhez hasonlóan a teljes terméklista kerül a legördülőbe
            if (!\mkw\store::isTermekAutocomplete()) {
                $x['munkalaptermeklist'] = $termekCtrl->getSelectList($x['munkalaptermek']);
            }
        }
        $x['munkalapegyediazonosito'] = $t ? $t->getMunkalapegyediazonosito() : '';
        $x['munkalapazonositokulso'] = $forKarb && $x['munkalapegyediazonosito']
            && !$this->getRepo(Bizonylattetel::class)->findByEgyediazonosito($x['munkalapegyediazonosito']);
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

        $azonosito = trim($this->params->getStringRequestParam('munkalapegyediazonosito'));
        $obj->setMunkalapegyediazonosito($azonosito);
        $tetel = $azonosito ? $this->getRepo(Bizonylattetel::class)->findByEgyediazonosito($azonosito) : null;
        if ($tetel) {
            // az azonosító konkrét példányt jelöl, ezért a járművet mindig abból oldjuk fel –
            // a formról jövő termék csak a képernyőn látszó név forrása volt
            $obj->setMunkalaptermek($tetel->getTermek());
            $obj->setMunkalaptermekvaltozat($tetel->getTermekvaltozat());
        } else {
            $termek = $this->getRepo(Termek::class)->find($this->params->getIntRequestParam('munkalaptermek'));
            $obj->setMunkalaptermek($termek);
            $valtozat = $termek
                ? $this->getRepo(TermekValtozat::class)->find($this->params->getIntRequestParam('munkalaptermekvaltozat'))
                : null;
            // változat nélküli termékre váltva a régi termék változata nem maradhat itt
            $obj->setMunkalaptermekvaltozat($valtozat && $valtozat->getTermek()?->getId() == $termek->getId() ? $valtozat : null);
        }
        $obj->setMunkalapkmoraallas($this->params->getIntRequestParam('munkalapkmoraallas'));
        $obj->setMunkalaphibaleiras($this->params->getStringRequestParam('munkalaphibaleiras'));
        $obj->setMunkalapkovetkezoszerviz($this->params->getStringRequestParam('munkalapkovetkezoszerviz'));
        $obj->setMunkalapkovetkezoszervizkm($this->params->getIntRequestParam('munkalapkovetkezoszervizkm'));

        return $obj;
    }

    protected function validate($obj, $parancs)
    {
        $hibak = parent::validate($obj, $parancs);
        if (!trim((string)$obj->getMunkalapegyediazonosito()) && !$obj->getMunkalaptermek()) {
            $mezo = \mkw\store::isTermekAutocomplete() ? 'munkalaptermeknev' : 'munkalaptermek';
            $hibak[$mezo] = t('Válassza ki a járművet, vagy adja meg az egyedi azonosítóját.');
        }
        return $hibak;
    }

    protected function isReadonly($record)
    {
        return parent::isReadonly($record) || ((bool)$record && $record->isKiszamlazva());
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

    /**
     * A munkalap egyedi azonosító autocomplete-je: a bizonylattételeken szereplő azonosítók.
     * Kiválasztott járművel csak az adott termék azonosítóit kínálja.
     */
    public function egyediAzonositoLista()
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            $this->getRepo(Bizonylattetel::class)->searchEgyediazonosito(
                trim($this->params->getStringRequestParam('term')),
                $this->params->getIntRequestParam('termekid')
            )
        );
    }

    /**
     * Egy egyedi azonosító teljes járműadata a formhoz: termék, változat (a változatlistával
     * együtt), és a példány gazdája arról a bizonylatról, amelyiken az azonosító szerepel.
     * Bizonylattétel nélküli (külső) járműnél a legutóbbi munkalapja az adatforrás.
     */
    public function jarmuAdat()
    {
        header('Content-Type: application/json; charset=utf-8');
        $azonosito = trim($this->params->getStringRequestParam('munkalapegyediazonosito'));
        $rep = $this->getRepo(Bizonylattetel::class);
        $adat = $azonosito ? $rep->egyediazonositoAdat($rep->findByEgyediazonosito($azonosito)) : null;
        if ($adat) {
            $partner = $rep->findOwnerBizonylattetel($azonosito)?->getBizonylatfej()?->getPartner();
            $adat['kulso'] = false;
        } else {
            $elozo = $azonosito ? $this->getRepo()->findLastMunkalapByEgyediazonosito($azonosito) : null;
            if (!$elozo) {
                echo json_encode([
                    'ok' => false,
                    'kulso' => true,
                    'error' => t('Nincs a rendszerben, külső járműként menthető – a járművet válassza ki kézzel.'),
                ]);
                return;
            }
            $termek = $elozo->getMunkalaptermek();
            $valtozat = $elozo->getMunkalaptermekvaltozat();
            $adat = [
                'azonosito' => $azonosito,
                'termekid' => $termek ? $termek->getId() : '',
                'termeknev' => $termek ? $termek->getNev() : '',
                'valtozatid' => $valtozat ? $valtozat->getId() : '',
                'valtozatnev' => $valtozat ? $valtozat->getNev() : '',
                'kulso' => true,
            ];
            $partner = $elozo->getPartner();
        }
        $termekCtrl = new termekController();
        $adat['ok'] = true;
        $adat['valtozatlista'] = $adat['termekid'] ? $termekCtrl->getValtozatList($adat['termekid'], $adat['valtozatid']) : [];
        $adat['partnerid'] = $partner ? $partner->getId() : '';
        $adat['partnernev'] = $partner ? $partner->getNev() : '';
        echo json_encode($adat);
    }

    /** A választott jármű változatai a fej termékválasztója után. */
    public function valtozatLista()
    {
        header('Content-Type: application/json; charset=utf-8');
        $termekCtrl = new termekController();
        echo json_encode($termekCtrl->getValtozatList(
            $this->params->getIntRequestParam('termekid'),
            $this->params->getIntRequestParam('valtozatid')
        ));
    }

}
