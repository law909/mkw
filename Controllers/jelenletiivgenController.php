<?php

namespace Controllers;

use Entities\Dolgozo;
use Entities\Dolgozoszabadsag;
use Entities\Unnepnap;
use mkwhelpers\FilterDescriptor;

/**
 * Aláírásra kész jelenléti ív a dolgozó rögzített munkarendjéből: azok a napok kerülnek rá,
 * amikor dolgoznia kell (munkanapok, ünnepnap nélkül), a távollétek megjelölve.
 *
 * Nem keverendő a {@see jelenletiivController} képernyőjével: az a tényleges be- és kilépéseket
 * tartja nyilván, ez a munkarendből képzett ívet adja.
 */
class jelenletiivgenController extends \mkwhelpers\Controller
{

    public function view()
    {
        $view = $this->createView('jelenletiivgen.tpl');

        $view->setVar('pagetitle', t('Jelenléti ív generálás'));
        $view->setVar('toldatum', date(\mkw\store::$DateFormat, strtotime('first day of this month')));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat, strtotime('last day of this month')));
        $d = new dolgozoController();
        $view->setVar('dolgozolist', $d->getSelectList());

        $view->printTemplateResult();
    }

    public function createLista()
    {
        $adat = $this->getData();
        $report = $this->createView('rep_jelenletiiv.tpl');
        $report->setVar('ivek', $adat['ivek']);
        $report->setVar('tolstr', $adat['tolstr']);
        $report->setVar('igstr', $adat['igstr']);
        $report->printTemplateResult();
    }

    /**
     * @return array ['tol','ig','tolstr','igstr','ivek'] – ívenként a dolgozó és a napjai
     */
    protected function getData()
    {
        $tol = $this->datumParam('tol', 'first day of this month');
        $ig = $this->datumParam('ig', 'last day of this month');
        if ($ig < $tol) {
            $ig = clone $tol;
        }

        $unnepnapok = $this->getUnnepnapok($tol, $ig);
        $ivek = [];
        /** @var Dolgozo $dolgozo */
        foreach ($this->getDolgozok() as $dolgozo) {
            $ivek[] = $this->createIv($dolgozo, $tol, $ig, $unnepnapok);
        }

        return [
            'tol' => $tol,
            'ig' => $ig,
            'tolstr' => $tol->format(\mkw\store::$DateFormat),
            'igstr' => $ig->format(\mkw\store::$DateFormat),
            'ivek' => $ivek,
        ];
    }

    private function createIv(Dolgozo $dolgozo, \DateTime $tol, \DateTime $ig, array $unnepnapok)
    {
        $szabadsagok = $this->getRepo(Dolgozoszabadsag::class)->getByDolgozoAndIdoszak($dolgozo, $tol, $ig);
        $napnevek = Dolgozo::getNapok();
        $kezdes = $dolgozo->getMunkakezdesStr();
        $vege = $dolgozo->getMunkavegeStr();

        $napok = [];
        $ledolgozott = 0;
        $tavollet = 0;
        $nap = clone $tol;
        while ($nap <= $ig) {
            if ($dolgozo->isMunkanap($nap) && !isset($unnepnapok[$nap->format(\mkw\store::$SQLDateFormat)])) {
                $tavolletnev = $this->getTavolletNev($szabadsagok, $nap);
                $napok[] = [
                    'datum' => $nap->format(\mkw\store::$DateFormat),
                    'napnev' => $napnevek[(int)$nap->format('N')],
                    'kezdes' => $tavolletnev ? '' : $kezdes,
                    'vege' => $tavolletnev ? '' : $vege,
                    'tavollet' => $tavolletnev,
                ];
                if ($tavolletnev) {
                    $tavollet++;
                } else {
                    $ledolgozott++;
                }
            }
            $nap->modify('+1 day');
        }

        return [
            'dolgozonev' => $dolgozo->getNev(),
            'munkakornev' => $dolgozo->getMunkakorNev(),
            'munkaido' => ($kezdes && $vege) ? $kezdes . ' - ' . $vege : '',
            'napok' => $napok,
            'ledolgozott' => $ledolgozott,
            'tavollet' => $tavollet,
        ];
    }

    /**
     * @param \Entities\Dolgozoszabadsag[] $szabadsagok
     *
     * @return string a távollét típusa, vagy üres string, ha aznap dolgozik
     */
    private function getTavolletNev(array $szabadsagok, \DateTime $nap)
    {
        foreach ($szabadsagok as $szabadsag) {
            if ($szabadsag->tartalmazzaNapot($nap)) {
                return t($szabadsag->getTipusNev());
            }
        }
        return '';
    }

    /**
     * @return \Entities\Dolgozo[]
     */
    private function getDolgozok()
    {
        $dolgozoid = $this->params->getIntRequestParam('dolgozo');
        if ($dolgozoid) {
            $dolgozo = $this->getRepo(Dolgozo::class)->find($dolgozoid);
            return $dolgozo ? [$dolgozo] : [];
        }
        $filter = new FilterDescriptor();
        $filter->addFilter('inaktiv', '=', false);
        return $this->getRepo(Dolgozo::class)->getAll($filter, ['nev' => 'ASC']);
    }

    /** @return array kulcs = Y-m-d, hogy a napi ellenőrzés ne járjon lekérdezéssel */
    private function getUnnepnapok(\DateTime $tol, \DateTime $ig)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('datum', '>=', $tol->format(\mkw\store::$SQLDateFormat));
        $filter->addFilter('datum', '<=', $ig->format(\mkw\store::$SQLDateFormat));
        $result = [];
        /** @var Unnepnap $unnepnap */
        foreach ($this->getRepo(Unnepnap::class)->getAll($filter) as $unnepnap) {
            $result[$unnepnap->getDatumString()] = true;
        }
        return $result;
    }

    private function datumParam($nev, $alapertelmezett)
    {
        $ertek = $this->params->getStringRequestParam($nev);
        $datum = $ertek ? new \DateTime(\mkw\store::convDate($ertek)) : new \DateTime($alapertelmezett);
        return $datum->setTime(0, 0);
    }

}
