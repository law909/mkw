<?php

namespace Services;

use Entities\Bizonylatfej;

class FedexService
{
    /** @var \mkwhelpers\FedexAPI */
    private $fedexapi;

    public function sendToFedex($ids)
    {
        foreach ($ids as $id) {
            /** @var Bizonylatfej $megrendfej */
            $megrendfej = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
            if ($megrendfej
                && \mkw\store::isFedexSzallitasimod($megrendfej->getSzallitasimodId())
                && (!$megrendfej->getFedextrackingnumber())
            ) {
                $this->_sendToFedex($megrendfej);
            }
        }
    }

    private function _sendToFedex(Bizonylatfej $megrendfej)
    {
        $fedexapi = $this->getApi();
        $pdfname = $megrendfej->getSanitizedId() . '_fedex_label.pdf';
        $fedexres = $fedexapi->createShipment($megrendfej->toFedexAPI(), $pdfname);
        $fedexerror = $fedexapi->getLasterrors();
        if ($fedexerror) {
            \mkw\store::writelog('Fedex API error: ' . json_encode($fedexerror), 'fedex_api_error.txt');
        }
        if ($fedexres) {
            $elsocsomag = $fedexres['packages'][0];
            $trackingnumber = $fedexres['mastertrackingnumber'] ?: $elsocsomag['trackingnumber'];
            $megrendfej->setSimpleedit(true);
            $megrendfej->setFedextrackingnumber($trackingnumber);
            $megrendfej->setFedexparcellabelurl(array_column($fedexres['packages'], 'pdfname'));
            $megrendfej->setFuvarlevelszam($trackingnumber);
            \mkw\store::getEm()->persist($megrendfej);
            \mkw\store::getEm()->flush();
        }
    }

    public function delFedexParcel($id)
    {
        /** @var Bizonylatfej $megrendfej */
        $megrendfej = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
        if ($megrendfej && $megrendfej->getFedextrackingnumber()) {
            $fedexapi = $this->getApi();
            $fedexres = $fedexapi->cancelShipment($megrendfej->getFedextrackingnumber());
            $fedexerror = $fedexapi->getLasterrors();
            if ($fedexerror) {
                \mkw\store::writelog('Fedex API error: ' . json_encode($fedexerror), 'fedex_api_error.txt');
            }
            if ($fedexres) {
                $megrendfej->setSimpleedit(true);
                $megrendfej->setFedexparcellabelurl(null);
                $megrendfej->setShipdate(null);
                $megrendfej->setFedextrackingnumber(null);
                $megrendfej->setFuvarlevelszam(null);
                \mkw\store::getEm()->persist($megrendfej);
                \mkw\store::getEm()->flush();
            }
        }
    }

    /**
     * A bizonylat pdf számlaképét feltölti a Fedexhez kereskedelmi dokumentumként.
     *
     * Ha a bizonylaton már van Fedex fuvarlevélszám és feladási dátum, akkor utólagos
     * (post-shipment) feltöltés történik – ilyenkor a Fedex a küldeményhez rendeli a
     * dokumentumot. Egyébként feladás előtti (pre-shipment) feltöltés, ilyenkor a
     * visszakapott docid-t kell a küldemény feladásakor megadni.
     *
     * @return string|false a Fedex dokumentum azonosítója (docid)
     */
    public function uploadSzamlakep(Bizonylatfej $bizonylatfej)
    {
        $pdfpath = $this->createSzamlakepPdf($bizonylatfej);
        if (!$pdfpath) {
            \mkw\store::writelog(
                'Fedex dokumentum feltöltés: nem sikerült pdf-et készíteni a(z) '
                . $bizonylatfej->getId() . ' bizonylatból',
                'fedex_api_error.txt'
            );
            return false;
        }

        $kuldemeny = $this->getFedexKuldemenyadatok($bizonylatfej);

        $fedexapi = $this->getApi();
        $fedexres = $fedexapi->uploadTradeDocument(
            $pdfpath,
            basename($pdfpath),
            [
                'shipdocumenttype' => 'COMMERCIAL_INVOICE',
                'origincountrycode' => 'HU',
                'destinationcountrycode' => $bizonylatfej->getPartnerSzallorszagOrOrszag()?->getIso3166(),
                'trackingnumber' => $kuldemeny['trackingnumber'],
                'shipdate' => $kuldemeny['shipdate']
            ]
        );
        $fedexerror = $fedexapi->getLasterrors();
        if ($fedexerror) {
            \mkw\store::writelog('Fedex API error: ' . json_encode($fedexerror), 'fedex_api_error.txt');
        }
        unlink($pdfpath);

        return $fedexres ? $fedexres['docid'] : false;
    }

