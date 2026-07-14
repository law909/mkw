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
            $megrendfej->setFedexshipdate($fedexres['shipdate']);
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
                $megrendfej->setFedexshipdate(null);
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
        if (!\mkw\store::isPDF()) {
            \mkw\store::writelog('Fedex dokumentum feltöltés: a pdf készítés nincs bekapcsolva', 'fedex_api_error.txt');
            return false;
        }
        $pdfpath = $this->createSzamlakepPdf($bizonylatfej);
        if (!$pdfpath) {
            \mkw\store::writelog(
                'Fedex dokumentum feltöltés: nem sikerült pdf-et készíteni a(z) '
                . $bizonylatfej->getId() . ' bizonylatból',
                'fedex_api_error.txt'
            );
            return false;
        }

        $fedexapi = $this->getApi();
        $fedexres = $fedexapi->uploadTradeDocument(
            $pdfpath,
            basename($pdfpath),
            [
                'shipdocumenttype' => 'COMMERCIAL_INVOICE',
                'origincountrycode' => 'HU',
                'destinationcountrycode' => $bizonylatfej->getFedexOrszagkod(),
                'trackingnumber' => $bizonylatfej->getFedextrackingnumber(),
                'shipdate' => ($bizonylatfej->getFedexshipdate()
                    ? $bizonylatfej->getFedexshipdate()->format('Y-m-d')
                    : null)
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
     * @return string|false a legenerált pdf útvonala
     */
    private function createSzamlakepPdf(Bizonylatfej $bizonylatfej)
    {
        $bizctrl = \Controllers\bizonylatfejController::factory($bizonylatfej->getBizonylattipusId());
        $html = $bizctrl->getBizonylatHTML($bizonylatfej->getId());
        if (!$html) {
            return false;
        }
        $pdfpath = \mkw\store::storagePath(\mkw\store::urlize($bizonylatfej->getId()) . '_fedex.pdf');
        \mkw\store::getPDFEngine($html)->saveAs($pdfpath);
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
