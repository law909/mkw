<?php

namespace Controllers;

use Carbon\Carbon;
use Entities\Arsav;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Dolgozo;
use Entities\Emailtemplate;
use Entities\Fizmod;
use Entities\JogaBejelentkezes;
use Entities\Idopont;
use Entities\Idopontfoglalas;
use Entities\JogaBerlet;
use Entities\Orarend;
use Entities\Orarendhelyettesites;
use Entities\Partner;
use Entities\Raktar;
use Entities\Szallitasimod;
use Entities\Termek;
use Entities\Valutanem;
use mkw\store;
use mkwhelpers, Entities;

class pubadminController extends mkwhelpers\Controller
{

    /** ennyi karakter alatt nem keresünk partnert (a select2 minimumInputLength párja) */
    private const PARTNERKERESES_MINHOSSZ = 3;

    public function view()
    {
        $view = $this->createPubAdminView('main.tpl');
        $view->setVar('pagetitle', t('Főoldal'));
        $view->setVar('tanarnev', \mkw\store::getPubAdminSession()->loggedinuser['name']);

        $view->printTemplateResult();
    }

    public function getOralist()
    {
        $view = $this->createPubAdminView('oralist.tpl');

        $dolgozo = $this->getRepo(Dolgozo::class)->find(\mkw\store::getPubAdminSession()->pk);
        if ($dolgozo) {
            $datum = $this->params->getStringRequestParam('datum');
            $napszam = new \DateTime($datum);
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('dolgozo', '=', $dolgozo);
            $filter->addFilter('nap', '=', $napszam->format('N'));
            $filter->addFilter('inaktiv', '=', false);
            $orak = $this->getRepo(Orarend::class)->getAll($filter);
            $oralista = [];
            /** @var Orarend $ora */
            foreach ($orak as $ora) {
                $oralista[] = [
                    'id' => $ora->getId(),
                    'nev' => $ora->getKezdetStr() . ' ' . $ora->getNev() . ' (' . $ora->getNapNev() . ')'
                ];
            }
            $filter->clear();
            $filter->addFilter('helyettesito', '=', $dolgozo);
            $filter->addFilter('datum', '=', $datum);
            $filter->addFilter('inaktiv', '=', false);
            $helyettek = $this->getRepo(Orarendhelyettesites::class)->getAll($filter);
            /** @var Orarendhelyettesites $helyett */
            foreach ($helyettek as $helyett) {
                $oralista[] = [
                    'id' => $helyett->getOrarendId(),
                    'nev' => $helyett->getOrarendNev()
                ];
            }
            $view->setVar('oralista', $oralista);
        }
        $view->printTemplateResult();
    }