    /**
     * A küldemény Fedex azonosítói. A fuvarlevélszám és a feladási dátum a
     * megrendelésre kerül, ezért ha a kapott bizonylaton (pl. számlán) nincsenek
     * meg, a szülő bizonylatról olvassuk ki őket. Enélkül a Fedex nem tudná,
     * melyik küldeményhez tartozik a dokumentum.
     */
    private function getFedexKuldemenyadatok(Bizonylatfej $bizonylatfej)
    {
        $forras = $bizonylatfej;
        if (!$forras->getFedextrackingnumber()) {
            $par = $bizonylatfej->getParbizonylatfej();
            if ($par && $par->getFedextrackingnumber()) {
                $forras = $par;
            }
        }
        return [
            'trackingnumber' => $forras->getFedextrackingnumber(),
            'shipdate' => ($forras->getShipdate()
                ? $forras->getShipdate()->format('Y-m-d')
                : null)
        ];
    }

    /**
     * @return string|false a legenerált pdf útvonala
     */
    private function createSzamlakepPdf(Bizonylatfej $bizonylatfej)
    {
        $pdf = (new BizonylatPrintService())->createEngine($bizonylatfej->getId());
        if (!$pdf) {
            return false;
        }
        $pdfpath = \mkw\store::storagePath(\mkw\store::urlize($bizonylatfej->getId()) . '_fedex.pdf');
        $pdf->saveAs($pdfpath);
        if (!is_readable($pdfpath)) {
            return false;
        }
        return $pdfpath;
    }

    public function getRatesById($id)
    {
        /** @var Bizonylatfej $megrendfej */
        $megrendfej = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
        if (!$megrendfej) {
            return ['error' => 'Nincs ilyen bizonylat'];
        }
        $fedexapi = $this->getApi();
        $rates = $fedexapi->getRates($megrendfej->toFedexRateAPI());
        if (!$rates) {
            $error = $this->getApi()->getLasterrors();
            \mkw\store::writelog('Fedex rate API error: ' . json_encode($error), 'fedex_api_error.txt');
            return ['error' => ($error ? $this->errorText($error) : 'A Fedex nem adott díjat erre a küldeményre')];
        }
        return ['rates' => $rates];
    }

    private function errorText($errors)
    {
        $result = [];
        foreach ($errors as $error) {
            $result[] = trim(($error->code ?? '') . ' ' . ($error->message ?? ''));
        }
        return implode(', ', $result);
    }

    /**
     * A kosár tartalmára és a pénztárnál megadott szállítási címre kért Fedex díjak.
     * Az eredményt a munkamenetbe is eltesszük: a szállítási költséghez a vevő
     * választását innen olvassuk vissza, hogy ne kelljen kosárfrissítésenként újra hívni.
     *
     * @param array $cim szallnev, szallirszam, szallvaros, szallutca, szallorszag, telefon, email
     */
    public function getRatesForKosar($szallitasimodid, array $cim)
    {
        $fej = $this->createKosarBizonylatfej($szallitasimodid, $cim);
        if (!$fej) {
            return ['error' => 'Üres a kosár, vagy hiányos a szállítási cím'];
        }
        $rates = $this->getApi()->getRates($fej->toFedexRateAPI());
        if (!$rates) {
            $error = $this->getApi()->getLasterrors();
            \mkw\store::writelog('Fedex rate API error: ' . json_encode($error), 'fedex_api_error.txt');
            \mkw\store::getMainSession()->fedexrates = [];
            return ['error' => ($error ? $this->errorText($error) : 'A Fedex nem adott díjat erre a küldeményre')];
        }

        $tarolt = [];
        foreach ($rates as $i => $rate) {
            $rates[$i]['szallitasidij'] = $this->convertToWebshopValuta($rate['brutto'], $rate['valutanem']);
            $rates[$i]['kiszallitasdatum'] = $this->kiszallitasDatum($rate['szallitasiido'] ?? null);
            $tarolt[$rate['servicetype']] = $rates[$i];
        }
        \mkw\store::getMainSession()->fedexrates = $tarolt;

        return ['rates' => array_values($rates)];
    }

    /**
     * A Fedex a vállalt kiszállítást ISO időbélyeggel adja (commit.dateDetail.dayFormat),
     * a vevőnek dátum kell. Ha nem értelmezhető, üresen marad – a szolgáltatás díja
     * enélkül is választható.
     */
    private function kiszallitasDatum($szallitasiido)
    {
        if (!$szallitasiido) {
            return '';
        }
        $datum = date_create($szallitasiido);
        return $datum ? $datum->format(\mkw\store::$DateFormat) : '';
    }

