<?php

namespace Controllers;

use Entities\Afa;
use Entities\Apiconsumer;
use Entities\Arfolyam;
use Entities\Bizonylatfej;
use Entities\Bizonylatstatusz;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Fizmod;
use Entities\ME;
use Entities\Partner;
use Entities\Raktar;
use Entities\Termek;
use Entities\TermekFa;
use Entities\TermekValtozat;
use Entities\Valutanem;
use Entities\Vtsz;
use mkwhelpers\FilterDescriptor;

class a2aController extends \mkwhelpers\Controller
{

    private $tr;
    private $tkr;
    private $pr;

    protected function Auth($nev, $kulcs)
    {
        $r = $this->getRepo(Apiconsumer::class)->findOneBy(['nev' => $nev, 'kulcs' => $kulcs]);
        return $r;
    }

    protected function writelog($consumer, $q, $r)
    {
        $log = new \Entities\Apiconsumelog();
        $log->setApiconsumer($consumer);
        $log->setIp($_SERVER['REMOTE_ADDR']);
        $log->setQuery($q);
        $log->setResult($r);
        $this->getEm()->persist($log);
        $this->getEm()->flush($log);
    }

    protected function getFafilter()
    {
        $termekfak = $this->getRepo(TermekFa::class)->getB2B();
        $fafilter = [];
        /** @var TermekFa $termekfa */
        foreach ($termekfak as $termekfa) {
            $fafilter[] = $termekfa->getId();
        }
        return $fafilter;
    }

    protected function gettermek_id($id, $partner = null)
    {
        $fafilter = $this->getFafilter();
        /** @var Termek $termek */
        $termek = $this->tr->find($id);
        $termekadat = null;
        if ($termek &&
            $termek->getLathato() == 1 &&
            $termek->getFuggoben() == 0 &&
            $termek->getInaktiv() == 0 &&
            in_array($termek->getTermekfa1Id(), $fafilter)
        ) {
            $termekadat = $termek->toA2a($partner);
        }
        return [$termekadat];
    }

    protected function gettermek_ids($ids, $partner = null)
    {
        $ret = [];
        $filter = new FilterDescriptor();
        $filter->addFilter('id', 'IN', $ids);
        $filter->addFilter('lathato', '=', 1);
        $filter->addFilter('fuggoben', '=', 0);
        $filter->addFilter('inaktiv', '=', 0);
        $fafilter = $this->getFafilter();
        if ($fafilter) {
            $filter->addFilter('termekfa1', 'IN', $fafilter);
        }
        $termekek = $this->tr->getWithJoins($filter);
        /** @var Termek $termek */
        foreach ($termekek as $termek) {
            $termekadat = $termek->toA2a($partner);
            $ret[] = $termekadat;
        }
        return $ret;
    }

    protected function gettermek_all($partner = null)
    {
        $ret = [];
        $filter = new FilterDescriptor();
        $filter->addFilter('lathato', '=', 1);
        $filter->addFilter('fuggoben', '=', 0);
        $filter->addFilter('inaktiv', '=', 0);
        $fafilter = $this->getFafilter();
        if ($fafilter) {
            $filter->addFilter('termekfa1', 'IN', $fafilter);
        }
        $termekek = $this->tr->getWithJoins($filter);
        /** @var Termek $termek */
        foreach ($termekek as $termek) {
            $termekadat = $termek->toA2a($partner);
            $ret[] = $termekadat;
        }
        return $ret;
    }

    protected function getkeszlet_id($id)
    {
        $x = [];
        $x['id'] = $id;
        $termek = $this->tr->find($id);
        if ($termek) {
            $valtozatok = $termek->getValtozatok();
            if ($valtozatok) {
                foreach ($valtozatok as $valt) {
                    if ($valt->getLathato() && $valt->getElerheto()) {
                        $valtadat = [];
                        $valtadat['id'] = $valt->getId();
                        $keszlet = max($valt->getKeszlet() - $valt->getFoglaltMennyiseg() - $valt->calcMinboltikeszlet(), 0);
                        if ($keszlet < 0) {
                            $keszlet = 0;
                        }
                        $valtadat['stock'] = $keszlet;
                        $x['variations'][] = $valtadat;
                    }
                }
            }
        }
        return $x;
    }