    public function getResztvevolist()
    {
        $resztvevolista = [];
        $oraid = $this->params->getIntRequestParam('oraid');
        $datum = $this->params->getStringRequestParam('datum');
        $ma = new Carbon();
        $datumdate = Carbon::createFromFormat(\mkw\store::$SQLDateFormat, $datum);
        // az óra azonosítója a kérésből jön: csak a saját óránk résztvevői láthatók
        $ora = $this->getSajatOra($oraid, $datum);

        if ($ora) {
            /** @var Termek $orajegytermek */
            $orajegytermek = $this->getRepo(Termek::class)->find(\mkw\store::getParameter(\mkw\consts::JogaOrajegyTermek));
            /** @var Termek $berlet4termek */
            $berlet4termek = $this->getRepo(Termek::class)->find(\mkw\store::getParameter(\mkw\consts::JogaBerlet4Termek));
            /** @var Termek $berlet10termek */
            $berlet10termek = $this->getRepo(Termek::class)->find(\mkw\store::getParameter(\mkw\consts::JogaBerlet10Termek));


            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('orarend', '=', $ora);
            $filter->addFilter('datum', '=', $datum);
            $resztvevok = $this->getRepo(JogaBejelentkezes::class)->getAll($filter, ['partnernev' => 'ASC']);

            /** @var JogaBejelentkezes $resztvevo */
            foreach ($resztvevok as $resztvevo) {
                $rvtomb = [];
                $rvtomb['tipus'] = false;
                $rvpartner = $this->getRepo(Partner::class)->findOneBy(['email' => $resztvevo->getPartneremail()]);
                if ($rvpartner) {
                    $rvtomb['nev'] = $resztvevo->getPartnernev();
                    $rvtomb['email'] = $resztvevo->getPartneremail();
                    $rvtomb['new'] = false;
                    $filter->clear();
                    $filter->addFilter('partner', '=', $rvpartner);
                    $filter->addFilter('lejart', '=', false);
                    $filter->addSql('(_xx.lejaratdatum>=CURDATE())');
                    $berletek = $this->getRepo(JogaBerlet::class)->getAll($filter, ['id' => 'ASC']);
                    if (count($berletek)) {
                        /** @var JogaBerlet $berlet */
                        $berlet = $berletek[0];
                        $rvtomb['tipus'] = 'berlet';
                        $rvtomb['alkalom'] = $berlet->getAlkalom();
                        $rvtomb['elfogyottalkalom'] = $berlet->getElfogyottalkalom() + $berlet->getOfflineelfogyottalkalom();
                        $rvtomb['lejaratdatum'] = $berlet->getLejaratdatumStr();
                    }
                } else {
                    $rvtomb['nev'] = $resztvevo->getPartnernev();
                    $rvtomb['email'] = $resztvevo->getPartneremail();
                    $rvtomb['new'] = true;
                }
                switch (true) {
                    case $resztvevo->getTipus() == 1:
                        $rvtomb['tipus'] = 'orajegy';
                        break;
                }
                $rvtomb['id'] = $resztvevo->getId();
                $rvtomb['megjegyzes'] = $resztvevo->getMegjegyzes();
                $rvtomb['megjelent'] = $resztvevo->isMegjelent();
                $rvtomb['mustbuy'] = !$rvtomb['tipus'];
                $rvtomb['online'] = $resztvevo->getOnline();
                $rvtomb['lemondva'] = $resztvevo->isLemondva();
                /** @var Termek $termek */
                $termek = $this->getRepo(Termek::class)->find(\mkw\store::getParameter(\mkw\consts::JogaOrajegyTermek));
                $arsav = $this->getEm()->getRepository(Arsav::class)->findOneBy(['nev' => 'normál']);
                if ($termek) {
                    $rvtomb['type1price'] = $termek->getBruttoArByArsav(null, $arsav);
                    $rvtomb['type1name'] = $termek->getNev();
                }
                $termek = $this->getRepo(Termek::class)->find(\mkw\store::getParameter(\mkw\consts::JogaBerlet4Termek));
                if ($termek) {
                    $rvtomb['type2price'] = $termek->getBruttoArByArsav(null, $arsav);
                    $rvtomb['type2name'] = $termek->getNev();
                }
                $termek = $this->getRepo(Termek::class)->find(\mkw\store::getParameter(\mkw\consts::JogaBerlet10Termek));
                if ($termek) {
                    $rvtomb['type3price'] = $termek->getBruttoArByArsav(null, $arsav);
                    $rvtomb['type3name'] = $termek->getNev();
                }
                $resztvevolista[] = $rvtomb;
            }
        }
        $view = $this->createPubAdminView('resztvevolist.tpl');
        $view->setVar('resztvevolist', $resztvevolista);
        $view->setVar('future', $ma->lessThan($datumdate));
        if ($ora) {
            $view->setVar('oraid', $oraid);
            $view->setVar('oradatum', $datum);
            $view->setVar('lemondhato', $ora->getLemondhato());
        }
        $view->printTemplateResult();
    }

    /**
     * A tanár aznapi időpontjai (a jóga órák mellett). Az ismétlődő időpont minden héten
     * ugyanarra a napra esik, az egyszeri a saját dátumára – ezt az Idopont dönti el.
     */
    public function getIdopontlist()
    {
        $view = $this->createPubAdminView('idopontlist.tpl');
        $idopontlista = [];

        $dolgozo = $this->getBejelentkezettTanar();
        $datum = $this->datumParam();
        if ($dolgozo && $datum) {
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('dolgozo', '=', $dolgozo);
            $filter->addFilter('inaktiv', '=', false);
            /** @var Idopont $idopont */
            foreach ($this->getRepo(Idopont::class)->getAll($filter) as $idopont) {
                if ($idopont->isValidOccurrenceDate($datum)) {
                    $idopontlista[] = [
                        'id' => $idopont->getId(),
                        'nev' => trim($idopont->getIdotartamStr() . ' ' . $idopont->getIdoponttemaNev()),
                    ];
                }
            }
            usort($idopontlista, fn($a, $b) => strcmp($a['nev'], $b['nev']));
        }
        $view->setVar('idopontlista', $idopontlista);
        $view->printTemplateResult();
    }