    /**
     * A vevő által választott Fedex szolgáltatás szállítási díja a webshop valutanemében.
     *
     * @return float|false
     */
    public function getKosarSzallitasiDij($servicetype)
    {
        $rates = \mkw\store::getMainSession()->fedexrates;
        if (!$servicetype || !is_array($rates) || !array_key_exists($servicetype, $rates)) {
            return false;
        }
        return $rates[$servicetype]['szallitasidij'];
    }

    /**
     * A Fedex a saját (számla)valutanemében adja a díjat, a kosár a webshopéban számol.
     */
    private function convertToWebshopValuta($osszeg, $valutanemnev)
    {
        $webshopvaluta = \mkw\store::getWebshopValutanem();
        if (!$valutanemnev || !$webshopvaluta || $valutanemnev === $webshopvaluta->getNev()) {
            return (float)$osszeg;
        }
        $repo = \mkw\store::getEm()->getRepository(\Entities\Valutanem::class);
        $dijvaluta = $repo->findOneBy(['nev' => $valutanemnev]);
        if (!$dijvaluta) {
            \mkw\store::writelog(
                'Fedex rate: ismeretlen valutanem: ' . $valutanemnev,
                'fedex_api_error.txt'
            );
            return (float)$osszeg;
        }
        $arfrepo = \mkw\store::getEm()->getRepository(\Entities\Arfolyam::class);
        $ma = date_create();
        $dijarfolyam = (float)$arfrepo->getActualArfolyam($dijvaluta, $ma)->getArfolyam();
        $webshoparfolyam = (float)$arfrepo->getActualArfolyam($webshopvaluta, $ma)->getArfolyam();
        if ($webshoparfolyam <= 0) {
            return (float)$osszeg;
        }
        return round($osszeg * $dijarfolyam / $webshoparfolyam, 2);
    }

    /**
     * A kosárból és a pénztár szállítási címéből összerakott, nem perzisztált bizonylat:
     * csak azért van, hogy a Fedex díjlekérdezés ugyanazt a kérésképzőt használhassa,
     * mint a már rögzített megrendelés (Bizonylatfej::toFedexRateAPI()).
     *
     * @return \Entities\Bizonylatfej|null
     */
    private function createKosarBizonylatfej($szallitasimodid, array $cim)
    {
        $em = \mkw\store::getEm();
        $kosartetelek = $em->getRepository(\Entities\Kosar::class)->getDataBySessionId(\mkw\session::getId());
        if (!$kosartetelek || !$cim['szallirszam'] || !$cim['szallvaros'] || !$cim['szallorszag']) {
            return null;
        }

        $fej = new Bizonylatfej();
        $fej->setPersistentData();
        $fej->setKelt('');
        $fej->setSzallitasimod($em->getRepository(\Entities\Szallitasimod::class)->find($szallitasimodid));
        $valutanem = \mkw\store::getWebshopValutanem();
        $fej->setValutanem($valutanem);
        $fej->setArfolyam(
            $em->getRepository(\Entities\Arfolyam::class)->getActualArfolyam($valutanem, $fej->getKelt())->getArfolyam()
        );
        $fej->setPartnerszallorszag($cim['szallorszag']);
        $fej->setPartnernev($cim['szallnev']);
        $fej->setPartnertelefon($cim['telefon']);
        $fej->setPartneremail($cim['email']);
        $fej->setSzallnev($cim['szallnev']);
        $fej->setSzallirszam($cim['szallirszam']);
        $fej->setSzallvaros($cim['szallvaros']);
        $fej->setSzallutca($cim['szallutca']);

        /** @var \Entities\Kosar $kt */
        foreach ($kosartetelek as $kt) {
            $tetel = new \Entities\Bizonylattetel();
            $tetel->setBizonylatfej($fej);
            $tetel->setPersistentData();
            $tetel->setTermek($kt->getTermek());
            $tetel->setTermekvaltozat($kt->getTermekvaltozat());
            $tetel->setMennyiseg($kt->getMennyiseg());
            $tetel->setNettoegysar($kt->getNettoegysar());
            $tetel->setBruttoegysar($kt->getBruttoegysar());
            $tetel->calc();
        }

        return $fej;
    }

    protected function getApi()
    {
        if ($this->fedexapi) {
            return $this->fedexapi;
        }
        return $this->fedexapi = new \mkwhelpers\FedexAPI([
                'apikey' => \mkw\store::getParameter(\mkw\consts::FedexApiKey),
                'secretkey' => \mkw\store::getParameter(\mkw\consts::FedexSecretKey),
                'accountnumber' => \mkw\store::getParameter(\mkw\consts::FedexAccountNumber),
                'apiurl' => \mkw\store::getParameter(\mkw\consts::FedexApiURL),
                'docapiurl' => \mkw\store::getParameter(\mkw\consts::FedexDocApiURL),
                'pdfdirectory' => \mkw\store::getParameter(\mkw\consts::FedexParcelLabelDir)
            ]
        );
    }

}