    protected function getkeszlet_ids($ids)
    {
        $ret = [];
        $filter = new FilterDescriptor();
        $filter->addFilter('id', 'IN', $ids);
        $termekek = $this->tr->getWithJoins($filter);
        foreach ($termekek as $termek) {
            $x = [];
            $x['id'] = $termek->getId();
            $valtozatok = $termek->getValtozatok();
            if ($valtozatok) {
                foreach ($valtozatok as $valt) {
                    if ($valt->getLathato() && $valt->getElerheto()) {
                        $valtadat = [];
                        $valtadat['id'] = $valt->getId();
                        $keszlet = max($valt->getKeszlet() - $valt->getFoglaltMennyiseg() - $valt->calcMinboltikeszlet(), 0);
                        if ($keszlet < 0) {
                            $keszlet = 0;
                        }
                        $valtadat['stock'] = $keszlet;
                        $x['variations'][] = $valtadat;
                    }
                }
            }
            $ret[] = $x;
        }
        return $ret;
    }

    protected function getkategoriak()
    {
        $termekfak = $this->getRepo(TermekFa::class)->getB2B();
        $result = [];
        foreach ($termekfak as $termekfa) {
            $result[] = $termekfa->toA2a();
        }
        return $result;
    }

    protected function partner_get($id)
    {
        $partner = $this->pr->find($id);
        $partneradat = null;
        if ($partner) {
            $partneradat = $partner->toA2a();
        }
        return $partneradat;
    }

    protected function createRaktar($nev)
    {
        $raktar = $this->getRepo(Raktar::class)->findOneBy(['idegenkod' => $nev]);
        if (!$raktar) {
            $raktar = new Raktar();
            $raktar->setNev($nev);
            $raktar->setMozgat(true);
            $raktar->setIdegenkod($nev);
            $this->getEm()->persist($raktar);
            $this->getEm()->flush($raktar);
        }
        return $raktar;
    }

    protected function createFizmod($nev, $tipus, $navtipus)
    {
        if (!$nev) {
            $nev = 'Készpénz';
            $tipus = 'P';
            $navtipus = 'CASH';
        }
        $fizmod = $this->getRepo(Fizmod::class)->findOneBy(['nev' => $nev]);
        if (!$fizmod) {
            $fizmod = new Fizmod();
            $fizmod->setNev($nev);
            $fizmod->setTipus($tipus);
            $fizmod->setNavtipus($navtipus);
            $this->getEm()->persist($fizmod);
            $this->getEm()->flush($fizmod);
        }
        return $fizmod;
    }

    protected function createVtsz($nev, $afa = null)
    {
        if (!$nev) {
            $nev = '.';
        }
        $vtsz = $this->getRepo(Vtsz::class)->findOneBy(['szam' => $nev]);
        if (!$vtsz) {
            $vtsz = new Vtsz();
            $vtsz->setSzam($nev);
            if ($afa) {
                $vtsz->setAfa($afa);
            }
            $this->getEm()->persist($vtsz);
            $this->getEm()->flush($vtsz);
        }
        return $vtsz;
    }

    protected function createAFA($nev)
    {
        if (is_null($nev) || $nev == '' || $nev == 25) {
            $nev = 27;
        }
        if ($nev == 20) {
            $nev = 18;
        }
        $afa = $this->getRepo(Afa::class)->findOneBy(['ertek' => $nev]);
        if (!$afa) {
            $afa = new Afa();
            $afa->setNev($nev . ' %');
            $afa->setErtek($nev);
            $this->getEm()->persist($afa);
            $this->getEm()->flush($afa);
        }
        return $afa;
    }

    protected function createME($nev, $navtipus)
    {
        if (!$nev) {
            $nev = 'db';
            $navtipus = 'PIECE';
        }
        $me = $this->getRepo(ME::class)->findOneBy(['nev' => $nev]);
        if (!$me) {
            $me = new ME();
            $me->setNev($nev);
            $me->setNavtipus($navtipus);
            $this->getEm()->persist($me);
            $this->getEm()->flush($me);
        }
        return $me;
    }

