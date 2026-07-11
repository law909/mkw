<?php

namespace Controllers;

use Carbon\Carbon;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\Query\ResultSetMapping;
use mkw\store;
use mkwhelpers, Entities;
use Services\BackorderService;

class adminController extends mkwhelpers\Controller
{

    private function checkForIE()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $ub = false;
        if (preg_match('/MSIE/i', $u_agent)) {
            $view = $this->createView('noie.tpl');
            $this->generalDataLoader->loadData($view);
            $view->printTemplateResult();
            $ub = true;
        }
        return $ub;
    }

    public function view()
    {
        $view = $this->createView('main.tpl');
        $this->generalDataLoader->loadData($view);
        $view->setVar('pagetitle', t('Főoldal'));

        $no = new \mkwhelpers\NAVOnline(\mkw\store::getTulajAdoszam(), \mkw\store::getNAVOnlineEnv());
        $no->hello();
        $view->setVar('noerrors', $no->getErrors());
        $view->setVar('noresult', $no->getResult());
        $no->version();
        $view->setVar(
            'noversion',
            \mkw\store::getNAVOnlineEnv()
            . ' v' . \mkw\store::getParameter(\mkw\consts::NAVOnlineVersion)
            . '; értékhatár=' . \mkw\store::getParameter(\mkw\consts::NAVOnlineErtekhatar, 0)
            . '; srv v' . $no->getResult()
        );

        $nohibasbeallitas = [];
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addSql('(_xx.navtipus=\'\') OR (_xx.navtipus IS NULL)');
        $hibasdb = $this->getRepo(\Entities\Fizmod::class)->getCount($filter);
        if ($hibasdb) {
            $nohibasbeallitas[] = 'Nincs minden fizetési módnak NAV típus megadva.';
        }
        $hibasdb = $this->getRepo(\Entities\ME::class)->getCount($filter);
        if ($hibasdb) {
            $nohibasbeallitas[] = 'Nincs minden mennyiségi egységnek NAV típus megadva.';
        }
        $filter->clear();
        $filter->addFilter('ertek', '=', 0);
        $filter->addSql('(_xx.navcase=\'\') OR (_xx.navcase IS NULL)');
        $hibasdb = $this->getRepo(Entities\Afa::class)->getCount($filter);
        if ($hibasdb) {
            $nohibasbeallitas[] = 'Nincs minden 0%-os ÁFA kulcsnak NAV case kiválasztva.';
        }
        $bizc = new bizonylatfejController(null);
        $bizcnt = $bizc->calcNavEredmenyRiasztas();
        if ($bizcnt['aborted'] > 0) {
            $nohibasbeallitas[] = $bizcnt['aborted'] . ' db ABORTED számla van!';
        }
        $view->setVar('nohibalista', $nohibasbeallitas);

        $raktar = new raktarController();
        $raktarid = \mkw\store::getDefaultRaktarId();
        $view->setVar('raktarlist', $raktar->getSelectList($raktarid));

        $lista = new listaController();
        switch (true) {
            case \mkw\store::isMugenrace2026():
            case \mkw\store::isSuperzoneHu():
            case \mkw\store::isSuperzoneB2B():
                $napijelentesdatum = date(\mkw\store::$DateFormat);
                $igdatum = date(\mkw\store::$DateFormat);
                $view->setVar('napijelentes', $lista->napiJelentes($napijelentesdatum, $igdatum));
                $raktarid2 = \mkw\store::getIntParameter(\mkw\consts::Napijelentes2DefaultRaktar, 15);
                $view->setVar('napijelentes2defaultraktar', $raktarid2);
                $view->setVar('napijelentes2', $lista->napiJelentes($napijelentesdatum, $igdatum, $raktarid2));
                $felh = new dolgozoController();
                $view->setVar('felhasznalolist', $felh->getSelectList());
                $apierrorlog = new apierrorlogController();
                $view->setVar('apierrorlog', $apierrorlog->getList());
                break;
            case \mkw\store::isKisszamlazo():
                $view->setVar('lejartkintlevoseg', \mkw\store::getEm()->getRepository(Entities\Folyoszamla::class)->getLejartKintlevosegByValutanem());
                $view->setVar('kintlevoseg', \mkw\store::getEm()->getRepository(Entities\Folyoszamla::class)->getKintlevosegByValutanem());
                break;
            case \mkw\store::isDarshan():
                $partner = new partnerController();
                $view->setVar('partnerlist', $partner->getSelectList());
                $szallitofilter = new \mkwhelpers\FilterDescriptor();
                $szallitofilter->addFilter('szallito', '=', true);
                $view->setVar('szallitolist', $partner->getSelectList(null, $szallitofilter));
                $valutanem = new valutanemController();
                $view->setVar('valutanemlist', $valutanem->getSelectList());
                $penztar = new penztarController();
                $view->setVar('penztarlist', $penztar->getSelectList());
                $bankszamla = new bankszamlaController();
                $view->setVar('bankszamlalist', $bankszamla->getSelectList());
                $jogcim = new jogcimController();
                $view->setVar('jogcimlist', $jogcim->getSelectList());
                $fizmod = new fizmodController();
                $view->setVar('fizmodlist', $fizmod->getSelectList());
                $termek = new termekController();
                $view->setVar('termeklist', $termek->getSelectList());
                $view->setVar('eladhatotermeklist', $termek->getEladhatoSelectList());
                $felh = new dolgozoController();
                $view->setVar('felhasznalolist', $felh->getSelectList());
                $view->setVar('tanarlist', $felh->getSelectList());
                $terem = new jogateremController();
                $view->setVar('jogateremlist', $terem->getSelectList());
                $ot = new jogaoratipusController();
                $view->setVar('jogaoratipuslist', $ot->getSelectList());
                $rendezveny = new rendezvenyController();
                $view->setVar('rendezvenylist', $rendezveny->getSelectList());
                $view->setVar('datumstr', date(\mkw\store::$DateFormat));

                $view->setVar('keltstr', date(\mkw\store::$DateFormat));
                $view->setVar('penztarformaction', \mkw\store::getRouter()->generate('adminpenztarbizonylatfejsave'));
                $view->setVar('eladasformaction', \mkw\store::getRouter()->generate('adminbizonylatfejquickadd'));
                $view->setVar('jogareszvetelformaction', \mkw\store::getRouter()->generate('adminjogareszvetelquicksave'));

                $ma = new \DateTime();
                $ma->sub(new \DateInterval('P1W'));

                $view->setVar('toldatum', $ma->format(\mkw\store::$DateFormat));
                $view->setVar('igdatum', date(\mkw\store::$DateFormat));

                break;
            default:
                break;
        }
        $view->printTemplateResult();
    }

    public function refreshTeljesithetoBackorderek()
    {
        $view = $this->createView('teljesithetobackorderekbody.tpl');
        $backorderSvc = new BackorderService();
        $view->setVar('teljesithetobackorderek', $backorderSvc->getTeljesithetoBackorderLista());
        $view->printTemplateResult();
    }

    public function refreshKintlevoseg()
    {
        $view = $this->createView('kintlevosegbody.tpl');

        $lejart = [];
        $r = $this->getRepo(Entities\Folyoszamla::class)->getLejartKintlevosegByValutanem();
        foreach ($r as $_r) {
            $lejart[$_r['nev']] = $_r;
        }

        if (\mkw\store::isFakeKintlevoseg()) {
            $fake = $this->getRepo(Entities\Folyoszamla::class)->getFakeKintlevosegByValutanem();
            foreach ($fake as $_r) {
                if (array_key_exists($_r['nev'], $lejart)) {
                    $lejart[$_r['nev']]['egyenleg'] += $_r['egyenleg'] * 1;
                } else {
                    $lejart[$_r['nev']] = $_r;
                }
            }
        }
        $view->setVar('lejartkintlevoseg', $lejart);

        $nemlejart = [];
        $r = \mkw\store::getEm()->getRepository(Entities\Folyoszamla::class)->getKintlevosegByValutanem();
        foreach ($r as $_r) {
            $nemlejart[$_r['nev']] = $_r;
        }
        if (\mkw\store::isFakeKintlevoseg()) {
            foreach ($fake as $_r) {
                if (array_key_exists($_r['nev'], $nemlejart)) {
                    $nemlejart[$_r['nev']]['egyenleg'] += $_r['egyenleg'] * 1;
                } else {
                    $nemlejart[$_r['nev']] = $_r;
                }
            }
        }
        $view->setVar('kintlevoseg', $nemlejart);
        $view->printTemplateResult();
    }

    public function refreshSpanyolKintlevoseg()
    {
        $view = $this->createView('spanyolkintlevosegbody.tpl');
        $lejart = [];
        $r = $this->getRepo(Entities\Folyoszamla::class)->getLejartKintlevosegByValutanem([\mkw\store::getParameter(\mkw\consts::SpanyolCimke)]);
        foreach ($r as $_r) {
            $lejart[$_r['nev']] = $_r;
        }
        if (\mkw\store::isFakeKintlevoseg()) {
            $fake = $this->getRepo(Entities\Folyoszamla::class)->getFakeKintlevosegByValutanem([\mkw\store::getParameter(\mkw\consts::SpanyolCimke)]);
            foreach ($fake as $_r) {
                if (array_key_exists($_r['nev'], $lejart)) {
                    $lejart[$_r['nev']]['egyenleg'] += $_r['egyenleg'] * 1;
                } else {
                    $lejart[$_r['nev']] = $_r;
                }
            }
        }
        $view->setVar('spanyollejartkintlevoseg', $lejart);

        $nemlejart = [];
        $r = \mkw\store::getEm()->getRepository(Entities\Folyoszamla::class)->getKintlevosegByValutanem([\mkw\store::getParameter(\mkw\consts::SpanyolCimke)]
        );
        foreach ($r as $_r) {
            $nemlejart[$_r['nev']] = $_r;
        }
        if (\mkw\store::isFakeKintlevoseg()) {
            foreach ($fake as $_r) {
                if (array_key_exists($_r['nev'], $nemlejart)) {
                    $nemlejart[$_r['nev']]['egyenleg'] += $_r['egyenleg'] * 1;
                } else {
                    $nemlejart[$_r['nev']] = $_r;
                }
            }
        }
        $view->setVar('spanyolkintlevoseg', $nemlejart);
        $view->printTemplateResult();
    }

    public function darshanStatisztika()
    {
        $view = $this->createView('statisztika.tpl');
        $tolstr = $this->params->getStringRequestParam('tol');
        $tol = \mkw\store::convDate($tolstr);
        $igstr = $this->params->getStringRequestParam('ig');
        $ig = \mkw\store::convDate($igstr);
        $view->setVar('idoszakvege', $igstr);
        $view->setVar('ma', date(\mkw\store::$DateFormat));

        $partnerrepo = $this->getRepo(Entities\Partner::class);
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('created', '>=', $tol);
        $filter->addFilter('created', '<=', $ig);
        $ujpk = $partnerrepo->getAll($filter, ['created' => 'ASC']);
        $ujpartnerlista = [];
        /** @var \Entities\Partner $ujp */
        foreach ($ujpk as $ujp) {
            $ujpartnerlista[] = [
                'datum' => $ujp->getCreatedStr(),
                'nev' => $ujp->getNev(),
                'createdby' => $ujp->getCreatedbyNev(),
                'email' => $ujp->getEmail()
            ];
        }
        $view->setVar('ujpartnerlista', $ujpartnerlista);
        $view->setVar('ujpartnercount', count($ujpartnerlista));

        $reszvetrepo = $this->getRepo(Entities\JogaReszvetel::class);
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('datum', '>=', $tol);
        $filter->addFilter('datum', '<=', $ig);
        $filter->addFilter('uresterem', '=', false);
        $view->setVar('reszvetelcount', $reszvetrepo->getCount($filter));

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('datum', '>=', $tol);
        $filter->addFilter('datum', '<=', $ig);
        $filter->addFilter('uresterem', '=', true);
        $view->setVar('uresteremcount', $reszvetrepo->getCount($filter));

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('datum', '>=', $tol);
        $filter->addFilter('datum', '<=', $ig);
        $filter->addFilter('online', '=', 1);
        $view->setVar('onlinecount', $reszvetrepo->getCount($filter));

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('datum', '>=', $tol);
        $filter->addFilter('datum', '<=', $ig);
        $filter->addFilter('online', '=', 2);
        $view->setVar('elocount', $reszvetrepo->getCount($filter));

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('datum', '>=', $tol);
        $filter->addFilter('datum', '<=', $ig);
        $filter->addFilter('uresterem', '=', false);
        $filter->addFilter('tisztaznikell', '=', false);
        $rvk = $reszvetrepo->getTermekOsszesito($filter);
        $resztvevolista = [];
        /** @var \Entities\JogaReszvetel $rv */
        foreach ($rvk as $rv) {
            $resztvevolista[] = [
                'db' => $rv['db'],
                'termek' => $rv['nev']
            ];
        }
        $view->setVar('resztvevolista', $resztvevolista);

        // penztar egyenlegek datumig napon
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('kelt', '<=', date(\mkw\store::$SQLDateFormat))
            ->addFilter('rontott', '=', false);
        $penztaregyenleg = $this->getRepo(Entities\Penztarbizonylatfej::class)->getSumByPenztar($filter);
        $view->setVar('penztaregyenlegek', $penztaregyenleg);

        // meg lejaratlan berlet alkalmak szama datumig napon berlet tipus szerint, ertekuk forintban, osszesen db es ertek
        $megfelhasznalhatoberletalk = $this->getRepo(Entities\JogaBerlet::class)->calcMegFelhasznalhato();
        $megfelhasznalhatoberletalk['kifizetendo'] = $megfelhasznalhatoberletalk['ertek'] * \mkw\store::getParameter(\mkw\consts::JogaJutalek) / 100;
        $view->setVar('berletalkalom', $megfelhasznalhatoberletalk);

        // tanari fizetesek, elozo havi vagy aktualis havi pipaval
        $telszidoszak = $this->params->getIntRequestParam('telszidoszak');
        switch ($telszidoszak) {
            case 1:
                $telsztol = Carbon::now()->startOfMonth()->subMonth();
                $telszig = Carbon::now()->startOfMonth()->subMonth()->endOfMonth();
                break;
            case 2:
                $telsztol = Carbon::now()->startOfMonth();
                $telszig = Carbon::now()->endOfMonth();
                break;
        }
        $tec = new tanarelszamolasController();
        $tecres = $tec->getData(null, $telsztol->format(\mkw\store::$DateFormat), $telszig->format(\mkw\store::$DateFormat));

        $tecview = $this->createView('tanarelszamolastanarsum.tpl');

        $tecview->setVar('tetelek', $tecres);
        $tecview->setVar('tol', $telsztol->format(\mkw\store::$DateFormat));
        $tecview->setVar('ig', $telszig->format(\mkw\store::$DateFormat));
        $view->setVar('tanarelszamolas', $tecview->getTemplateResult());
        $view->setVar('telszeleje', $telsztol->format(\mkw\store::$DateFormat));
        $view->setVar('telszvege', $telszig->format(\mkw\store::$DateFormat));

        $view->printTemplateResult();
    }

    public function printNapijelentes()
    {
        $lista = new listaController();
        $datumstr = $this->params->getStringRequestParam('datum');
        $datum = \mkw\store::convDate($datumstr);
        $igdatumstr = $this->params->getStringRequestParam('datumig');
        $igdatum = \mkw\store::convDate($igdatumstr);
        $view = $this->createView('napijelentesbody.tpl');
        $view->setVar('napijelentes', $lista->napiJelentes($datum, $igdatum));

        $view->printTemplateResult();
    }

    public function printNapijelentes2()
    {
        $lista = new listaController();
        $datumstr = $this->params->getStringRequestParam('datum');
        $datum = \mkw\store::convDate($datumstr);
        $igdatumstr = $this->params->getStringRequestParam('datumig');
        $igdatum = \mkw\store::convDate($igdatumstr);
        $raktarid = $this->params->getIntRequestParam('raktar', \mkw\store::getIntParameter(\mkw\consts::Napijelentes2DefaultRaktar, 15));
        $letrehozoid = $this->params->getIntRequestParam('letrehozo');
        $view = $this->createView('napijelentes2body.tpl');
        $view->setVar('napijelentes2', $lista->napiJelentes($datum, $igdatum, $raktarid, $letrehozoid));

        $view->printTemplateResult();
    }

    public function printTeljesitmenyJelentes()
    {
        $lista = new listaController();

        $datumstr = $this->params->getStringRequestParam('tol');
        $datum = \mkw\store::convDate($datumstr);
        $igdatumstr = $this->params->getStringRequestParam('ig');
        $igdatum = \mkw\store::convDate($igdatumstr);

        $view = $this->createView('teljesitmenyjelentesbody.tpl');
        $view->setVar('tjlista', $lista->teljesitmenyJelentes($datum, $igdatum));
        $view->printTemplateResult();
    }

    public function regeneratekarkod()
    {
        $farepo = \mkw\store::getEm()->getRepository(Entities\TermekFa::class);
        $farepo->regenerateKarKod();
        echo 'ok';
    }

    public function regeneratemenukarkod()
    {
        $menurepo = \mkw\store::getEm()->getRepository(Entities\TermekMenu::class);
        $menurepo->regenerateKarKod();
        echo 'ok';
    }

    public function sanitize()
    {
        echo \mkwhelpers\Filter::toPermalink($this->params->getStringRequestParam('text', ''));
    }

    protected function cropimage()
    {
        $view = $this->createView('cropimage.tpl');
        $this->generalDataLoader->loadData($view);
        $view->setVar('pagetitle', t('Főoldal'));
        $view->printTemplateResult();
    }

    public function setUITheme()
    {
        $dolgozo = $this->getRepo(Entities\Dolgozo::class)->find(\mkw\store::getAdminSession()->loggedinuser['id']);
        if ($dolgozo) {
            $theme = $this->params->getStringRequestParam('uitheme', 'sunny');
            $dolgozo->setUitheme($theme);
            $this->getEm()->persist($dolgozo);
            $this->getEm()->flush();
            \mkw\store::getAdminSession()->loggedinuser['uitheme'] = $theme;
        }
    }

    /**
     * Lista-szintű felhasználói beállítás mentése a parameterek táblába.
     * A kulcs a lista URL-jének (? előtti) elérési útja, a domain nélkül (pl. /admin/orszag/viewlist).
     * Jelenleg a "Mindig nyitva" (szűrő nyitva tartása) állapotot menti a mattable listák.
     */
    public function setListParam()
    {
        $key = $this->params->getStringRequestParam('key', '');
        $value = $this->params->getStringRequestParam('value', '0');
        // biztonsági korlát: csak elérési út jellegű kulcs (vezető /, nincs domain/query),
        // hogy ne lehessen tetszőleges parameterek-sort felülírni
        if (preg_match('#^/[A-Za-z0-9/_-]+$#', $key)) {
            \mkw\store::setParameter($key, $value ? '1' : '0');
        }
    }

    public function getSmallUrl()
    {
        echo \mkw\store::createSmallImageUrl($this->params->getStringRequestParam('url'));
    }

    public function setVonalkodFromValtozat()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('vonalkod', '=', '');
        $termekek = \mkw\store::getEm()->getRepository(Entities\Termek::class)->getAll($filter, []);
        foreach ($termekek as $termek) {
            $valtozatok = $termek->getValtozatok();
            $termek->setVonalkod($valtozatok[0]->getVonalkod());
            \mkw\store::getEm()->persist($termek);
            \mkw\store::getEm()->flush();
        }
        echo 'ok';
    }

    public function fillBiztetelValtozat()
    {
        $repo = $this->getRepo(Entities\Bizonylattetel::class);
        $mind = $repo->getAll();
        foreach ($mind as $bt) {
            if ($bt->getTermekvaltozat()) {
                $bt->setTermekvaltozat($bt->getTermekvaltozat());
                $this->getEm()->persist($bt);
                $this->getEm()->flush();
            }
        }
        echo 'kesz';
    }

    public function generateFolyoszamla()
    {
        /*
        $repo = $this->getRepo(Entities\Bizonylatfej::class);
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('penztmozgat', '=', true);
        $bfs = $repo->getAll($filter);
        foreach ($bfs as $bf) {
            $repo->createFolyoszamla($bf);
        }

        $bbrepo = $this->getRepo(Entities\Bankbizonylatfej::class);
        $bfs = $bbrepo->getAll();
        foreach ($bfs as $bf) {
            $bbrepo->createFolyoszamla($bf);
        }
        */
        echo 'kesz';
    }

    public function replier()
    {
        header('HTTP/1.1 200 OK');
    }

    public function genean13()
    {
        $conn = \mkw\store::getEm()->getConnection();
        $termekidk = \mkw\store::getEm()->getRepository('\Entities\Termek')->getIdsWithJoins([], []);
        foreach ($termekidk as $ttt) {
            $termekid = $ttt['id'];

            $stmt = $conn->prepare('INSERT INTO vonalkodseq (data) VALUES (1)');
            $stmt->executeStatement();
            $vonalkod = \mkw\store::generateEAN13((string)$conn->lastInsertId());
            $st2 = $conn->prepare('UPDATE termek SET vonalkod="' . $vonalkod . '" WHERE id=' . $termekid);
            $st2->executeStatement();

            $f = new \mkwhelpers\FilterDescriptor();
            $f->addFilter('termek', '=', $termekid);
            $valtozatok = \mkw\store::getEm()->getRepository('\Entities\TermekValtozat')->getAll($f);
            foreach ($valtozatok as $valtozat) {
                $stmt = $conn->prepare('INSERT INTO vonalkodseq (data) VALUES (1)');
                $stmt->executeStatement();
                $vonalkod = \mkw\store::generateEAN13((string)$conn->lastInsertId());
                $st2 = $conn->prepare('UPDATE termekvaltozat SET vonalkod="' . $vonalkod . '" WHERE id=' . $valtozat->getId());
                $st2->executeStatement();
            }
        }
        echo 'kész';
    }

    public function cimletez()
    {
        $view = $this->createView('cimletezoeredmeny.tpl');
        $view->setVar('cimletek', \mkw\store::cimletez($this->params->getStringRequestParam('osszegek')));
        $view->printTemplateResult();
    }

    public function repairFoglalas()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('foglal', '=', 1);
        $r = $this->getRepo(Entities\Bizonylatstatusz::class)->getAll($filter);
        $statuszok = [];
        foreach ($r as $bs) {
            $statuszok[] = $bs->getId();
        }

        $filter->clear();
        $filter->addSql('_xx.bizonylatstatusz NOT IN (' . implode(',', $statuszok) . ')');
        $filter->addFilter('bizonylattipus', '=', 'megrendeles');
        $r = $this->getRepo(Entities\Bizonylatfej::class)->getAll($filter);
        foreach ($r as $bf) {
            $q = \mkw\store::getEm()->createQuery('UPDATE Entities\Bizonylattetel bt SET bt.foglal=0 WHERE bt.bizonylatfej=\'' . $bf->getId() . '\'');
            $q->Execute();
        }
        echo 'kész';
    }

    public function calcBerletervenyesseg()
    {
        $vasarlas = new \DateTime(\mkw\store::convDate($this->params->getStringRequestParam('vasarlasdatum')));
        $het = $this->params->getIntRequestParam('berlettipus');
        $iw = new \DateInterval('P' . $het . 'W');
        $vasarlas->add($iw);
        echo $vasarlas->format('Y.m.d');
    }

    public function checkEmail()
    {
        echo 'balint.lovey@gmail.com - X' . print_r(\mkw\store::isValidEmail('balint.lovey@gmail.com'), true) . 'X<br>';
        echo '^balint.lovey@gmail.com - X' . \mkw\store::isValidEmail('balint.lovey@gmail.com,vikarerzsebet@gmail.com') . 'X<br>';
        echo 'balint.lovey@gmail com - X' . \mkw\store::isValidEmail('balint.lovey@gmail com') . 'X';
    }
    
    public function ujdivatszamlare()
    {
        $no = new \mkwhelpers\NAVOnline(\mkw\store::getTulajAdoszam(), \mkw\store::getNAVOnlineEnv());
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('bizonylattipus', '=', 'szamla');
        $filter->addFilter('id', '<=', 'SZ2022/000102');
        $r = $this->getRepo(Entities\Bizonylatfej::class)->getAll($filter);
        /** @var Entities\Bizonylatfej $bf */
        foreach ($r as $bf) {
            if ($no->getSzamlaContent($bf->getId())) {
                $adat = base64_decode($no->getResult());
                $xmladat = simplexml_load_string($adat);
                $ns = $xmladat->InvoiceData->getDocNamespaces(true);
                $customer = $xmladat->invoiceMain->invoice->invoiceHead->customerInfo;
                $name = (string)$customer->customerName;
                switch ($ns['']) {
                    case 'http://schemas.nav.gov.hu/OSA/2.0/data':
                        $vatstatus = '';
                        $taxnumbase = $customer->customerTaxNumber;
                        $taxnum = (string)$taxnumbase->taxpayerId . '-' . (string)$taxnumbase->vatCode . '-' . (string)$taxnumbase->countyCode;
                        $address = $customer->customerAddress->simpleAddress;
                        $orszagkod = (string)$address->countryCode;
                        $irszam = (string)$address->postalCode;
                        $varos = (string)$address->city;
                        $utca = (string)$address->additionalAddressDetail;
                        break;
                    case 'http://schemas.nav.gov.hu/OSA/3.0/data':
                        $vatstatus = (string)$customer->customerVatStatus;
                        switch ($vatstatus) {
                            case 'DOMESTIC':
                                $taxnumbase = $customer->customerVatData->customerTaxNumber->children('base', true);
                                $taxnum = (string)$taxnumbase->taxpayerId . '-' . (string)$taxnumbase->vatCode . '-' . (string)$taxnumbase->countyCode;
                                break;
                            case 'PRIVATE_PERSON':
                                $taxnum = '';
                                break;
                        }
                        $address = $customer->customerAddress->children('base', true)->simpleAddress;
                        $orszagkod = (string)$address->countryCode;
                        $irszam = (string)$address->postalCode;
                        $varos = (string)$address->city;
                        $utca = (string)$address->additionalAddressDetail;
                        break;
                }

                echo $bf->getId() . ': ' . $name . ' ' . $vatstatus . ' ' . $taxnum . ' ' . $orszagkod . ' ' . $irszam . ' ' . $varos . ', ' . $utca . '<br>';
                if ($vatstatus !== 'PRIVATE_PERSON') {
                    $bf->setPartneradoszam($taxnum);
                    $bf->setPartnerirszam($irszam);
                    $bf->setPartnervaros($varos);
                    $bf->setPartnerutca($utca);
                    $bf->setPartnernev($name);
                    switch ($vatstatus) {
                        case 'DOMESTIC':
                            $bf->setPartnervatstatus(1);
                            break;
                    }
                    $bf->setKellszallitasikoltsegetszamolni(false);
                    $bf->setSimpleedit(true);
                    $this->getEm()->persist($bf);
                    $this->getEm()->flush();
                } else {
                    $bf->setPartnervatstatus(2);
                    $this->getEm()->persist($bf);
                    $this->getEm()->flush();
                }
            } else {
                echo $bf->getId() . ':' . $no->getErrorsAsHtml() . '<br>';
            }
        }
        echo 'kész';
    }

    private function n($mit)
    {
        if (strlen($mit) === 1) {
            return ord($mit) - 97;
        }
        if (strlen($mit) === 2) {
            return ((ord($mit[0]) - 96) * 26) + (ord($mit[1]) - 97);
        }
    }

    private function createMptSzekcio($nev)
    {
        $szekcio = \mkw\store::getEm()->getRepository(Entities\MPTSzekcio::class)->findOneBy(['nev' => $nev]);
        if (!$szekcio) {
            $szekcio = new Entities\MPTSzekcio();
            $szekcio->setNev($nev);
            \mkw\store::getEm()->persist($szekcio);
            \mkw\store::getEm()->flush($szekcio);
        }
        return $szekcio;
    }

    private function createMptTagozat($nev)
    {
        $tagozat = \mkw\store::getEm()->getRepository(Entities\MPTTagozat::class)->findOneBy(['nev' => $nev]);
        if (!$tagozat) {
            $tagozat = new Entities\MPTTagozat();
            $tagozat->setNev($nev);
            \mkw\store::getEm()->persist($tagozat);
            \mkw\store::getEm()->flush($tagozat);
        }
        return $tagozat;
    }

    private function createMptTagsagforma($nev)
    {
        $tagsagforma = \mkw\store::getEm()->getRepository(Entities\MPTTagsagforma::class)->findOneBy(['nev' => $nev]);
        if (!$tagsagforma) {
            $tagsagforma = new Entities\MPTTagsagforma();
            $tagsagforma->setNev($nev);
            \mkw\store::getEm()->persist($tagsagforma);
            \mkw\store::getEm()->flush($tagsagforma);
        }
        return $tagsagforma;
    }

    /**
     * @param $osszeg
     * @param $biz
     * @param $datum
     * @param $ev
     * @param $p Entities\Partner
     * @param $mod
     *
     * @return void
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    private function MPTCreateFSZ($osszeg, $biz, $datum, $ev, $p, $mod)
    {
        switch ($mod) {
            case 'eloiras':
                switch ($ev) {
                    case 2007:
                    case 2008:
                    case 2009:
                    case 2011:
                    case 2012:
                    case 2013:
                    case 2014:
                    case 2015:
                        if ($p->getMptTagsagformaNev() === 'rendes') {
                            $osszeg = 6000;
                        } else {
                            $osszeg = 3000;
                        }
                        break;
                    case 2010:
                        if ($p->getMptTagsagformaNev() === 'rendes') {
                            $osszeg = 5000;
                        } else {
                            $osszeg = 2500;
                        }
                        break;
                    case 2016:
                    case 2017:
                    case 2018:
                    case 2019:
                    case 2020:
                    case 2021:
                    case 2022:
                    case 2023:
                    case 2024:
                        if ($p->getMptTagsagformaNev() === 'rendes') {
                            $osszeg = 7000;
                        } else {
                            $osszeg = 3500;
                        }
                        break;
                }
                $f = new Entities\MPTFolyoszamla();
                $f->setTipus('E');
                $f->setIrany('-1');
                $f->setPartner($p);
                $f->setMegjegyzes('Importált adat');
                $f->setDatum($datum);
                $f->setOsszeg($osszeg);
                $f->setBizonylatszam('ismeretlen');
                $f->setVonatkozoev($ev);
                \mkw\store::getEm()->persist($f);
                break;
            case 'minden':
                $f = new Entities\MPTFolyoszamla();
                $f->setTipus('E');
                $f->setIrany('-1');
                $f->setPartner($p);
                $f->setMegjegyzes('Importált adat');
                $f->setDatum($datum);
                $f->setOsszeg($osszeg);
                $f->setBizonylatszam('ismeretlen');
                $f->setVonatkozoev($ev);
                \mkw\store::getEm()->persist($f);

                $f = new Entities\MPTFolyoszamla();
                $f->setTipus('B');
                $f->setIrany('1');
                $f->setPartner($p);
                $f->setMegjegyzes('Importált adat');
                $f->setDatum($datum);
                $f->setOsszeg($osszeg);
                $f->setBizonylatszam($biz);
                $f->setVonatkozoev($ev);
                \mkw\store::getEm()->persist($f);
                break;
        }
    }

    public function MPTPartnerImport()
    {
        function getosszeg($mibol)
        {
            if (trim($mibol) !== '-') {
                return (int)trim($mibol);
            }
            return false;
        }

        function getmod($mibol)
        {
            if (trim($mibol) === '-') {
                return 'semmi';
            } elseif ((int)trim($mibol) === 0) {
                return 'eloiras';
            }
            return 'minden';
        }

        function getbiz($mibol)
        {
            if (trim($mibol) !== '-') {
                return trim($mibol);
            }
            return false;
        }


        $sep = ';';
        $fh = fopen(\mkw\store::storagePath('mpttagok.csv'), 'r');
        if ($fh) {
            \mkw\store::getEm()->getConnection()->executeStatement('set foreign_key_checks=0');
            \mkw\store::getEm()->getConnection()->executeStatement('TRUNCATE partner');
            \mkw\store::getEm()->getConnection()->executeStatement('TRUNCATE mpttagsagforma');
            \mkw\store::getEm()->getConnection()->executeStatement('TRUNCATE mpttagozat');
            \mkw\store::getEm()->getConnection()->executeStatement('TRUNCATE mptszekcio');
            \mkw\store::getEm()->getConnection()->executeStatement('TRUNCATE mptfolyoszamla');
            \mkw\store::getEm()->getConnection()->executeStatement('set foreign_key_checks=1');

            fgetcsv($fh, 0, $sep, '"');
            while ($data = fgetcsv($fh, 0, $sep, '"')) {
                if ($data[$this->n('a')]) {
                    $p = new Entities\Partner();
                    if ($data[$this->n('a')]) {
                        $p->setNev(mb_substr($data[$this->n('a')], 0, 255));
                        $p->setMptSzamlazasinev(mb_substr($data[$this->n('a')], 0, 255));
                    }
                    $nev = explode(' ', $data[$this->n('a')]);
                    $p->setVezeteknev($nev[0]);
                    unset($nev[0]);
                    $p->setKeresztnev(implode(' ', $nev));
                    $p->setMptUsername($data[$this->n('b')]);
                    $p->setEmail($data[$this->n('c')]);
                    $p->setUjdonsaghirlevelkell($data[$this->n('f')]);
                    if ($data[$this->n('g')] && substr($data[$this->n('g')], 0, 10) !== '0000-00-00') {
                        $p->setMptRegisterdate($data[$this->n('g')]);
                    }
                    if ($data[$this->n('h')] && substr($data[$this->n('h')], 0, 10) !== '0000-00-00') {
                        $p->setMptLastvisit($data[$this->n('h')]);
                    }
                    $p->setMptUserid($data[$this->n('p')]);
                    if ($data[$this->n('aa')] && substr($data[$this->n('aa')], 0, 10) !== '0000-00-00') {
                        $p->setMptLastupdate($data[$this->n('aa')]);
                    }
                    $p->setMptMunkahelynev($data[$this->n('ak')]);
                    $p->setTelefon($data[$this->n('am')]);

                    $cim = \mkw\store::explodeCim($data[$this->n('cp')]);
                    $p->setIrszam(mb_substr($cim[0], 0, 10));
                    $p->setVaros(mb_substr($cim[1], 0, 40));
                    $p->setUtca(mb_substr($cim[2], 0, 60));
                    $cim = \mkw\store::explodeCim($data[$this->n('as')]);
                    $p->setMptLakcimirszam(mb_substr($cim[0], 0, 10));
                    $p->setMptLakcimvaros(mb_substr($cim[1], 0, 40));
                    $p->setMptLakcimutca(mb_substr($cim[2], 0, 60));
                    $cim = \mkw\store::explodeCim($data[$this->n('at')]);
                    $p->setMptMunkahelyirszam(mb_substr($cim[0], 0, 10));
                    $p->setMptMunkahelyvaros(mb_substr($cim[1], 0, 40));
                    $p->setMptMunkahelyutca(mb_substr($cim[2], 0, 60));
                    $p->setMptTagkartya($data[$this->n('au')]);
                    if ($data[$this->n('aw')]) {
                        $sz = $this->createMptSzekcio($data[$this->n('aw')]);
                        $p->setMptSzekcio1($sz);
                    }
                    if ($data[$this->n('ax')]) {
                        $sz = $this->createMptTagozat($data[$this->n('ax')]);
                        $p->setMptTagozat($sz);
                    }
                    if (\mkw\store::isMagyarAdoszam($data[$this->n('bk')])) {
                        $p->setAdoszam($data[$this->n('bk')]);
                    }
                    $p->setMptMegszolitas($data[$this->n('bm')]);
                    $p->setMptFokozat($data[$this->n('bn')]);
                    $p->setMptVegzettseg($data[$this->n('bo')]);
                    if ($data[$this->n('bp')]) {
                        $sz = $this->createMptTagsagforma($data[$this->n('bp')]);
                        $p->setMptTagsagforma($sz);
                    }
                    $p->setMptSzuleteseve($data[$this->n('bu')]);
                    $p->setSzuletesiido($data[$this->n('bu')] . '.01.01');
                    $p->setMptDiplomahely($data[$this->n('bv')]);

                    if ($data[$this->n('bs')]) {
                        $tagsagdate = trim($data[$this->n('bs')]);
                        if ($tagsagdate) {
                            if (\mkw\store::isValidDate($tagsagdate)) {
                                $p->setMptTagsagdate($tagsagdate);
                            } else {
                                $tagsagdate = substr($tagsagdate, 0, 4);
                                if (\mkw\store::isValidDate($tagsagdate . '-01-01')) {
                                    $p->setMptTagsagdate($tagsagdate . '-01-01');
                                }
                            }
                        }
                    }

                    $p->setMptDiplomaeve($data[$this->n('bw')]);
                    $p->setMptEgyebdiploma($data[$this->n('bx')]);
                    $p->setMptPrivatemail($data[$this->n('by')]);
                    $p->setMegjegyzes($data[$this->n('ci')]);
                    if ($data[$this->n('cn')]) {
                        $sz = $this->createMptSzekcio($data[$this->n('cn')]);
                        $p->setMptSzekcio2($sz);
                    }
                    if ($data[$this->n('co')]) {
                        $sz = $this->createMptSzekcio($data[$this->n('co')]);
                        $p->setMptSzekcio3($sz);
                    }
                    $p->setInaktiv(trim($data[$this->n('cu')]) === '');

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('ck')]),
                        getbiz($data[$this->n('cj')]),
                        '2004-01-01',
                        2004,
                        $p,
                        getmod($data[$this->n('ck')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('ch')]),
                        getbiz($data[$this->n('cf')]),
                        '2005-01-01',
                        2005,
                        $p,
                        getmod($data[$this->n('ch')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('ce')]),
                        getbiz($data[$this->n('cd')]),
                        '2006-01-01',
                        2006,
                        $p,
                        getmod($data[$this->n('ce')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('cc')]),
                        getbiz($data[$this->n('cb')]),
                        '2007-01-01',
                        2007,
                        $p,
                        getmod($data[$this->n('cc')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('ca')]),
                        getbiz($data[$this->n('bz')]),
                        '2008-01-01',
                        2008,
                        $p,
                        getmod($data[$this->n('ca')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('cg')]),
                        getbiz($data[$this->n('bt')]),
                        '2009-01-01',
                        2009,
                        $p,
                        getmod($data[$this->n('cg')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('cm')]),
                        getbiz($data[$this->n('cl')]),
                        '2010-01-01',
                        2010,
                        $p,
                        getmod($data[$this->n('cm')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('cr')]),
                        getbiz($data[$this->n('cq')]),
                        '2011-01-01',
                        2011,
                        $p,
                        getmod($data[$this->n('cr')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('ct')]),
                        getbiz($data[$this->n('cs')]),
                        '2012-01-01',
                        2012,
                        $p,
                        getmod($data[$this->n('ct')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('cw')]),
                        getbiz($data[$this->n('cv')]),
                        '2013-01-01',
                        2013,
                        $p,
                        getmod($data[$this->n('cw')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('cy')]),
                        getbiz($data[$this->n('cx')]),
                        '2014-01-01',
                        2014,
                        $p,
                        getmod($data[$this->n('cy')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('da')]),
                        getbiz($data[$this->n('cz')]),
                        '2015-01-01',
                        2015,
                        $p,
                        getmod($data[$this->n('da')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('dc')]),
                        getbiz($data[$this->n('db')]),
                        '2016-01-01',
                        2016,
                        $p,
                        getmod($data[$this->n('dc')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('de')]),
                        getbiz($data[$this->n('dd')]),
                        '2017-01-01',
                        2017,
                        $p,
                        getmod($data[$this->n('de')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('dg')]),
                        getbiz($data[$this->n('df')]),
                        '2018-01-01',
                        2018,
                        $p,
                        getmod($data[$this->n('dg')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('di')]),
                        getbiz($data[$this->n('dh')]),
                        '2019-01-01',
                        2019,
                        $p,
                        getmod($data[$this->n('di')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('dk')]),
                        getbiz($data[$this->n('dj')]),
                        '2020-01-01',
                        2020,
                        $p,
                        getmod($data[$this->n('dk')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('dn')]),
                        getbiz($data[$this->n('dm')]),
                        '2021-01-01',
                        2021,
                        $p,
                        getmod($data[$this->n('dn')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('dp')]),
                        getbiz($data[$this->n('do')]),
                        '2022-01-01',
                        2022,
                        $p,
                        getmod($data[$this->n('dp')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('dr')]),
                        getbiz($data[$this->n('dq')]),
                        '2023-01-01',
                        2023,
                        $p,
                        getmod($data[$this->n('dr')])
                    );

                    $this->MPTCreateFSZ(
                        getosszeg($data[$this->n('dt')]),
                        getbiz($data[$this->n('ds')]),
                        '2024-01-01',
                        2024,
                        $p,
                        getmod($data[$this->n('dt')])
                    );

                    \mkw\store::getEm()->persist($p);
                    \mkw\store::getEm()->flush();
                }
            }
        }
        echo 'Ready.';
    }

    public function setSzerzoByEmail()
    {
        $anyagok = $this->getRepo(Entities\MPTNGYSzakmaianyag::class)->getAll();
        /** @var Entities\MPTNGYSzakmaianyag $anyag */
        foreach ($anyagok as $anyag) {
            if ($anyag->getSzerzo1email() && strtolower($anyag->getSzerzo1email()) !== strtolower($anyag->getSzerzo1()?->getEmail())) {
                \mkw\store::writelog(
                    $anyag->getId() . '/1: ' . $anyag->getSzerzo1email() . ' <> ' . $anyag->getSzerzo1()?->getEmail() . ' | partnerid ' . $anyag->getSzerzo1(
                    )?->getId()
                );
                $partner = $this->getRepo(Entities\Partner::class)->findOneBy(['email' => $anyag->getSzerzo1email()]);
                if ($partner) {
                    \mkw\store::writelog($anyag->getId() . '/1: ' . $partner->getNev() . ' : ' . $partner->getId());
                    $anyag->setSzerzo1($partner);
                }
            }
            if ($anyag->getSzerzo2email() && strtolower($anyag->getSzerzo2email()) !== strtolower($anyag->getSzerzo2()?->getEmail())) {
                \mkw\store::writelog(
                    $anyag->getId() . '/2: ' . $anyag->getSzerzo2email() . ' <> ' . $anyag->getSzerzo2()?->getEmail() . ' | partnerid ' . $anyag->getSzerzo2(
                    )?->getId()
                );
                $partner = $this->getRepo(Entities\Partner::class)->findOneBy(['email' => $anyag->getSzerzo2email()]);
                if ($partner) {
                    \mkw\store::writelog($anyag->getId() . '/2: ' . $partner->getNev() . ' : ' . $partner->getId());
                    $anyag->setSzerzo2($partner);
                }
            }
            if ($anyag->getSzerzo3email() && strtolower($anyag->getSzerzo3email()) !== strtolower($anyag->getSzerzo3()?->getEmail())) {
                \mkw\store::writelog(
                    $anyag->getId() . '/3: ' . $anyag->getSzerzo3email() . ' <> ' . $anyag->getSzerzo3()?->getEmail() . ' | partnerid ' . $anyag->getSzerzo3(
                    )?->getId()
                );
                $partner = $this->getRepo(Entities\Partner::class)->findOneBy(['email' => $anyag->getSzerzo3email()]);
                if ($partner) {
                    \mkw\store::writelog($anyag->getId() . '/3: ' . $partner->getNev() . ' : ' . $partner->getId());
                    $anyag->setSzerzo3($partner);
                }
            }
            if ($anyag->getSzerzo4email() && strtolower($anyag->getSzerzo4email()) !== strtolower($anyag->getSzerzo4()?->getEmail())) {
                \mkw\store::writelog(
                    $anyag->getId() . '/4: ' . $anyag->getSzerzo4email() . ' <> ' . $anyag->getSzerzo4()?->getEmail() . ' | partnerid ' . $anyag->getSzerzo4(
                    )?->getId()
                );
                $partner = $this->getRepo(Entities\Partner::class)->findOneBy(['email' => $anyag->getSzerzo4email()]);
                if ($partner) {
                    \mkw\store::writelog($anyag->getId() . '/4: ' . $partner->getNev() . ' : ' . $partner->getId());
                    $anyag->setSzerzo4($partner);
                }
            }
            if ($anyag->getSzerzo5email() && strtolower($anyag->getSzerzo5email()) !== strtolower($anyag->getSzerzo5()?->getEmail())) {
                \mkw\store::writelog(
                    $anyag->getId() . '/5: ' . $anyag->getSzerzo5email() . ' <> ' . $anyag->getSzerzo5()?->getEmail() . ' | partnerid ' . $anyag->getSzerzo5(
                    )?->getId()
                );
                $partner = $this->getRepo(Entities\Partner::class)->findOneBy(['email' => $anyag->getSzerzo5email()]);
                if ($partner) {
                    \mkw\store::writelog($anyag->getId() . '/5: ' . $partner->getNev() . ' : ' . $partner->getId());
                    $anyag->setSzerzo5($partner);
                }
            }
            \mkw\store::getEm()->persist($anyag);
            \mkw\store::getEm()->flush();
        }
        echo 'Ready.';
    }

    public function fszlahivdatumJavit()
    {
        $fszlak = $this->getRepo(Entities\Folyoszamla::class)->getAll();
        /** @var Entities\Folyoszamla $fszla */
        foreach ($fszlak as $fszla) {
            if (!$fszla->getBizonylatfej()) {
                $szamla = $this->getRepo(Entities\Bizonylatfej::class)->find($fszla->getHivatkozottbizonylat());
                if ($szamla && $fszla->getHivatkozottdatum() !== $szamla->getEsedekesseg()) {
                    $fszla->setHivatkozottdatum($szamla->getEsedekesseg());
                    $this->getEm()->persist($fszla);
                    $this->getEm()->flush();
                }
            }
        }
        echo 'Ready.';
    }
}