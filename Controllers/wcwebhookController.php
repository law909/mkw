<?php

namespace Controllers;


use Entities\Apierrorlog;
use Entities\Arfolyam;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Fizmod;
use Entities\Orszag;
use Entities\Partner;
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

    private function createErrorLog($type, $order, $error)
    {
        // kell csinalni egy megrendeleslogot, oda irni a megrendeles szamat, order_keyt, datumot
        // a log itemeket egyesevel olvasottnak kell jelolni az adminban
        $log = new Apierrorlog();
        $log->setType($type);
        switch ($type) {
            case 'wcorder':
                $log->setObjectid('ID: ' . $order['id'] . '; order_key: ' . $order['order_key']);
                break;
        }
        $log->setMessage($error);
        $this->getEm()->persist($log);
//        $this->getEm()->flush();
    }

    public function orderCreated()
    {
        //$wcorder = json_decode(file_get_contents('wcorder.json'), true);
        $wcorder = json_decode(file_get_contents('php://input'), true);

        $iserror = false;

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
            $biztipus = $this->getRepo(Bizonylattipus::class)->find('megrendeles');

            $megr = new Bizonylatfej();
            $megr->setPersistentData();
            $megr->setBizonylattipus($biztipus);

            $partner = $this->getRepo(Partner::class)->findOneBy(['wcid' => $wcorder['customer_id']]);
            if (!$partner) {
                $partner = $this->getRepo(Partner::class)->findOneBy(['email' => $wcorder['billing']['email']]);
            }
            if (!$partner) {
                $partner = new Partner();
            }
            $partner->setWcid($wcorder['customer_id']);
            $partner->setVezeteknev($wcorder['billing']['last_name']);
            $partner->setKeresztnev($wcorder['billing']['first_name']);
            $partner->setNev($wcorder['billing']['first_name'] . ' ' . $wcorder['billing']['last_name']);
            $partner->setEmail($wcorder['billing']['email']);
            $partner->setTelefon($wcorder['billing']['phone']);
            $partner->setIrszam($wcorder['billing']['postcode']);
            $partner->setVaros($wcorder['billing']['city']);
            $partner->setUtca($wcorder['billing']['address_1']);
            $partner->setHazszam($wcorder['billing']['address_2']);
            $partner->setOrszag($orszag);
            $partner->setSzallnev($wcorder['shipping']['first_name'] . ' ' . $wcorder['shipping']['last_name']);
            $partner->setSzallirszam($wcorder['shipping']['postcode']);
            $partner->setSzallvaros($wcorder['shipping']['city']);
            $partner->setSzallutca($wcorder['shipping']['address_1']);
            $partner->setSzallhazszam($wcorder['shipping']['address_2']);

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

            //$megr->setSzallitasimod($szallmod);

            $megr->setKelt();
            $megr->setTeljesites();
            $megr->setEsedekesseg();

            $arf = $this->getEm()->getRepository(Arfolyam::class)->getActualArfolyam($valutanem, $megr->getTeljesites());
            $megr->setArfolyam($arf->getArfolyam());

            $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
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
            $megr->calcOsszesen();
            $this->getEm()->persist($megr);
        }
        $this->getEm()->flush();
        header('HTTP/1.1 200 OK');
    }

    public function partnerCreated()
    {
        $wcpartner = json_decode(file_get_contents('php://input'), true);

        $iserror = false;

        $orszag = $this->getRepo(Orszag::class)->findOneBy(['iso3166' => $wcpartner['billing']['country']]);
        if (!$orszag) {
            $this->createErrorLog('wcorder', $wcpartner, 'Ismeretlen ország: ' . $wcpartner['billing']['country']);
            $iserror = true;
        }

        if (!$iserror) {
            $partner = $this->getRepo(Partner::class)->findOneBy(['wcid' => $wcpartner['id']]);
            if (!$partner) {
                $partner = $this->getRepo(Partner::class)->findOneBy(['email' => $wcpartner['email']]);
            }
            if (!$partner) {
                $partner = new Partner();
            }
            $partner->setWcid($wcpartner['id']);
            $partner->setVezeteknev($wcpartner['billing']['last_name']);
            $partner->setKeresztnev($wcpartner['billing']['first_name']);
            $partner->setNev($wcpartner['billing']['first_name'] . ' ' . $wcpartner['billing']['last_name']);
            $partner->setEmail($wcpartner['email']);
            $partner->setTelefon($wcpartner['billing']['phone']);
            $partner->setIrszam($wcpartner['billing']['postcode']);
            $partner->setVaros($wcpartner['billing']['city']);
            $partner->setUtca($wcpartner['billing']['address_1']);
            $partner->setHazszam($wcpartner['billing']['address_2']);
            $partner->setOrszag($orszag);
            $partner->setSzallnev($wcpartner['shipping']['first_name'] . ' ' . $wcpartner['shipping']['last_name']);
            $partner->setSzallirszam($wcpartner['shipping']['postcode']);
            $partner->setSzallvaros($wcpartner['shipping']['city']);
            $partner->setSzallutca($wcpartner['shipping']['address_1']);
            $partner->setSzallhazszam($wcpartner['shipping']['address_2']);

            //$partner->setAdoszam('??????');
            //$partner->setVatstatus();
            //$partner->setSzamlatipus(); // EU-beluli, kivuli, magyar

            $partner->setWcdate();

            $this->getEm()->persist($partner);
            $this->getEm()->flush();
        }
        header('HTTP/1.1 200 OK');
    }
}