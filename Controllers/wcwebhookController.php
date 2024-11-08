<?php

namespace Controllers;


use Entities\Apierrorlog;
use Entities\Arfolyam;
use Entities\Bizonylatfej;
use Entities\Bizonylatstatusz;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Fizmod;
use Entities\Orszag;
use Entities\Partner;
use Entities\Szallitasimod;
use Entities\Termek;
use Entities\TermekValtozat;
use Entities\Valutanem;
use Proxies\__CG__\Entities\Raktar;

class wcwebhookController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        parent::__construct($params);
    }

    private function writelog($endpoint, $data)
    {
        $log = new \Entities\Webhooklog();
        $log->setIp($_SERVER['REMOTE_ADDR']);
        $log->setEndpoint($endpoint);
        $log->setData($data);
        $this->getEm()->persist($log);
        $this->getEm()->flush($log);
    }

    private function createErrorLog($type, $data, $error)
    {
        $log = new Apierrorlog();
        $log->setType($type);
        switch ($type) {
            case 'wcorder':
                $log->setObjectid('ID: ' . $data['id'] . '; order_key: ' . $data['order_key']);
                break;
            case 'wcpartner':
                $log->setObjectid('ID: ' . $data['id'] . '; email: ' . $data['email']);
                break;
        }
        $log->setMessage($error);
        $this->getEm()->persist($log);
//        $this->getEm()->flush();
    }

    public function orderCreated()
    {
        //$params = file_get_contents('wcorder.json');
        $params = file_get_contents('php://input');
        $this->writelog('WCOrderCreated', $params);
        $wcorder = json_decode($params, true);

        $iserror = false;

        $megr = $this->getRepo(Bizonylatfej::class)->findOneBy([
            'wcid' => $wcorder['id']
        ]);
        if ($megr) {
            $this->createErrorLog('wcorder', $wcorder, 'Már létezik megrendelés ezzel az azonosítóval: ' . $wcorder['id'] . ' => ' . $megr->getId());
            $iserror = true;
        }
        $bizstatusz = $this->getRepo(Bizonylatstatusz::class)->findOneBy(['wcid' => $wcorder['status']]);
        if (!$bizstatusz) {
            $this->createErrorLog('wcorder', $wcorder, 'Ismeretlen bizonylatstátusz: ' . $wcorder['status']);
            $iserror = true;
        }
        $raktar = $this->getRepo(Raktar::class)->find(\mkw\store::getParameter(\mkw\consts::Raktar));
        if (!$raktar) {
            $this->createErrorLog('wcorder', $wcorder, 'Nincs beállítva alapértelmezett raktár.');
            $iserror = true;
        }
        $orszag = $this->getRepo(Orszag::class)->findOneBy(['iso3166' => $wcorder['billing']['country']]);
        if (!$orszag) {
            $this->createErrorLog('wcorder', $wcorder, 'Ismeretlen ország: ' . $wcorder['billing']['country']);
            $iserror = true;
        }
        $szallorszag = $this->getRepo(Orszag::class)->findOneBy(['iso3166' => $wcorder['shipping']['country']]);
        if (!$szallorszag) {
            $this->createErrorLog('wcorder', $wcorder, 'Ismeretlen ország: ' . $wcorder['shipping']['country']);
            $iserror = true;
        }
        $valutanem = $this->getRepo(Valutanem::class)->findOneBy(['nev' => $wcorder['currency']]);
        if (!$valutanem) {
            $this->createErrorLog('wcorder', $wcorder, 'Ismeretlen valutanem: ' . $wcorder['currency']);
            $iserror = true;
        }
        $fizmod = $this->getRepo(Fizmod::class)->findOneBy(['wcid' => $wcorder['payment_method']]);
        if (!$fizmod) {
            $this->createErrorLog('wcorder', $wcorder, 'Ismeretlen fizetési mód: ' . $wcorder['payment_method']);
            $iserror = true;
        }
        $szallmod = $this->getRepo(Szallitasimod::class)->findOneBy(['wcid' => $wcorder['shipping_lines'][0]['method_id']]);
        if (!$szallmod) {
            $this->createErrorLog('wcorder', $wcorder, 'Ismeretlen szállítási mód: ' . $wcorder['shipping_lines'][0]['method_id']);
            $iserror = true;
        }
        foreach ($wcorder['line_items'] as $item) {
            $termek = $this->getRepo(Termek::class)->findOneBy(['wcid' => $item['product_id']]);
            if (!$termek) {
                $this->createErrorLog('wcorder', $wcorder, 'Ismeretlen termék: ' . $item['product_id'] . '; ' . $item['name']);
                $iserror = true;
            } elseif (array_key_exists('variation_id', $item) && $item['variation_id']) {
                $valtozat = $this->getRepo(TermekValtozat::class)->findOneBy([
                    'termek' => $termek,
                    'wcid' => $item['variation_id']
                ]);
                if (!$valtozat) {
                    $this->createErrorLog('wcorder', $wcorder, 'Ismeretlen változat: ' . $item['variation_id'] . '; ' . $item['name']);
                    $iserror = true;
                }
            }
        }

        if (!$iserror) {
            /** @var Bizonylattipus $biztipus */
            $biztipus = $this->getRepo(Bizonylattipus::class)->find('webshopbiz');

            $megr = new Bizonylatfej();
            $megr->setPersistentData();
            $megr->setBizonylattipus($biztipus);
            $megr->setWebshopnum(\mkw\store::getWcWebshopNum());
            $megr->setErbizonylatszam($wcorder['id']);

            $partner = $this->getRepo(Partner::class)->findOneBy(['wcid' => $wcorder['customer_id']]);
            if (!$partner) {
                $partner = $this->getRepo(Partner::class)->findOneBy(['email' => $wcorder['billing']['email']]);
            }
            if (!$partner) {
                $partner = new Partner();
            }

            $partner->setSkipListener(true);

            $partner->setWcid($wcorder['customer_id']);
            $partner->setVezeteknev($wcorder['billing']['last_name']);
            $partner->setKeresztnev($wcorder['billing']['first_name']);
            if ($wcorder['billing']['company']) {
                $partner->setNev($wcorder['billing']['company']);
            } else {
                $partner->setNev($wcorder['billing']['first_name'] . ' ' . $wcorder['billing']['last_name']);
            }
            $partner->setEmail($wcorder['billing']['email']);
            $partner->setTelefon($wcorder['billing']['phone']);
            $partner->setIrszam($wcorder['billing']['postcode']);
            $partner->setVaros($wcorder['billing']['city']);
            $partner->setUtca($wcorder['billing']['address_1']);
            $partner->setHazszam($wcorder['billing']['address_2']);
            $partner->setOrszag($orszag);
            if ($wcorder['shipping']['company']) {
                $partner->setSzallnev($wcorder['shipping']['company']);
            } else {
                $partner->setSzallnev($wcorder['shipping']['first_name'] . ' ' . $wcorder['shipping']['last_name']);
            }
            $partner->setSzallirszam($wcorder['shipping']['postcode']);
            $partner->setSzallvaros($wcorder['shipping']['city']);
            $partner->setSzallutca($wcorder['shipping']['address_1']);
            $partner->setSzallhazszam($wcorder['shipping']['address_2']);
            $partner->setSzallorszag($szallorszag);

            //$partner->setAdoszam('??????');
            //$partner->setVatstatus();
            //$partner->setSzamlatipus(); // EU-beluli, kivuli, magyar

            $partner->setWcdate();

            $this->getEm()->persist($partner);

            $megr->setPartner($partner);

            $megr->setWcid($wcorder['id']);
            $megr->setRaktar($raktar);
            $megr->setValutanem($valutanem);
            $megr->setBankszamla($valutanem->getBankszamla());
            $megr->setFizmod($fizmod);
            $megr->setSzallitasimod($szallmod);

            $megr->setKelt();
            $megr->setTeljesites();
            $megr->setEsedekesseg();

            $arf = $this->getEm()->getRepository(Arfolyam::class)->getActualArfolyam($valutanem, $megr->getTeljesites());
            $megr->setArfolyam($arf->getArfolyam());

            $megr->setBizonylatstatusz($bizstatusz);

            $megr->setMegjegyzes($wcorder['customer_note']);
            $megr->setBelsomegjegyzes('WooCommerce ID: ' . $wcorder['id'] . '; order_key: ' . $wcorder['order_key']);

            foreach ($wcorder['line_items'] as $item) {
                $termek = $this->getRepo(Termek::class)->findOneBy(['wcid' => $item['product_id']]);

                $tetel = new Bizonylattetel();
                $megr->addBizonylattetel($tetel);
                $tetel->setBizonylatfej($megr);

                $tetel->setPersistentData();
                $tetel->setTermek($termek);
                if ($valtozat) {
                    $tetel->setTermekvaltozat($valtozat);
                }
                $tetel->setMennyiseg($item['quantity']);
                $tetel->setBruttoegysar($item['price']);
                $tetel->setBruttoegysarhuf($tetel->getBruttoegysar() * $tetel->getArfolyam());
                $tetel->calc();
                $this->getEm()->persist($tetel);
            }
            $termek = $this->getRepo(Termek::class)->find(\mkw\store::getParameter(\mkw\consts::SzallitasiKtgTermek));
            if ($termek) {
                foreach ($wcorder['shipping_lines'] as $item) {
                    $tetel = new Bizonylattetel();
                    $megr->addBizonylattetel($tetel);
                    $tetel->setBizonylatfej($megr);

                    $tetel->setPersistentData();
                    $tetel->setTermek($termek);
                    $tetel->setTermeknev($item['method_title']);
                    $tetel->setMennyiseg(1);
                    $tetel->setBruttoegysar($item['total']);
                    $tetel->setBruttoegysarhuf($tetel->getBruttoegysar() * $tetel->getArfolyam());
                    $tetel->calc();
                    $this->getEm()->persist($tetel);
                }
            }
            $megr->calcOsszesen();
            $this->getEm()->persist($megr);
        }
        $this->getEm()->flush();
        header('HTTP/1.1 200 OK');
    }

    private function fillPartner($partner, $wcpartner, $orszag, $szallorszag)
    {
        $partner->setSkipListener(true);

        $partner->setWcid($wcpartner['id']);
        if ($wcpartner['billing']['last_name']) {
            $partner->setVezeteknev($wcpartner['billing']['last_name']);
        }
        if ($wcpartner['billing']['first_name']) {
            $partner->setKeresztnev($wcpartner['billing']['first_name']);
        }
        if ($wcpartner['billing']['company']) {
            $partner->setNev($wcpartner['billing']['company']);
        } elseif ($wcpartner['billing']['last_name'] || $wcpartner['billing']['first_name']) {
            $partner->setNev($wcpartner['billing']['first_name'] . ' ' . $wcpartner['billing']['last_name']);
        }
        if ($wcpartner['email']) {
            $partner->setEmail($wcpartner['email']);
        }
        if ($wcpartner['billing']['phone']) {
            $partner->setTelefon($wcpartner['billing']['phone']);
        }
        if ($wcpartner['billing']['postcode']) {
            $partner->setIrszam($wcpartner['billing']['postcode']);
        }
        if ($wcpartner['billing']['city']) {
            $partner->setVaros($wcpartner['billing']['city']);
        }
        if ($wcpartner['billing']['address_1']) {
            $partner->setUtca($wcpartner['billing']['address_1']);
        }
        if ($wcpartner['billing']['address_2']) {
            $partner->setHazszam($wcpartner['billing']['address_2']);
        }
        if ($orszag) {
            $partner->setOrszag($orszag);
        }
        if ($wcpartner['shipping']['company']) {
            $partner->setSzallnev($wcpartner['shipping']['company']);
        } elseif ($wcpartner['shipping']['last_name'] || $wcpartner['shipping']['first_name']) {
            $partner->setSzallnev($wcpartner['shipping']['first_name'] . ' ' . $wcpartner['shipping']['last_name']);
        }
        if ($wcpartner['shipping']['postcode']) {
            $partner->setSzallirszam($wcpartner['shipping']['postcode']);
        }
        if ($wcpartner['shipping']['city']) {
            $partner->setSzallvaros($wcpartner['shipping']['city']);
        }
        if ($wcpartner['shipping']['address_1']) {
            $partner->setSzallutca($wcpartner['shipping']['address_1']);
        }
        if ($wcpartner['shipping']['address_2']) {
            $partner->setSzallhazszam($wcpartner['shipping']['address_2']);
        }
        if ($szallorszag) {
            $partner->setSzallorszag($szallorszag);
        }

        //$partner->setAdoszam('??????');
        //$partner->setVatstatus();
        //$partner->setSzamlatipus(); // EU-beluli, kivuli, magyar

        $partner->setWcdate();
    }

    public function partnerCreated()
    {
        $params = file_get_contents('php://input');
        $this->writelog('WCPartnerCreated', $params);
        $wcpartner = json_decode($params, true);

        $iserror = false;

        if ($wcpartner['billing']['last_name']) {
            $this->createErrorLog('wcpartner', $wcpartner, 'Last_name nincs megadva');
            $iserror = true;
        }
        if ($wcpartner['billing']['first_name']) {
            $this->createErrorLog('wcpartner', $wcpartner, 'First_name nincs megadva');
            $iserror = true;
        }
        $orszag = $this->getRepo(Orszag::class)->findOneBy(['iso3166' => $wcpartner['billing']['country']]);
        $szallorszag = $this->getRepo(Orszag::class)->findOneBy(['iso3166' => $wcpartner['shipping']['country']]);

        if (!$iserror) {
            $partner = $this->getRepo(Partner::class)->findOneBy(['wcid' => $wcpartner['id']]);
            if (!$partner) {
                $partner = $this->getRepo(Partner::class)->findOneBy(['email' => $wcpartner['email']]);
            }
            if (!$partner) {
                $partner = new Partner();
            }

            $this->fillPartner($partner, $wcpartner, $orszag, $szallorszag);

            $this->getEm()->persist($partner);
            $this->getEm()->flush();
        }
        header('HTTP/1.1 200 OK');
    }

    public function partnerUpdated()
    {
        $params = file_get_contents('php://input');
        $this->writelog('WCPartnerUpdated', $params);
        $wcpartner = json_decode($params, true);

        $iserror = false;

        $orszag = $this->getRepo(Orszag::class)->findOneBy(['iso3166' => $wcpartner['billing']['country']]);
        $szallorszag = $this->getRepo(Orszag::class)->findOneBy(['iso3166' => $wcpartner['shipping']['country']]);

        if (!$iserror) {
            $partner = $this->getRepo(Partner::class)->findOneBy(['wcid' => $wcpartner['id']]);
            if (!$partner) {
                $partner = $this->getRepo(Partner::class)->findOneBy(['email' => $wcpartner['email']]);
            }
            if (!$partner) {
                $partner = new Partner();
            }

            $this->fillPartner($partner, $wcpartner, $orszag, $szallorszag);

            $this->getEm()->persist($partner);
            $this->getEm()->flush();
        }
        header('HTTP/1.1 200 OK');
    }

}