    /**
     * Egy időpont adott napi foglalói – a jóga óra résztvevőlistájának mintájára, azzal a
     * különbséggel, hogy itt csak a megérkezés jelölhető.
     */
    public function getIdopontfoglalaslist()
    {
        $view = $this->createPubAdminView('idopontfoglalaslist.tpl');
        $foglalaslista = [];

        // az időpont azonosítója a kérésből jön: csak a saját időpontunk foglalói láthatók
        $idopont = $this->getSajatIdopont($this->params->getIntRequestParam('idopontid'));
        $datum = $this->datumParam();
        if ($idopont && $datum) {
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('idopont', '=', $idopont);
            $filter->addFilter('datum', '=', $datum->format(\mkw\store::$SQLDateFormat));
            /** @var Idopontfoglalas $foglalas */
            foreach ($this->getRepo(Idopontfoglalas::class)->getAll($filter, ['id' => 'ASC']) as $foglalas) {
                $foglalaslista[] = [
                    'id' => $foglalas->getId(),
                    'nev' => $foglalas->getPartnerNev(),
                    'email' => $foglalas->getPartnerEmail(),
                    'telefon' => $foglalas->getPartnerTelefon(),
                    'online' => $foglalas->isOnline(),
                    'fizetve' => $foglalas->getFizetve(),
                    'lemondva' => $foglalas->getLemondva(),
                    'megjelent' => $foglalas->isMegjelent(),
                ];
            }
        }
        $view->setVar('foglalaslist', $foglalaslista);
        $view->printTemplateResult();
    }

    /**
     * A megérkezés jelölése egy időpont-foglaláson. Csak a saját időpontunk foglalásán – a
     * jelölés a tanárelszámolásra is kihat.
     */
    public function setIdopontfoglalasMegjelent()
    {
        /** @var Idopontfoglalas $foglalas */
        $foglalas = $this->getRepo(Idopontfoglalas::class)->find($this->params->getIntRequestParam('id'));
        if ($foglalas && $this->getSajatIdopont($foglalas->getIdopontId())) {
            $foglalas->setMegjelent(!$foglalas->isMegjelent());
            $this->getEm()->persist($foglalas);
            $this->getEm()->flush();
        }
    }

    /** @return Dolgozo|null */
    private function getBejelentkezettTanar()
    {
        $pk = \mkw\store::getPubAdminSession()->pk;
        return $pk ? $this->getRepo(Dolgozo::class)->find($pk) : null;
    }

    /**
     * A kérésben kapott óra, DE csak akkor, ha a bejelentkezett tanáré: vagy az övé az órarendi
     * sor, vagy őt jelölték be helyettesnek arra a napra (ugyanaz a két forrás, amiből a
     * {@see getOralist()} az óralistát összerakja).
     *
     * A helyettesítés egyetlen napra szól, ezért ott a dátum is számít; a saját órájához a tanár
     * a nap megadása nélkül is hozzáfér.
     *
     * @return Orarend|null
     */
    private function getSajatOra($oraid, $datum = null)
    {
        $dolgozo = $this->getBejelentkezettTanar();
        if (!$dolgozo || !$oraid) {
            return null;
        }
        /** @var Orarend|null $ora */
        $ora = $this->getRepo(Orarend::class)->find($oraid);
        if (!$ora) {
            return null;
        }
        if ((int)$ora->getDolgozoId() === (int)$dolgozo->getId()) {
            return $ora;
        }
        if ($datum) {
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('orarend', '=', $ora);
            $filter->addFilter('helyettesito', '=', $dolgozo);
            $filter->addFilter('datum', '=', $datum);
            $filter->addFilter('inaktiv', '=', false);
            if ($this->getRepo(Orarendhelyettesites::class)->getCount($filter)) {
                return $ora;
            }
        }
        return null;
    }