    public function processCmd($data = null)
    {
        $pelda = [
            'auth' => [
                'name' => 'superzone.hu',
                'key' => 'xxxx'
            ],
            'cmds' => [
                'hello',
                'getproduct' => [
                    'ids' => [1, 2, 3, 4, 5], // vagy
                    'id' => 1062, // vagy
                    'all' => 1
                ],
                'getstock' => [
                    'ids' => [1, 2, 3, 4, 5], // vagy
                    'id' => 1062
                ],
                'getcategory' => [
                    'all' => 1
                ],
                'partner' => [
                    'get' => 1,
                    'login' => [
                        'email' => 'balint.lovey@gmail.com',
                        'password' => 'a'
                    ],
                    'reg' => [
                        'email' => '',
                        'password' => '',
                        'nev' => '',
                        'vezeteknev' => '',
                        'keresztnev' => '',
                        'irszam' => '',
                        'varos' => '',
                        'utca' => '',
                        'hazszam' => '',
                        'telefon' => '',
                        'adoszam' => '',
                        'euadoszam' => '',
                        'szallnev' => '',
                        'szallirszam' => '',
                        'szallvaros' => '',
                        'szallutca' => '',
                        'szallhazszam' => '',
                        'vendeg' => 0
                    ]
                ],
                'szamla' => [
                    'createraw' => [
                        'telephelykod' => 0,
                        'idegenbizszam' => '',
                        'nev' => '',
                        'irszam' => '',
                        'varos' => '',
                        'utca' => '',
                        'hazszam' => '',
                        'adoszam' => '',
                        'fizmodnev' => '',
                        'fizmodtipus' => '',
                        'fizmodnavkod' => '',
                        'megjegyzes' => '',
                        'tetelek' => [
                            'termeknev' => '',
                            'cikkszam' => '',
                            'szallitoicikkszam' => '',
                            'vtszszam' => '',
                            'afakulcs' => 27,
                            'me' => '',
                            'menavkod' => '',
                            'mennyiseg' => 0,
                            'nettoegysar' => 0,
                            'bruttoegysar' => 0,
                            'netto' => 0,
                            'afa' => 0,
                            'brutto' => 0
                        ]
                    ]
                ],
                'megrendeles' => [
                    'create' => [
                        'idegenbizszam' => '',
                        'partner_id' => '',
                        'fizmodnev' => '',
                        'fizmodtipus' => '',
                        'fizmodnavkod' => '',
                        'szallmodnev' => '',
                        'megjegyzes' => '',
                        'tetelek' => [
                            'termek_id' => '',
                            'termekvaltozat_id' => '',
                            'mennyiseg' => 0,
                            'bruttoegysar' => 0,
                        ]
                    ]
                ],
                'b2border' => [
                    'create' => [
                        'id' => '',
                        'notes' => '',
                        'products' => [
                            'product_id' => '',
                            'productvariation_id' => '',
                            'quantity' => 0,
                        ]
                    ]
                ],
                'getnaveredmenyriasztas'
            ]
        ];

        $this->tr = \mkw\store::getEm()->getRepository(Termek::class);
        $this->tkr = \mkw\store::getEm()->getRepository(TermekFa::class);
        $this->pr = \mkw\store::getEm()->getRepository(Partner::class);

        $result = [];
        $results = [];

        if ($data) {
            $rawdata = $data;
        } else {
            $rawdata = $this->params->getOriginalStringRequestParam('data');
            if (!$rawdata) {
                $rawdata = file_get_contents('php://input');
            }
        }
        $jsondata = json_decode($rawdata, true);

        // nem lahtato, nem elerheto, nem kaphato, inaktiv termek eseten visszadobni a rendelest
        // ha nincs annyi keszlet, visszadobni a rendelest
        // ha nem a mi arunkat kuldi, vissyadobni a rendelest
        // fcmoto orderform beolvasasnal is!!!!

        $auth = $jsondata['auth'];
        $consumer = $this->Auth($auth['name'], $auth['key']);
        if ($consumer) {
            $result['auth'] = 1;

            $cmds = $jsondata['cmds'];
            foreach ($cmds as $cmdkey => $cmd) {
                switch ($cmdkey) {
                    case 'hello':
                        $results['hello'] = 'hello';
                        break;
                    case 'getproduct':
                        if (array_key_exists('id', $cmd)) {
                            $results['products'] = $this->gettermek_id($cmd['id'], $consumer->getPartner());
                        } elseif (array_key_exists('ids', $cmd)) {
                            $results['products'] = $this->gettermek_ids($cmd['ids'], $consumer->getPartner());
                        } elseif (array_key_exists('all', $cmd)) {
                            $results['products'] = $this->gettermek_all($consumer->getPartner());
                        }
                        $this->writelog($consumer, $rawdata, json_encode($results));
                        $i = 0;
                        foreach ($results['products'] as $product) {
                            \mkw\store::writelog($product['sku'] . ' ' . $product['name_en'] . ', id=' . $product['id']);
                            $i++;
                        }
                        \mkw\store::writelog($i . ' product az apiban');
                        break;
                    case 'getstock':
                        if (array_key_exists('id', $cmd)) {
                            $results['stock'] = $this->getkeszlet_id($cmd['id']);
                        } elseif (array_key_exists('ids', $cmd)) {
                            $results['stocks'] = $this->getkeszlet_ids($cmd['ids']);
                        }
                        $this->writelog($consumer, $rawdata, json_encode($results));
                        break;
                    case 'getcategory':
                        if (array_key_exists('all', $cmd)) {
                            $results['categories'] = $this->getkategoriak();
                        }
                        $this->writelog($consumer, $rawdata, json_encode($results));
                        break;
                    case 'partner':
                        if (array_key_exists('get', $cmd)) {
                            $results['partner'] = $this->partner_get($cmd['get']);
                        }
                        if (array_key_exists('login', $cmd)) {
                            $padat = [];
                            $padat['success'] = 0;
                            $pc = new \Controllers\partnerController(null);
                            $partner = $pc->apiLogin($cmd['login']['email'], $cmd['login']['password']);
                            if ($partner) {
                                $padat = $partner->toA2a();
                                $padat['success'] = 1;
                            }
                            $results['login'] = $padat;
                        }
                        if (array_key_exists('reg', $cmd)) {
                            $padat = [];
                            $padat['success'] = 0;
                            $pc = new \Controllers\partnerController(null);
                            $msgs = $pc->checkApiRegData($cmd['reg']);
                            if (!$msgs) {
                                $partner = $pc->saveApiRegData($cmd['reg'], $consumer);
                                if ($partner) {
                                    $padat = $partner->toA2a();
                                    $padat['success'] = 1;
                                }
                            } else {
                                $padat['msg'] = $msgs;
                            }
                            $results['reg'] = $padat;
                        }
                        $this->writelog($consumer, $rawdata, json_encode($results));
                        break;
                    case 'szamla':
                        if (array_key_exists('createraw', $cmd)) {
                            $results['success'] = 0;
                            $results['msg'] = '';

                            $data = $cmd['createraw'];

                            /** @var Bizonylattipus $biztipus */
                            $biztipus = $this->getRepo(Bizonylattipus::class)->find('szamla');
                            if (!$biztipus) {
                                $results['msg'] .= ' Nincs számla biz.tipus.';
                            }
                            /** @var Valutanem $valutanem */
                            $valutanem = $this->getRepo(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
                            if (!$valutanem) {
                                $results['msg'] .= ' Nincs valutanem.';
                            }
                            /** @var Partner $defapartner */
                            $defapartner = $this->getRepo(Partner::class)->find(\mkw\store::getParameter(\mkw\consts::DefaultPartner));
                            if (!$defapartner) {
                                $results['msg'] .= ' Nincs default partner';
                            }
                            /** @var Termek $defatermek */
                            $defatermek = $this->getRepo(Termek::class)->find(\mkw\store::getParameter(\mkw\consts::DefaultTermek));
                            if (!$defatermek) {
                                $results['msg'] .= ' Nincs default termék.';
                            }
                            if ($results['msg'] === '') {
                                $raktar = $this->createRaktar($data['telephelykod']);
                                $fizmod = $this->createFizmod($data['fizmodnev'], $data['fizmodtipus'], $data['fizmodnavkod']);

                                $szamlafej = new Bizonylatfej();
                                $szamlafej->setPersistentData();

                                $szamlafej->setBizonylattipus($biztipus);
                                $szamlafej->setPartner($defapartner);

                                $szamlafej->setRaktar($raktar);
                                $szamlafej->setValutanem($valutanem);
                                $szamlafej->setBankszamla($valutanem->getBankszamla());
                                $szamlafej->setArfolyam(1);
                                $szamlafej->setFizmod($fizmod);

                                $szamlafej->setKelt();
                                $szamlafej->setTeljesites();
                                $szamlafej->setEsedekesseg($data['esedekesseg']);

                                $szamlafej->setPartnernev(trim($data['nev']));
                                $szamlafej->setPartnerirszam(trim($data['irszam'], ',. \n\r\t\v'));
                                $szamlafej->setPartnervaros(trim($data['varos']));
                                $szamlafej->setPartnerutca(trim($data['utca']));
                                $szamlafej->setPartnerhazszam(trim($data['hazszam']));
                                $szamlafej->setPartneradoszam(trim($data['adoszam']));
                                if (array_key_exists('vatstatus', $data)) {
                                    $szamlafej->setPartnervatstatus($data['vatstatus']);
                                    if (!$szamlafej->getPartneradoszam()) {
                                        $szamlafej->setPartnervatstatus(2);
                                    }
                                } elseif ($szamlafej->getPartneradoszam()) {
                                    $szamlafej->setPartnervatstatus(1);
                                } else {
                                    $szamlafej->setPartnervatstatus(2);
                                }

                                $szamlafej->setMegjegyzes($data['megjegyzes']);
                                $szamlafej->setBelsomegjegyzes($data['idegenbizszam']);
                                $this->getEm()->persist($szamlafej);

                                foreach ($data['tetelek'] as $tetel) {
                                    $afa = $this->createAFA($tetel['afakulcs']);
                                    $vtsz = $this->createVtsz(trim($tetel['vtszszam']), $afa);
                                    $me = $this->createME(trim($tetel['me']), $tetel['menavkod']);

                                    $szamlatetel = new Bizonylattetel();
                                    $szamlafej->addBizonylattetel($szamlatetel);
                                    $szamlatetel->setBizonylatfej($szamlafej);

                                    $szamlatetel->setPersistentData();
                                    $szamlatetel->setTermek($defatermek);
                                    $szamlatetel->setTermeknev($tetel['termeknev']);
                                    $szamlatetel->setCikkszam($tetel['cikkszam']);
                                    $szamlatetel->setIdegencikkszam($tetel['szallitoicikkszam']);
                                    $szamlatetel->setVtsz($vtsz);
                                    $szamlatetel->setAfa($afa);
                                    $szamlatetel->setMekod($me);
                                    $szamlatetel->setMennyiseg($tetel['mennyiseg']);
                                    $szamlatetel->setNettoegysar($tetel['nettoegysar']);
                                    $szamlatetel->setNettoegysarhuf($szamlatetel->getNettoegysar() * $szamlatetel->getArfolyam());
                                    $szamlatetel->calc();

                                    $this->getEm()->persist($szamlatetel);
                                }
                                $szamlafej->calcOsszesen();
                                $this->getEm()->flush();

                                $results['success'] = 1;
                                $results['szamlaszam'] = $szamlafej->getId();
                                $results['pdfurl'] = \mkw\store::getRouter()->generate('szamlapdf', true, [], ['id' => $szamlafej->getId(), 'printed' => true]);
                            }
                        }
                        $this->writelog($consumer, $rawdata, json_encode($results));
                        break;
                    case 'megrendeles':
                        if (array_key_exists('create', $cmd)) {
                            $results['success'] = 0;
                            $results['msg'] = '';

                            $data = $cmd['create'];

                            /** @var Bizonylattipus $biztipus */
                            $biztipus = $this->getRepo(Bizonylattipus::class)->find('megrendeles');
                            if (!$biztipus) {
                                $results['msg'] .= ' Nincs megrendelés biz.tipus.';
                            }
                            /** @var Valutanem $valutanem */
                            $valutanem = $this->getRepo(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
                            if (!$valutanem) {
                                $results['msg'] .= ' Nincs valutanem.';
                            }
                            /** @var Partner $partner */
                            $partner = $this->getRepo(partner::class)->find($data['partner_id']);
                            if (!$partner) {
                                $results['msg'] .= ' Ismeretlen partner.';
                            }
                            foreach ($data['tetelek'] as $tetel) {
                                /** @var Termek $termek */
                                $termek = $this->getRepo(Termek::class)->find($tetel['termek_id']);
                                if (!$termek) {
                                    $result['msg'] .= ' ' . $tetel['termek_id'] . ' ismeretlen termék.';
                                } else {
                                    /** @var TermekValtozat $termekvaltozat */
                                    $termekvaltozat = $this->getRepo(TermekValtozat::class)->find($tetel['termekvaltozat_id']);
                                    if (!$termekvaltozat || $termekvaltozat->getTermek()?->getId() !== $termek->getId()) {
                                        $result['msg'] .= ' ' . $tetel['termekvaltozat_id'] . ' ismeretlen termékváltozat.';
                                    }
                                }
                            }

                            if ($results['msg'] === '') {
                                $fizmod = $this->createFizmod($data['fizmodnev'], $data['fizmodtipus'], $data['fizmodnavkod']);

                                // szallmod !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

                                $bizfej = new Bizonylatfej();
                                $bizfej->setPersistentData();

                                $bizfej->setBizonylattipus($biztipus);
                                $bizfej->setPartner($partner);

                                $bizfej->setRaktar($raktar);
                                $bizfej->setValutanem($valutanem);
                                $bizfej->setBankszamla($valutanem->getBankszamla());
                                $bizfej->setArfolyam(1);
                                $bizfej->setFizmod($fizmod);

                                $bizfej->setKelt();
                                $bizfej->setTeljesites();
                                $bizfej->setEsedekesseg();

                                $bizfej->setMegjegyzes($data['megjegyzes']);
                                $bizfej->setBelsomegjegyzes($data['idegenbizszam']);
                                $this->getEm()->persist($bizfej);

                                foreach ($data['tetelek'] as $tetel) {
                                    $biztetel = new Bizonylattetel();
                                    $bizfej->addBizonylattetel($biztetel);
                                    $biztetel->setBizonylatfej($bizfej);

                                    $biztetel->setPersistentData();
                                    $termek = $this->getRepo(Termek::class)->find($tetel['termek_id']);
                                    if ($termek) {
                                        $termekvaltozat = $this->getRepo(TermekValtozat::class)->find($tetel['termekvaltozat_id']);
                                        if ($termekvaltozat) {
                                            $biztetel->setTermek($termek);
                                            $biztetel->setTermekvaltozat($termekvaltozat);
                                            $biztetel->setMennyiseg($tetel['mennyiseg']);
                                            $biztetel->setBruttoegysar($tetel['bruttoegysar']);
                                            $biztetel->setBruttoegysarhuf($biztetel->getBruttoegysar() * $biztetel->getArfolyam());
                                            $biztetel->calc();

                                            $this->getEm()->persist($biztetel);
                                        }
                                    }
                                }
                                $bizfej->calcOsszesen();
                                $this->getEm()->flush();

                                $results['success'] = 1;
                                $results['bizonylatszam'] = $bizfej->getId();
                            }
                        }
                        $this->writelog($consumer, $rawdata, json_encode($results));
                        break;
                    case 'b2border':
                        if (array_key_exists('create', $cmd)) {
                            $results['success'] = 0;
                            $results['msg'] = '';

                            $data = $cmd['create'];

                            /** @var Bizonylattipus $biztipus */
                            $biztipus = $this->getRepo(Bizonylattipus::class)->find('megrendeles');
                            if (!$biztipus) {
                                $results['msg'] .= ' "Order" type not found.';
                            }
                            $bizstatusz = $this->getRepo(Bizonylatstatusz::class)->find(12); // függőben
                            /** @var Partner $partner */
                            $partner = $consumer->getPartner();
                            if (!$partner) {
                                $results['msg'] .= ' Unknown partner.';
                            }
                            $afa = $this->getRepo(Afa::class)->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
                            $nullasafa = $afa->getId();
                            $nullasafakulcs = $afa->getErtek();

                            foreach ($data['products'] as $tetel) {
                                /** @var Termek $termek */
                                $termek = $this->getRepo(Termek::class)->find($tetel['product_id']);
                                if (!$termek) {
                                    $result['msg'] .= ' ' . $tetel['product_id'] . ' unknown product.';
                                } else {
                                    /** @var TermekValtozat $termekvaltozat */
                                    $termekvaltozat = $this->getRepo(TermekValtozat::class)->find($tetel['productvariation_id']);
                                    if (!$termekvaltozat || $termekvaltozat->getTermek()?->getId() !== $termek->getId()) {
                                        $result['msg'] .= ' ' . $tetel['productvariation_id'] . ' unknown product variation.';
                                    }
                                }
                            }

                            if ($results['msg'] === '') {
                                $bizfej = new Bizonylatfej();
                                $bizfej->setPersistentData();
                                $bizfej->setBizonylattipus($biztipus);
                                $bizfej->setKelt();
                                $bizfej->setTeljesites();
                                $bizfej->setEsedekesseg();
                                $bizfej->setKellszallitasikoltsegetszamolni(false);
                                $bizfej->dontUploadToWC = true;

                                $bizfej->setPartner($partner);
                                $bizfej->setRaktar($raktar);
                                $bizfej->setSzallitasimod($partner->getSzallitasimod());

                                $arf = $this->getEm()->getRepository(Arfolyam::class)->getActualArfolyam($partner->getValutanem(), $bizfej->getTeljesites());
                                $bizfej->setArfolyam($arf->getArfolyam());

                                $bizfej->setBizonylatstatusz($bizstatusz);
                                $bizfej->setBelsomegjegyzes($data['id']);
                                $bizfej->setMegjegyzes($data['notes']);

                                $vantetel = false;

                                foreach ($data['tetelek'] as $tetel) {
                                    $tv = $this->getRepo(TermekValtozat::class)->find($tetel['productvariation_id']);

                                    $biztetel = new Bizonylattetel();
                                    $bizfej->addBizonylattetel($biztetel);
                                    $biztetel->setBizonylatfej($bizfej);

                                    $biztetel->setPersistentData();
                                    $biztetel->setTermek($tv->getTermek());
                                    $biztetel->setTermekvaltozat($tv);
                                    if ($partner->getSzamlatipus() > 0) {
                                        $biztetel->setAfa($nullasafa);
                                        $biztetel->setAfakulcs($nullasafakulcs);
                                    }

                                    $biztetel->setMennyiseg($tetel['quantity']);
                                    $biztetel->setNettoegysar($tv->getTermek()->getNettoAr($tv, $partner));
                                    $biztetel->setNettoegysarhuf($biztetel->getNettoegysar() * $biztetel->getArfolyam());
                                    $biztetel->calc();
                                    $this->getEm()->persist($biztetel);
                                    $vantetel = true;
                                }
                                if ($vantetel) {
                                    $bizfej->calcOsszesen();
                                    $this->getEm()->persist($bizfej);
                                    $this->getEm()->flush();
                                } else {
                                    $this->getEm()->clear();
                                }

                                $results['success'] = 1;
                                $results['order_id'] = $bizfej->getId();
                            }
                        }
                        $this->writelog($consumer, $rawdata, json_encode($results));
                        break;
                    case 'getnaveredmenyriasztas':
                        $bizc = new bizonylatfejController(null);
                        $bizcnt = $bizc->calcNavEredmenyRiasztas();
                        $results['abortedcnt'] = $bizcnt['aborted'];
                        $results['bekuldetlencnt'] = $bizcnt['null'];
                        break;
                }
            }
        } else {
            $result['auth'] = 0;
            $result['authmsg'] = 'API authentication failed: ' . $auth['name'] . '/' . $auth['key'] . '|';
            $this->writelog($consumer, $rawdata, json_encode($result));
        }

        $result['results'] = $results;
        echo json_encode($result);
    }
}