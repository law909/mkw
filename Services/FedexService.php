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
                $megrendfej->setFedextrackingnumber(null);
                $megrendfej->setFuvarlevelszam(null);
                \mkw\store::getEm()->persist($megrendfej);
                \mkw\store::getEm()->flush();
            }
        }
    }

    /**
     * Egy kötegre egy példány: így a tokent egyszer kéri le, a többi rendelés
     * már a példányon cache-elt tokennel megy ki.
     */
    private function getApi()
    {
        if ($this->fedexapi) {
            return $this->fedexapi;
        }
        return $this->fedexapi = new \mkwhelpers\FedexAPI([
                'apikey' => \mkw\store::getParameter(\mkw\consts::FedexApiKey),
                'secretkey' => \mkw\store::getParameter(\mkw\consts::FedexSecretKey),
                'accountnumber' => \mkw\store::getParameter(\mkw\consts::FedexAccountNumber),
                'apiurl' => \mkw\store::getParameter(\mkw\consts::FedexApiURL),
                'pdfdirectory' => \mkw\store::getParameter(\mkw\consts::FedexParcelLabelDir)
            ]
        );
    }

}