    /**
     * A kérésben kapott bejelentkezés, csak a saját óránkról. A napot a bejelentkezés maga adja,
     * nem a kérés – így a helyettesítés napja sem téveszthető meg.
     *
     * @return JogaBejelentkezes|null
     */
    private function getSajatBejelentkezes($id)
    {
        /** @var JogaBejelentkezes|null $rv */
        $rv = $id ? $this->getRepo(JogaBejelentkezes::class)->find($id) : null;
        if (!$rv || !$this->getSajatOra($rv->getOrarendId(), $rv->getDatum())) {
            return null;
        }
        return $rv;
    }

    /**
     * A kérésben kapott időpont, DE csak akkor, ha a bejelentkezett tanáré. Az azonosító a
     * böngészőből jön, más tanár időpontjának foglalói pedig se nem láthatók, se nem jelölhetők.
     *
     * @return Idopont|null
     */
    private function getSajatIdopont($idopontid)
    {
        $dolgozo = $this->getBejelentkezettTanar();
        if (!$dolgozo || !$idopontid) {
            return null;
        }
        /** @var Idopont|null $idopont */
        $idopont = $this->getRepo(Idopont::class)->find($idopontid);
        if (!$idopont || ((int)$idopont->getDolgozoId() !== (int)$dolgozo->getId())) {
            return null;
        }
        return $idopont;
    }

