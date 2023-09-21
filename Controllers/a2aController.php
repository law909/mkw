<?php

namespace Controllers;

use Entities\Afa;
use Entities\Apiconsumer;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Fizmod;
use Entities\ME;
use Entities\Partner;
use Entities\Raktar;
use Entities\Termek;
use Entities\TermekFa;
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

    protected function gettermek_id($id)
    {
        /** @var Termek $termek */
        $termek = $this->tr->find($id);
        $termekadat = null;
        if ($termek) {
            $termekadat = $termek->toA2a();
        }
        return $termekadat;
    }

    protected function gettermek_ids($ids)
    {
        $ret = [];
        $filter = new FilterDescriptor();
        $filter->addFilter('id', 'IN', $ids);
        $termekek = $this->tr->getWithJoins($filter);
        /** @var Termek $termek */
        foreach ($termekek as $termek) {
            $termekadat = $termek->toA2a();
            $ret[] = $termekadat;
        }
        return $ret;
    }

    protected function gettermek_all()
    {
        $ret = [];
        $termekek = $this->tr->getWithJoins(null);
        /** @var Termek $termek */
        foreach ($termekek as $termek) {
            $termekadat = $termek->toA2a();
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
                    if ($valt->getXElerheto()) {
                        $valtadat = [];
                        $valtadat['id'] = $valt->getId();
                        $keszlet = $valt->getKeszlet() - $valt->getFoglaltMennyiseg();
                        if ($keszlet < 0) {
                            $keszlet = 0;
                        }
                        $valtadat['keszlet'] = $keszlet;
                        $x['valtozatok'][] = $valtadat;
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
                    if ($valt->getXElerheto()) {
                        $valtadat = [];
                        $valtadat['id'] = $valt->getId();
                        $keszlet = $valt->getKeszlet() - $valt->getFoglaltMennyiseg();
                        if ($keszlet < 0) {
                            $keszlet = 0;
                        }
                        $valtadat['keszlet'] = $keszlet;
                        $x['valtozatok'][] = $valtadat;
                    }
                }
            }
            $ret[] = $x;
        }
        return $ret;
    }

    protected function getkategoria_id($id)
    {
        $kat = $this->tkr->find($id);
        $katadat = null;
        if ($kat) {
            $katadat = $kat->toA2a();
        }
        return $katadat;
    }

    protected function getkategoria_idwithchildren($id = null)
    {
        function getChildren($parent)
        {
            $osszesgyerekadat = [];
            $gyerekadat = null;
            $gyerekek = $parent->getChildren();
            foreach ($gyerekek as $gyerek) {
                $gyerekadat = $gyerek->toA2a();
                $gyerekadat['children'] = getChildren($gyerek);
                $osszesgyerekadat[] = $gyerekadat;
            }
            return $osszesgyerekadat;
        }

        $result = [];

        if (is_null($id)) {
            $id = 1;
        }
        $first = $this->tkr->find($id);
        if ($first) {
            $result = $first->toA2a();
            $result['children'] = getChildren($first);
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
        if (!$nev || $nev == 25) {
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

    public function processCmd()
    {
        $pelda = [
            'auth' => [
                'name' => 'superzone.hu',
                'key' => 'xxxx'
            ],
            'cmds' => [
                'hello',
                'gettermek' => [
                    'ids' => [1, 2, 3, 4, 5], // vagy
                    'id' => 1062, // vagy
                    'all' => 1
                ],
                'getkeszlet' => [
                    'ids' => [1, 2, 3, 4, 5], // vagy
                    'id' => 1062
                ],
                'getkategoria' => [
                    'all' => 1, // vagy
                    'id' => 1, // vagy
                    'idwithchildren' => 1
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
                'getnaveredmenyriasztas'
            ]
        ];

        $this->tr = \mkw\store::getEm()->getRepository(Termek::class);
        $this->tkr = \mkw\store::getEm()->getRepository(TermekFa::class);
        $this->pr = \mkw\store::getEm()->getRepository(Partner::class);

        $result = [];
        $results = [];

        $rawdata = $this->params->getOriginalStringRequestParam('data');
        $jsondata = json_decode($rawdata, true);

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
                    case 'gettermek':
                        if (array_key_exists('id', $cmd)) {
                            $results['termek'] = $this->gettermek_id($cmd['id']);
                        } elseif (array_key_exists('ids', $cmd)) {
                            $results['termekek'] = $this->gettermek_ids($cmd['ids']);
                        } elseif (array_key_exists('all', $cmd)) {
                            $results['termekek'] = $this->gettermek_all();
                        }
                        $this->writelog($consumer, $rawdata, json_encode($results));
                        break;
                    case 'getkeszlet':
                        if (array_key_exists('id', $cmd)) {
                            $results['keszlet'] = $this->getkeszlet_id($cmd['id']);
                        } elseif (array_key_exists('ids', $cmd)) {
                            $results['keszletek'] = $this->getkeszlet_ids($cmd['ids']);
                        }
                        $this->writelog($consumer, $rawdata, json_encode($results));
                        break;
                    case 'getkategoria':
                        if (array_key_exists('id', $cmd)) {
                            $results['kategoria'] = $this->getkategoria_id($cmd['id']);
                        } elseif (array_key_exists('idwithchildren', $cmd)) {
                            $results['kategoriak'] = $this->getkategoria_idwithchildren($cmd['idwithchildren']);
                        } elseif (array_key_exists('all', $cmd)) {
                            $results['kategoriak'] = $this->getkategoria_idwithchildren();
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
                                $szamlafej->setPartnerirszam(trim($data['irszam']));
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
                                    $vtsz = $this->createVtsz(trim($tetel['vtsz']), $afa);
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

    public function teszt()
    {
        $this->tkr = \mkw\store::getEm()->getRepository(TermekFa::class);

        $result = [];

        $result['results']['kategoriak'] = $this->getkategoria_idwithchildren();
        echo json_encode($result);
    }
}