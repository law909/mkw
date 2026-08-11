<?php

namespace Services;

use Entities\Arfolyam;
use Entities\Valutanem;
use mkwhelpers\FilterDescriptor;

/**
 * MNB árfolyamok letöltése.
 *
 * A saját (elszámolási) valutanemre nem kérünk árfolyamot – arra definíció szerint 1 –, a
 * többire igen. Egy adott napra és valutanemre **csak akkor** írunk, ha még nincs sor: a
 * kézzel javított árfolyamot a letöltés nem írja felül.
 *
 * Két hívója van, ugyanezzel a viselkedéssel: az admin gomb (arfolyamController::downloadArfolyam)
 * és a `php cron.php arfolyam` (Services\Cron\ArfolyamTask).
 */
class ArfolyamService
{

    const WSDL = 'http://www.mnb.hu/arfolyamok.asmx?WSDL';

    /**
     * Egy nap árfolyamainak letöltése és mentése.
     *
     * @param string $datumstr a kért nap; üresen a mai
     *
     * @return array{datum: string, valutak: string[], kapott: int, mentve: int}
     *         `kapott` az MNB-től jött árfolyamok száma (hétvégén/ünnepen 0),
     *         `mentve` amennyi ebből új sorként bekerült
     *
     * @throws \SoapFault ha az MNB szolgáltatás nem érhető el
     */
    public function download($datumstr = '')
    {
        $datum = \mkw\store::convDate($datumstr);
        $datum = date(\mkw\store::$DateFormat, $datum ? strtotime($datum) : time());

        $valutak = $this->getValutanemNevek();
        $result = ['datum' => $datum, 'valutak' => $valutak, 'kapott' => 0, 'mentve' => 0];
        if (!$valutak) {
            return $result;
        }

        $rates = $this->getRates($datum, $valutak);
        if (!$rates) {
            return $result;
        }

        $em = \mkw\store::getEm();
        $vr = $em->getRepository(Valutanem::class);
        $ar = $em->getRepository(Arfolyam::class);
        foreach ($rates as $rate) {
            $result['kapott']++;
            $valutanem = $vr->findOneBy(['nev' => (string)$rate['curr']]);
            if (!$valutanem) {
                continue;
            }
            // a meglévő sort nem bántjuk: lehet kézzel javított érték
            if ($ar->getArfolyam($valutanem, $datum)) {
                continue;
            }
            $arfolyam = new Arfolyam();
            $arfolyam->setValutanem($valutanem);
            $arfolyam->setDatum(new \DateTime(\mkw\store::convDate($datum)));
            $arfolyam->setArfolyam((float)str_replace(',', '.', (string)$rate));
            $em->persist($arfolyam);
            $result['mentve']++;
        }
        if ($result['mentve']) {
            $em->flush();
        }

        return $result;
    }

    /**
     * A letöltendő valutanemek nevei: az elszámolási valutanem kivételével mind.
     *
     * @return string[]
     */
    public function getValutanemNevek()
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('id', '<>', \mkw\store::getParameter(\mkw\consts::Valutanem));

        $ret = [];
        foreach (\mkw\store::getEm()->getRepository(Valutanem::class)->getAll($filter) as $valutanem) {
            $ret[] = $valutanem->getNev();
        }
        return $ret;
    }

    /**
     * @param string $datum
     * @param string[] $valutak
     *
     * @return \SimpleXMLElement[] a nap árfolyamai; üres, ha az MNB nem adott adatot
     */
    private function getRates($datum, array $valutak)
    {
        $srv = new \SoapClient(self::WSDL);
        $res = $srv->__soapCall('GetExchangeRates', [
            'parameters' => [
                'startDate' => $datum,
                'endDate' => $datum,
                'currencyNames' => implode(',', $valutak),
            ],
        ]);
        if (!$res) {
            return [];
        }
        $xml = simplexml_load_string($res->GetExchangeRatesResult);
        // hétvégén és ünnepnapon nincs Day elem, tehát nincs mit menteni
        if (!$xml || !isset($xml->Day)) {
            return [];
        }
        $ret = [];
        foreach ($xml->Day->Rate as $rate) {
            $ret[] = $rate;
        }
        return $ret;
    }

}