    /** @return \DateTime|null */
    private function datumParam()
    {
        $datum = trim($this->params->getStringRequestParam('datum'));
        if ($datum === '') {
            return null;
        }
        try {
            return new \DateTime($datum);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setResztvevoMegjelent()
    {
        /** @var JogaBejelentkezes $rv */
        $online = $this->params->getIntRequestParam('online');
        $rv = $this->getSajatBejelentkezes($this->params->getIntRequestParam('id'));
        if ($rv) {
            $megje = $rv->isMegjelent();
            $rv->setMegjelent(!$rv->isMegjelent());
            if (!$rv->isMegjelent()) {
                $rv->setOnline(0);
            } else {
                $rv->setOnline($online);
            }
            $this->getEm()->persist($rv);
            $this->getEm()->flush();
            if ($megje) {
                $rv->delJogaReszvetel();
            } else {
                $rv->createJogaReszvetel();
            }
        }
    }

    public function setResztvevoOrajegy()
    {
        $type = $this->params->getIntRequestParam('type');
        $price = $this->params->getNumRequestParam('price');
        $later = $this->params->getBoolRequestParam('later');
        /** @var JogaBejelentkezes $rv */
        $rv = $this->getSajatBejelentkezes($this->params->getIntRequestParam('id'));
        if ($rv) {
            $rv->setTipus($type);
            $rv->setAr($price);
            $rv->setKesobbfizet($later);
            $this->getEm()->persist($rv);
            $this->getEm()->flush();

            $rvpartner = $this->getRepo(Partner::class)->findOneBy(['email' => $rv->getPartneremail()]);
            if (!$rvpartner) {
                $rvpartner = new Partner();
                $rvpartner->setEmail($rv->getPartneremail());
                $rvpartner->setNev($rv->getPartnernev());
                $rvpartner->setVezeteknev($rv->getPartnerVezeteknev());
                $rvpartner->setKeresztnev($rv->getPartnerKeresztnev());
                $rvpartner->setSzamlatipus(0);
                $rvpartner->setVatstatus(2);
                \mkw\store::getEm()->persist($rvpartner);
                \mkw\store::getEm()->flush();
            }

            $tipusnev = 'órajegy';
            if ($type === 2 || $type === 3) {
                $berlet = new JogaBerlet();
                $berlet->setPartner($rvpartner);
                switch ($type) {
                    case 2:
                        /** @var Termek $termek */
                        $termek = $this->getRepo(Termek::class)->find(\mkw\store::getParameter(\mkw\consts::JogaBerlet4Termek));
                        break;
                    case 3:
                        $termek = $this->getRepo(Termek::class)->find(\mkw\store::getParameter(\mkw\consts::JogaBerlet10Termek));
                        break;
                }
                $tipusnev = $termek->getNev();
                $berlet->setTermek($termek);
                $berlet->setBruttoegysar($price);
                $berlet->setVasarlasnapja();
                $berlet->setNincsfizetve($rv->isKesobbfizet());
                $this->getEm()->persist($berlet);
                $this->getEm()->flush();
            } elseif ($type === 1) {
                $termek = $this->getRepo(Termek::class)->find(\mkw\store::getParameter(\mkw\consts::JogaOrajegyTermek));
            }
            if ($rv->getOrarend()->getDolgozo()->isAutoszamla()) {
                $tulajkontaktemail = \mkw\store::getParameter(\mkw\consts::TulajKontaktEmail);
                if (!\mkw\store::csinalhatUjSzamlat() && $tulajkontaktemail) {
                    $subject = 'Beküldetlen számla miatt nem készül új számla a pubadminból';
                    $body = 'A pubadminban valaki épp órajegyet vagy bérletet vett, de nem készül automatikusan számla, mert van beküldetlen számla. Küldd be minél előbb, utána számlázd ki ami elmaradt!';

                    $mailer = \mkw\store::getMailer();

                    $mailer->addTo($tulajkontaktemail);
                    $mailer->setSubject($subject);
                    $mailer->setMessage($body);
                    $mailer->send();

                    return;
                }
                /** @var Bizonylattipus $biztipus */
                $biztipus = $this->getRepo(Bizonylattipus::class)->find('szamla');
                /** @var Valutanem $valutanem */
                $valutanem = $this->getRepo(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem));

                $szamlafej = new Bizonylatfej();
                //$szamlafej->setKellszallitasikoltsegetszamolni(false);
                $szamlafej->setPersistentData();

                $szamlafej->setBizonylattipus($biztipus);
                $szamlafej->setPartner($rvpartner);
                if ($rvpartner->getSzallitasimod()) {
                    $szamlafej->setSzallitasimod($rvpartner->getSzallitasimod());
                } else {
                    $szamlafej->setSzallitasimod($this->getRepo(Szallitasimod::class)->find(\mkw\store::getParameter(\mkw\consts::Szallitasimod)));;
                }
                if (!$szamlafej->getPartnervatstatus()) {
                    $szamlafej->setPartnervatstatus(2);
                }
                if (!$szamlafej->getPartnerSzamlatipus()) {
                    $szamlafej->setPartnerSzamlatipus(0);
                }

                $szamlafej->setRaktar($this->getRepo(Raktar::class)->find(\mkw\store::getDefaultRaktarId()));
                $szamlafej->setValutanem($valutanem);
                $szamlafej->setBankszamla($valutanem->getBankszamla());
                $szamlafej->setArfolyam(1);
                if ($later) {
                    $szamlafej->setFizmod($this->getRepo(Fizmod::class)->find(\mkw\store::getParameter(\mkw\consts::Fizmod)));
                } else {
                    $szamlafej->setFizmod($this->getRepo(Fizmod::class)->find(\mkw\store::getParameter(\mkw\consts::KeszpenzFizmod)));
                }
                $szamlafej->setKelt();
                $szamlafej->setTeljesites();
                $szamlafej->setEsedekesseg(\mkw\store::calcEsedekesseg($szamlafej->getKelt(), $szamlafej->getFizmod(), $szamlafej->getPartner()));
                $szamlafej->setBelsomegjegyzes('Automata számla pubadminból');

                $szamlatetel = new Bizonylattetel();
                $szamlafej->addBizonylattetel($szamlatetel);
                $szamlatetel->setBizonylatfej($szamlafej);

                $szamlatetel->setPersistentData();
                $szamlatetel->setTermek($termek);
                $szamlatetel->setMennyiseg(1);
                $szamlatetel->setBruttoegysar($price);
                $szamlatetel->setBruttoegysarhuf($szamlatetel->getBruttoegysar() * $szamlatetel->getArfolyam());
                $szamlatetel->calc();
                if ($berlet) {
                    $szamlatetel->setJogaberlet($berlet);
                    $berlet->setSzamlazva(true);
                    $this->getEm()->persist($berlet);
                }

                $this->getEm()->persist($szamlatetel);
                $szamlafej->calcOsszesen();
                $this->getEm()->persist($szamlafej);
                $this->getEm()->flush();

                $email = $szamlafej->getPartneremail();
                if (\mkw\store::isSendableEmail($email)) {
                    $emailtpl = $this->getRepo(Emailtemplate::class)->find(\mkw\store::getParameter(\mkw\consts::JogaBerletSzamlazvaSablon));

                    $pdf = (new \Services\BizonylatPrintService())->createEngine($szamlafej->getId());
                    $filepath = \mkw\store::storagePath(\mkw\store::urlize($szamlafej->getId()) . '.pdf');
                    $pdf?->saveAs($filepath);

                    $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                    $body = \mkw\store::getTemplateFactory()->createMainView(
                        'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
                    );
                    $body->setVar('szamla', $szamlafej->toLista());
                    $body->setVar('megszolitas', $szamlafej->getPartner()->getSzamlalevelmegszolitas());
                    $body->setVar('partnernev', $szamlafej->getPartnernev());
                    $body->setVar('datum', date(\mkw\store::$DateFormat));
                    $body->setVar('berlet', $tipusnev);
                    $body->setVar('ar', $price);

                    $mailer = \mkw\store::getMailer();

                    $mailer->setAttachment($filepath);
                    $mailer->addTo($email);
                    $mailer->setSubject($subject->getTemplateResult());
                    $mailer->setMessage($body->getTemplateResult());

                    $mailer->send();

                    $bfcontroller->setNyomtatva($szamlafej->getId(), true);

                    \unlink($filepath);
                }
            } else {
                if ($rv->isKesobbfizet()) {
                    $sablon = \mkw\store::getParameter(\mkw\consts::JogaBerletFelszolitoSablon);
                } else {
                    $sablon = \mkw\store::getParameter(\mkw\consts::JogaBerletKoszonoSablon);
                }
                if ($berlet) {
                    $berlet->sendEmail($sablon);
                } elseif (\mkw\store::isSendableEmail($rv->getPartneremail())) {
                    $emailtpl = $this->getRepo(Emailtemplate::class)->find($sablon);
                    if ($emailtpl) {
                        $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                        $body = \mkw\store::getTemplateFactory()->createMainView(
                            'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
                        );
                        $body->setVar('partnernev', $rv->getPartnernev());
                        $body->setVar('datum', date(\mkw\store::$DateFormat));
                        $body->setVar('berlet', $tipusnev);
                        $body->setVar('ar', $price);

                        $mailer = \mkw\store::getMailer();

                        $mailer->addTo($rv->getPartneremail());
                        $mailer->setSubject($subject->getTemplateResult());
                        $mailer->setMessage($body->getTemplateResult());
                        $mailer->send();
                    }
                }
            }
        }
    }

    /**
     * Partnerkereső az „új gyakorló" ablakhoz. A minimális hossz eddig csak a böngészőben volt
     * feltétel (select2 minimumInputLength): üres kereséssel az egész partnertörzs kijött egy
     * kérésre, névvel és emaillel együtt.
     */
    public function getPartnerData()
    {
        $result = [];
        $q = trim($this->params->getStringRequestParam('q'));
        if (mb_strlen($q) < self::PARTNERKERESES_MINHOSSZ) {
            header('Content-Type: application/json');
            echo json_encode(['results' => $result]);
            return;
        }
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter(['nev', 'keresztnev', 'vezeteknev'], 'like', '%' . $q . '%');
        $partnerek = $this->getRepo(Partner::class)->getAll($filter, ['nev' => 'ASC']);
        /** @var Partner $partner */
        foreach ($partnerek as $partner) {
            $result[] = [
                'id' => $partner->getId(),
                'text' => $partner->getNev() . ' (' . $partner->getEmail() . ')'
            ];
        }
        header('Content-Type: application/json');
        echo json_encode(['results' => $result]);
    }

    public function newBejelentkezes()
    {
        $oraid = $this->params->getIntRequestParam('oraid');
        $datum = $this->params->getStringRequestParam('datum');
        $ora = $this->getSajatOra($oraid, $datum);
        $partnerid = $this->params->getIntRequestParam('partnerid');
        /** @var Partner $partner */
        $partner = $this->getRepo(Partner::class)->find($partnerid);
        if ($partner && $ora) {
            $obj = new JogaBejelentkezes();
            $obj->setDatum($datum);
            $obj->setPartnernev($partner->getNev());
            $obj->setPartneremail($partner->getEmail());
            $obj->setOrarend($ora);
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    public function newBejelentkezesWNewPartner()
    {
        $oraid = $this->params->getIntRequestParam('oraid');
        $datum = $this->params->getStringRequestParam('datum');
        $ora = $this->getSajatOra($oraid, $datum);
        $nev = $this->params->getStringRequestParam('nev');
        $email = $this->params->getStringRequestParam('email');
        if ($ora && $nev && $email) {
            $obj = new JogaBejelentkezes();
            $obj->setDatum($datum);
            $obj->setPartnernev($nev);
            $obj->setPartneremail($email);
            $obj->setOrarend($ora);
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    public function getMegjegyzes()
    {
        $id = $this->params->getIntRequestParam('id');
        /** @var JogaBejelentkezes $rv */
        $rv = $this->getSajatBejelentkezes($id);
        if ($rv) {
            echo $rv->getMegjegyzes();
        }
    }

    public function postMegjegyzes()
    {
        $id = $this->params->getIntRequestParam('id');
        $m = $this->params->getStringRequestParam('megjegyzes');
        /** @var JogaBejelentkezes $rv */
        $rv = $this->getSajatBejelentkezes($id);
        if ($rv) {
            $rv->setMegjegyzes($m);
            $this->getEm()->persist($rv);
            $this->getEm()->flush();
        }
    }

    public function getPartner()
    {
        $id = $this->params->getIntRequestParam('id');
        $r = [
            'nev' => '',
            'email' => ''
        ];
        /** @var JogaBejelentkezes $rv */
        $rv = $this->getSajatBejelentkezes($id);
        if ($rv) {
            $r['nev'] = $rv->getPartnernev();
            $r['email'] = $rv->getPartneremail();
        }
        header('Content-Type: application/json');
        echo json_encode($r);
    }

    public function postPartner()
    {
        $id = $this->params->getIntRequestParam('id');
        $nev = $this->params->getStringRequestParam('nev');
        $email = $this->params->getStringRequestParam('email');
        /** @var JogaBejelentkezes $rv */
        $rv = $this->getSajatBejelentkezes($id);
        if ($rv) {
            $rv->setPartnernev($nev);
            $rv->setPartneremail($email);
            $this->getEm()->persist($rv);
            $this->getEm()->flush();
        }
    }

    public function lemondOra()
    {
        $id = $this->params->getIntRequestParam('oraid');
        $datum = $this->params->getStringRequestParam('datum');
        // az óra lemondása levelet küld minden bejelentkezettnek: csak a sajátunkat mondhatjuk le
        $ora = $this->getSajatOra($id, $datum);
        if ($ora) {
            $helyett = new Orarendhelyettesites();
            $helyett->setOrarend($ora);
            $helyett->setDatum($datum);
            $helyett->setElmarad(true);
            $this->getEm()->persist($helyett);
            $this->getEm()->flush();

            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('orarend', '=', $id);
            $filter->addFilter('datum', '=', $datum);
            $resztvevok = $this->getRepo(JogaBejelentkezes::class)->getAll($filter, ['partnernev' => 'ASC']);

            /** @var JogaBejelentkezes $resztvevo */
            foreach ($resztvevok as $resztvevo) {
                $email = $resztvevo->getPartneremail();
                $emailtpl = $this->getRepo(Emailtemplate::class)->find(\mkw\store::getParameter(\mkw\consts::JogaElmaradasErtesitoSablon));
                if ($email && $emailtpl) {
                    $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                    $body = \mkw\store::getTemplateFactory()->createMainView(
                        'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
                    );
                    $body->setVar('oranev', $ora->getJogaoratipusNev());
                    $body->setVar('tanarnev', $ora->getDolgozoNev());
                    $body->setVar('idopont', $ora->getKezdetStr());
                    if ($resztvevo) {
                        $body->setVar('partnerkeresztnev', $resztvevo->getPartnerKeresztnev());
                        $body->setVar('partnervezeteknev', $resztvevo->getPartnerVezeteknev());
                    }
                    $body->setVar('datum', $datum);

                    $mailer = \mkw\store::getMailer();

                    $mailer->addTo($email);
                    $mailer->setSubject($subject->getTemplateResult());
                    $mailer->setMessage($body->getTemplateResult());

                    $mailer->send();
                }
            }
        }
    }
}