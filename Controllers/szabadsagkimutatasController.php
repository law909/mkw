<?php

namespace Controllers;

use Entities\Dolgozo;
use Entities\Dolgozoszabadsag;
use mkwhelpers\FilterDescriptor;
use Traits\Munkanap;

/**
 * Szabadság kimutatás: ki mikor volt szabadságon, hány munkanapot vett ki az időszakban, és
 * mennyi maradt az éves keretéből.
 *
 * A nap ugyanúgy számít, mint a jelenléti íven ({@see jelenletiivgenController}): csak a dolgozó
 * munkanapja, ünnepnap nélkül. Az éves keretet ({@see Dolgozo::getEvesmaxszabi()}) csak a
 * „szabadság” típus fogyasztja, a betegszabadság és a fizetetlen nem.
 */
class szabadsagkimutatasController extends \mkwhelpers\Controller
{
    use Munkanap;

    public function view()
    {
        $view = $this->createView('szabadsagkimutatas.tpl');

        $view->setVar('pagetitle', t('Szabadság kimutatás'));
        $view->setVar('toldatum', date(\mkw\store::$DateFormat, strtotime('first day of January this year')));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat, strtotime('last day of December this year')));
        $d = new dolgozoController();
        $view->setVar('dolgozolist', $d->getSelectList());

        $view->printTemplateResult();
    }

    public function createLista()
    {
        $adat = $this->getData();
        $report = $this->createView('rep_szabadsagkimutatas.tpl');
        $report->setVar('sorok', $adat['sorok']);
        $report->setVar('tolstr', $adat['tolstr']);
        $report->setVar('igstr', $adat['igstr']);
        $report->setVar('ev', $adat['ev']);
        $report->printTemplateResult();
    }

    /**
     * @return array ['tolstr','igstr','ev','sorok'] – dolgozónként a távollétei és az egyenlege
     */
    protected function getData()
    {
        $tol = $this->datumParam('tol', 'first day of January this year');
        $ig = $this->datumParam('ig', 'last day of December this year');
        if ($ig < $tol) {
            $ig = clone $tol;
        }
        // az éves keret az időszak végének évére vonatkozik
        $ev = (int)$ig->format('Y');
        $evtol = (new \DateTime($ev . '-01-01'))->setTime(0, 0);
        $evig = (new \DateTime($ev . '-12-31'))->setTime(0, 0);

        $unnepnapok = $this->getUnnepnapok(min($tol, $evtol), max($ig, $evig));

        $sorok = [];
        /** @var Dolgozo $dolgozo */
        foreach ($this->getDolgozok() as $dolgozo) {
            $sorok[] = $this->createSor($dolgozo, $tol, $ig, $evtol, $evig, $unnepnapok);
        }

        return [
            'tolstr' => $tol->format(\mkw\store::$DateFormat),
            'igstr' => $ig->format(\mkw\store::$DateFormat),
            'ev' => $ev,
            'sorok' => $sorok,
        ];
    }

    private function createSor(Dolgozo $dolgozo, \DateTime $tol, \DateTime $ig, \DateTime $evtol, \DateTime $evig, array $unnepnapok)
    {
        $tavolletek = [];
        $idoszaki = array_fill_keys(array_keys(Dolgozoszabadsag::getTipusok()), 0);
        /** @var Dolgozoszabadsag $szabadsag */
        foreach ($this->getRepo(Dolgozoszabadsag::class)->getByDolgozoAndIdoszak($dolgozo, $tol, $ig) as $szabadsag) {
            $sorTol = max($szabadsag->getDatumtol(), $tol);
            $sorIg = min($szabadsag->getDatumig(), $ig);
            $napok = $this->countMunkanapok($dolgozo, $sorTol, $sorIg, $unnepnapok);
            $idoszaki[$szabadsag->getTipus()] = ($idoszaki[$szabadsag->getTipus()] ?? 0) + $napok;
            $tavolletek[] = [
                'datumtol' => $sorTol->format(\mkw\store::$DateFormat),
                'datumig' => $sorIg->format(\mkw\store::$DateFormat),
                'tipusnev' => t($szabadsag->getTipusNev()),
                'napok' => $napok,
                'megjegyzes' => $szabadsag->getMegjegyzes(),
            ];
        }

        $evesmax = (int)$dolgozo->getEvesmaxszabi();
        $evbenkivett = $this->countSzabadsag($dolgozo, $evtol, $evig, $unnepnapok);

        return [
            'dolgozonev' => $dolgozo->getNev(),
            'munkakornev' => $dolgozo->getMunkakorNev(),
            'tavolletek' => $tavolletek,
            'idoszaki' => $idoszaki,
            'idoszakiosszes' => array_sum($idoszaki),
            'evesmax' => $evesmax,
            'evbenkivett' => $evbenkivett,
            'marad' => $evesmax - $evbenkivett,
        ];
    }

    /** Az éves keretet fogyasztó (szabadság típusú) munkanapok száma az évben. */
    private function countSzabadsag(Dolgozo $dolgozo, \DateTime $evtol, \DateTime $evig, array $unnepnapok)
    {
        $db = 0;
        /** @var Dolgozoszabadsag $szabadsag */
        foreach ($this->getRepo(Dolgozoszabadsag::class)->getByDolgozoAndIdoszak($dolgozo, $evtol, $evig) as $szabadsag) {
            if ($szabadsag->getTipus() !== Dolgozoszabadsag::TIPUS_SZABADSAG) {
                continue;
            }
            $db += $this->countMunkanapok(
                $dolgozo,
                max($szabadsag->getDatumtol(), $evtol),
                min($szabadsag->getDatumig(), $evig),
                $unnepnapok
            );
        }
        return $db;
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

    private function datumParam($nev, $alapertelmezett)
    {
        $ertek = $this->params->getStringRequestParam($nev);
        $datum = $ertek ? new \DateTime(\mkw\store::convDate($ertek)) : new \DateTime($alapertelmezett);
        return $datum->setTime(0, 0);
    }

}
