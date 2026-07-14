<?php

namespace Controllers;

use Entities\Afa;
use Entities\Bankszamla;
use Entities\BizonylatDok;
use Entities\Bizonylatfej;
use Entities\Bizonylatstatusz;
use Entities\Bizonylatstatusznaplo;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\CsomagTerminal;
use Entities\Dolgozo;
use Entities\Emailtemplate;
use Entities\Fizmod;
use Entities\Folyoszamla;
use Entities\Jogcim;
use Entities\Orszag;
use Entities\Partner;
use Entities\Partnercimketorzs;
use Entities\Penztar;
use Entities\Raktar;
use Entities\Szallitasimod;
use Entities\Termek;
use Entities\TermekValtozat;
use Entities\Uzletkoto;
use Entities\Valutanem;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Services\BizonylatCalculatorService;

class bizonylatfejController extends \mkwhelpers\MattableController
{

    protected $biztipus;

    public static function factory($biztip)
    {
        switch ($biztip) {
            case 'szamla':
                return new szamlafejController();
            case 'esetiszamla':
                return new esetiszamlafejController();
            default:
                return new bizonylatfejController();
        }
    }

    public function __construct()
    {
        $this->setEntityName(Bizonylatfej::class);
        $this->setKarbFormTplName('bizonylatfejkarbform.tpl');
        $this->setKarbTplName('bizonylatfejkarb.tpl');
        $this->setListBodyRowTplName('bizonylatfejlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
        $this->getRepo()->addToBatches(['excelfejexport' => 'Fejadat export']);
        $this->getRepo()->addToBatches(['exceltetelexport' => 'Tételadat export']);
    }

    public function viewselect()
    {
        $view = $this->createView('bizonylatfejlista.tpl');

        $view->setVar('pagetitle', $this->getPluralPageTitle());
        $view->setVar('controllerscript', $this->biztipus . 'fej.js');
        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function viewlist()
    {
        /** @var \Entities\Bizonylattipus $bt */
        $bt = $this->getRepo(Bizonylattipus::class)->find($this->biztipus);
        if ($bt && $bt->getNavbekuldendo()) {
            $this->NAVEredmenyFeldolgoz();
        }

        $view = $this->createView('bizonylatfejlista.tpl');

        $view->setVar('pagetitle', $this->getPluralPageTitle());
        $view->setVar('controllerscript', $this->biztipus . 'fej.js');
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function setVars($view)
    {
        $bt = $this->getRepo(Bizonylattipus::class)->find($this->biztipus);
        $bt->setTemplateVars($view);

        $fmc = new fizmodController();
        $view->setVar('fizmodlist', $fmc->getSelectList());

        $fmc = new szallitasimodController();
        $view->setVar('szallitasimodlist', $fmc->getSelectList());

        $fmc = new uzletkotoController();
        $view->setVar('uzletkotolist', $fmc->getSelectList(false));

        $fmc = new valutanemController();
        $view->setVar('valutanemlist', $fmc->getSelectList());

        $raktar = new raktarController();
        $view->setVar('raktarlist', $raktar->getSelectList());

        $felh = new dolgozoController();
        $view->setVar('felhasznalolist', $felh->getSelectList());

        $emailtpl = new emailtemplateController();
        $view->setVar('emailsablonlist', $emailtpl->getSelectList());

        $bsc = new bizonylatstatuszController();
        switch (true) {
            case \mkw\store::isMindentkapni():
                $a = date(\mkw\store::$DateFormat, strtotime('-1 week'));
                if ($this->biztipus === 'megrendeles') {
                    $view->setVar('bizonylatstatuszlist', $bsc->getSelectList(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben)));
                } else {
                    $view->setVar('bizonylatstatuszlist', $bsc->getSelectList());
                }
                $view->setVar('bizonylatstatuszcsoportlist', $bsc->getCsoportSelectList());
                break;
            case \mkw\store::isSuperzoneB2B():
                if (!\mkw\store::haveJog(20)) {
                    $a = date(\mkw\store::$DateFormat);
                } else {
                    $a = false;
                }
                $view->setVar('bizonylatstatuszlist', $bsc->getSelectList());
                $view->setVar('bizonylatstatuszcsoportlist', $bsc->getCsoportSelectList());
                $view->setVar('bizonylatrontottfilter', 1);
                break;
            case \mkw\store::isKisszamlazo():
                if ($this->biztipus === 'bizsablon') {
                    $view->setVar('bizonylatrontottfilter', 1);
                }
                $a = false;
                break;
            default:
                $a = false;
                break;
        }
        if ($a) {
            $view->setVar('datumtolfilter', $a);
        }

        $pcc = new partnercimkekatController();
        $view->setVar('cimkekat', $pcc->getWithCimkek());
    }

    /**
     * @param \mkwhelpers\FilterDescriptor $filter
     *
     * @return mixed
     */
    protected function loadFilters($filter)
    {
        if (!is_null($this->params->getRequestParam('idfilter', null))) {
            $filter->addFilter('id', 'LIKE', '%' . $this->params->getStringRequestParam('idfilter'));
        }

        $f = $this->params->getIntRequestParam('partnerid');
        if ($f) {
            $filter->addFilter('partner', '=', $f);
        } else {
            $f = $this->params->getStringRequestParam('vevonevfilter');
            if ($f) {
                $filter->addFilter('partnernev', 'LIKE', '%' . $f . '%');
            }

            $f = $this->params->getStringRequestParam('vevoemailfilter');
            if ($f) {
                $filter->addFilter('partneremail', 'LIKE', '%' . $f . '%');
            }
            $f = $this->params->getStringRequestParam('vevotelefonfilter');
            if ($f) {
                $filter->addFilter('partnertelefon', 'LIKE', '%' . $f . '%');
            }
            $f = $this->params->getStringRequestParam('szallitasiirszamfilter');
            if ($f) {
                $filter->addFilter('szallirszam', 'LIKE', '%' . $f . '%');
            }
            $f = $this->params->getStringRequestParam('szallitasivarosfilter');
            if ($f) {
                $filter->addFilter('szallvaros', 'LIKE', '%' . $f . '%');
            }
            $f = $this->params->getStringRequestParam('szallitasiutcafilter');
            if ($f) {
                $filter->addFilter('szallutca', 'LIKE', '%' . $f . '%');
            }
            $f = $this->params->getStringRequestParam('szamlazasiirszamfilter');
            if ($f) {
                $filter->addFilter('partnerirszam', 'LIKE', '%' . $f . '%');
            }
            $f = $this->params->getStringRequestParam('szamlazasivarosfilter');
            if ($f) {
                $filter->addFilter('partnervaros', 'LIKE', '%' . $f . '%');
            }
            $f = $this->params->getStringRequestParam('szamlazasiutcafilter');
            if ($f) {
                $filter->addFilter('partnerutca', 'LIKE', '%' . $f . '%');
            }
        }
        $tip = $this->params->getStringRequestParam('datumtipusfilter');
        $tol = $this->params->getStringRequestParam('datumtolfilter');
        $ig = $this->params->getStringRequestParam('datumigfilter');
        if ($tip && ($tol || $ig)) {
            switch ($tip) {
                case 2:
                    $mezo = 'teljesites';
                    break;
                case 3:
                    $mezo = 'esedekesseg';
                    break;
                case 1:
                default:
                    $mezo = 'kelt';
                    break;
            }
            if ($tol) {
                $filter->addFilter($mezo, '>=', $tol);
            }
            if ($ig) {
                $filter->addFilter($mezo, '<=', $ig);
            }
        }
        $osszegtol = $this->params->getNumRequestParam('osszegtolfilter');
        if ($osszegtol) {
            $filter->addFilter('brutto', '>=', $osszegtol);
        }
        $osszegig = $this->params->getNumRequestParam('osszegigfilter');
        if ($osszegig) {
            $filter->addFilter('brutto', '<=', $osszegig);
        }
        $f = $this->params->getIntRequestParam('bizonylatstatuszfilter');
        if ($f) {
            $bs = $this->getRepo(Bizonylatstatusz::class)->findOneById($f);
            if ($bs) {
                $filter->addFilter('bizonylatstatusz', '=', $bs);
            }
        }
        $f = $this->params->getStringRequestParam('bizonylatstatuszcsoportfilter');
        if ($f) {
            $filter->addFilter('bizonylatstatuszcsoport', '=', $f);
        }
        $f = $this->params->getIntRequestParam('fizmodfilter');
        if ($f) {
            $bs = $this->getRepo(Fizmod::class)->findOneById($f);
            if ($bs) {
                $filter->addFilter('fizmod', '=', $bs);
            }
        }
        $f = $this->params->getIntRequestParam('szallitasimodfilter');
        if ($f) {
            $bs = $this->getRepo(Szallitasimod::class)->findOneById($f);
            if ($bs) {
                $filter->addFilter('szallitasimod', '=', $bs);
            }
        }
        $f = $this->params->getIntRequestParam('uzletkotofilter');
        if ($f) {
            $bs = $this->getRepo(Uzletkoto::class)->findOneById($f);
            if ($bs) {
                $filter->addFilter('uzletkoto', '=', $bs);
            }
        }
        $f = $this->params->getIntRequestParam('valutanemfilter');
        if ($f) {
            $bs = $this->getRepo(Valutanem::class)->findOneById($f);
            if ($bs) {
                $filter->addFilter('valutanem', '=', $bs);
            }
        }
        $f = $this->params->getIntRequestParam('raktarfilter');
        if ($f) {
            $bs = $this->getRepo(Raktar::class)->findOneById($f);
            if ($bs) {
                $filter->addFilter('raktar', '=', $bs);
            }
        }
        $f = $this->params->getStringRequestParam('fuvarlevelszamfilter');
        if ($f) {
            $filter->addFilter('fuvarlevelszam', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getStringRequestParam('referrerfilter');
        if ($f) {
            $filter->addFilter('referrer', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getStringRequestParam('erbizonylatszamfilter');
        if ($f) {
            $filter->addFilter('erbizonylatszam', 'LIKE', '%' . $f . '%');
        }

        $f = $this->params->getIntRequestParam('termekertekeleskikuldvefilter');
        switch ($f) {
            case 1:
                $filter->addFilter('termekertekeleskikuldve', '=', false);
                break;
            case 2:
                $filter->addFilter('termekertekeleskikuldve', '=', true);
                break;
        }

        $f = $this->params->getIntRequestParam('bizonylatrontottfilter');
        switch ($f) {
            case 1:
                $filter->addFilter('rontott', '=', false);
                break;
            case 2:
                $filter->addFilter('rontott', '=', true);
                break;
        }

        $f = $this->params->getIntRequestParam('bizonylatstornofilter');
        switch ($f) {
            case 1:
                $filter->addFilter(['storno', 'stornozott'], '=', false);
                break;
            case 2:
                $filter->addFilter(['storno', 'stornozott'], '=', true);
                break;
        }

        $cf = $this->params->getArrayRequestParam('cimkefilter');
        if ($cf) {
            $filter->addJoin('JOIN _xx.partner p');
            $filter->addJoin('INNER JOIN p.cimkek c WITH (c.id IN (' . \mkw\store::getCommaList($cf) . '))');
        }

        $f = $this->params->getIntRequestParam('feketelistafilter');
        switch ($f) {
            case 1:
                $filter->addFilter('partnerfeketelistas', '=', false);
                break;
            case 2:
                $filter->addFilter('partnerfeketelistas', '=', true);
                break;
        }

        $f = $this->params->getIntRequestParam('naveredmenyfilter');
        switch ($f) {
            case 1:
                $filter->addFilter('naveredmeny', '=', 'ABORTED');
                break;
            case 2:
                $filter->addFilter('naveredmeny', '=', 'WAITING');
                break;
            case 3:
                $filter->addSql('_xx.naveredmeny IS NULL');
                break;
        }

        $f = $this->params->getStringRequestParam('megjegyzesfilter');
        if ($f) {
            $filter->addFilter('megjegyzes', 'LIKE', '%' . $f . '%');
        }

        return $filter;
    }

    protected function loadVars($t, $forKarb = false, $oper = false)
    {
        $tetelCtrl = new bizonylattetelController();
        $dokCtrl = new termekdokController();
        $tetel = [];
        $dok = [];
        $x = [];
        if (!$t) {
            $t = new \Entities\Bizonylatfej();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['editprinted'] = $t->getBizonylattipus() ? $t->getBizonylattipus()->getEditprinted() : false;
        $x['nyomtatva'] = $t->getNyomtatva();
        $x['bizonylattipusid'] = $t->getBizonylattipusId();
        $x['hibas'] = $t->getHibas();
        $x['hibauzenetek'] = $t->getHibauzenetek();
        $x['afaellenorzesnemkell'] = $t->getAfaellenorzesnemkell();
        $x['afaellenorzesnemkellon'] = $t->getAfaellenorzesnemkellonStr();
        $x['afaellenorzesnemkellby'] = $t->getAfaellenorzesnemkellbyNev();
        $x['irany'] = $t->getIrany();
        // Új bizonylatnál a fejen még nincs irány – ilyenkor a bizonylattípus iránya a mérvadó
        // (hogy az egyedi azonosító autocomplete új, negatív irányú bizonylaton is működjön).
        if (!$x['irany'] && $this->biztipus) {
            $biztip = $this->getRepo(Bizonylattipus::class)->find($this->biztipus);
            if ($biztip) {
                $x['irany'] = $biztip->getIrany();
            }
        }
        $x['storno'] = $t->getStorno();
        $x['stornozott'] = $t->getStornozott();
        $x['rontott'] = $t->getRontott();
        $x['nemrossz'] = !$t->getRontott() && !$t->getStorno() && !$t->getStornozott();
        $x['tulajnev'] = $t->getTulajnev();
        $x['tulajirszam'] = $t->getTulajirszam();
        $x['tulajvaros'] = $t->getTulajvaros();
        $x['tulajutca'] = $t->getTulajutca();
        $x['tulajadoszam'] = $t->getTulajadoszam();
        $x['tulajeuadoszam'] = $t->getTulajeuadoszam();
        $x['bizonylatnev'] = $t->getBizonylatnev();
        $x['erbizonylatszam'] = $t->getErbizonylatszam();
        $x['fuvarlevelszam'] = $t->getFuvarlevelszam();
        $x['csomagcount'] = $t->getCsomagcount();
        if (\mkw\store::isFoxpostSzallitasimod($t->getSzallitasimodId())) {
            $x['csomagkovetolink'] = 'https://www.foxpost.hu/csomagkovetes/?code=' . $t->getFuvarlevelszam();
        } elseif (\mkw\store::isGLSSzallitasimod($t->getSzallitasimodId()) || \mkw\store::isGLSFutarSzallitasimod($t->getSzallitasimodId())) {
            $x['csomagkovetolink'] = $t->getGlsparcellabelurl();
        } elseif (\mkw\store::isFedexSzallitasimod($t->getSzallitasimodId())) {
            $x['csomagkovetolink'] = 'https://www.fedex.com/fedextrack/?trknbr=' . $t->getFedextrackingnumber();
        }
        $x['tobbimegrendeleslink'] = \mkw\store::getRouter()->generate(
            'adminmegrendelesfejviewlist',
            false,
            [],
            [
                'partnerid' => $t->getPartnerId(),
                'bizonylatstatuszfilter' => ''
            ]
        );
        $x['keltstr'] = $t->getKeltStr();
        $x['teljesitesstr'] = $t->getTeljesitesStr();
        $x['esedekessegstr'] = $t->getEsedekessegStr();
        $x['esedekesseg1str'] = $t->getEsedekesseg1Str();
        $x['fizetendo1'] = $t->getFizetendo1();
        $x['esedekesseg2str'] = $t->getEsedekesseg2Str();
        $x['fizetendo2'] = $t->getFizetendo2();
        $x['esedekesseg3str'] = $t->getEsedekesseg3Str();
        $x['fizetendo3'] = $t->getFizetendo3();
        $x['esedekesseg4str'] = $t->getEsedekesseg4Str();
        $x['fizetendo4'] = $t->getFizetendo4();
        $x['esedekesseg5str'] = $t->getEsedekesseg5Str();
        $x['fizetendo5'] = $t->getFizetendo5();
        $x['hataridostr'] = $t->getHataridoStr();
        $x['partner'] = $t->getPartnerId();
        $x['partnernev'] = $t->getPartnernev();
        $x['partnervezeteknev'] = $t->getPartnervezeteknev();
        $x['partnerkeresztnev'] = $t->getPartnerkeresztnev();
        $x['partnerirszam'] = $t->getPartnerirszam();
        $x['partnervaros'] = $t->getPartnervaros();
        $x['partnerutca'] = $t->getPartnerutca();
        $x['partnerhazszam'] = $t->getPartnerhazszam();
        $x['partnertelefon'] = $t->getPartnertelefon();
        $x['partneremail'] = $t->getPartneremail();
        $x['partneradoszam'] = $t->getPartneradoszam();
        $x['partnereuadoszam'] = $t->getPartnereuadoszam();
        $x['partnerthirdadoszam'] = $t->getPartnerthirdadoszam();
        $x['partnervatstatus'] = $t->getPartnervatstatus();
        $x['partnerszamlatipus'] = $t->getPartnerSzamlatipus();
        $x['partnerfeketelistas'] = $t->getPartnerfeketelistas();
        $x['partnerfeketelistaok'] = $t->getPartnerfeketelistaok();
        $x['partnerorszagnev'] = $t->getPartnerorszagnev();
        $x['partnerszallorszagnev'] = $t->getPartnerszallorszagnev();
        $x['raktar'] = $t->getRaktarId();
        $x['raktarnev'] = $t->getRaktarnev();
        $x['fizmod'] = $t->getFizmodId();
        $x['fizmodnev'] = $t->getFizmodnev();
        $x['fizmodnev_locale'] = $t->getLocalizedFieldValue('fizmodnev', $t->getBizonylatnyelv());
        $x['fizmodnev_l1'] = $t->getFizmodnevL1();
        $x['szallitasimod'] = $t->getSzallitasimodId();
        $x['szallitasimodnev'] = $t->getSzallitasimodnev();
        $x['szallitasimodnev_locale'] = $t->getLocalizedFieldValue('szallitasimodnev', $t->getBizonylatnyelv());
        $x['szallitasimodnev_l1'] = $t->getSzallitasimodnevL1();
        $x['felhasznalo'] = $t->getFelhasznaloId();
        $x['felhasznalonev'] = $t->getFelhasznalonev();
        $x['valutanem'] = $t->getValutanemId();
        $x['valutanemnev'] = $t->getValutanemnev();
        $x['arfolyam'] = $t->getArfolyam();
        $x['bankszamla'] = $t->getBankszamlaId();
        $x['tulajbanknev'] = $t->getTulajbanknev();
        $x['tulajbankszamlaszam'] = $t->getTulajbankszamlaszam();
        $x['tulajswift'] = $t->getTulajswift();
        $x['tulajiban'] = $t->getTulajiban();
        $x['tulajeorinr'] = $t->getTulajeorinr();
        $x['netto'] = $t->getNetto();
        $x['afa'] = $t->getAfa();
        $x['brutto'] = $t->getBrutto();
        $x['nettohuf'] = $t->getNettohuf();
        $x['afahuf'] = $t->getAfahuf();
        $x['bruttohuf'] = $t->getBruttohuf();
        $x['megjegyzes'] = $t->getMegjegyzes();
        $x['belsomegjegyzes'] = $t->getBelsomegjegyzes();
        $x['szallnev'] = $t->getSzallnev();
        $x['szallirszam'] = $t->getSzallirszam();
        $x['szallvaros'] = $t->getSzallvaros();
        $x['szallutca'] = $t->getSzallutca();
        $x['szallhazszam'] = $t->getSzallhazszam();
        $x['webshopmessage'] = $t->getWebshopmessage();
        $x['couriermessage'] = $t->getCouriermessage();
        $x['sysmegjegyzes'] = $t->getSysmegjegyzes();
        $x['foxpostbarcode'] = $t->getFoxpostBarcode();
        $x['ip'] = $t->getIp();
        $x['referrer'] = $t->getReferrer();
        $x['fizetve'] = $t->getFizetve();
        $x['termekertekelesid'] = $t->getTermekertekelesid();
        $x['uzletkoto'] = $t->getUzletkotoId();
        $x['uzletkotonev'] = $t->getUzletkotonev();
        $x['uzletkotoemail'] = $t->getUzletkotoemail();
        $x['uzletkotojutalek'] = $t->getUzletkotojutalek();
        $x['belsouzletkoto'] = $t->getBelsouzletkotoId();
        $x['belsouzletkotonev'] = $t->getBelsouzletkotonev();
        $x['belsouzletkotoemail'] = $t->getBelsouzletkotoemail();
        $x['belsouzletkotojutalek'] = $t->getBelsouzletkotojutalek();
        $x['bizonylatnyelv'] = $t->getBizonylatnyelv();
        $x['reportfile'] = $t->getReportfile();
        $x['regmode'] = $t->getRegmode();
        $x['lastmodstr'] = $t->getLastmodStr();
        $x['createdstr'] = $t->getCreatedStr();
        $x['updatedby'] = $t->getUpdatedbyNev();
        $x['createdby'] = $t->getCreatedbyNev();
        $x['kupon'] = $t->getKupon();
        $x['csomagterminalid'] = $t->getCsomagterminalId();
        $x['szepkartyaszam'] = $t->getSzepkartyaszam();
        $x['szepkartyanev'] = $t->getSzepkartyanev();
        $x['szepkartyaervenyessegstr'] = $t->getSzepkartyaervenyessegStr();
        $x['szepkartyatipus'] = $t->getSzepkartyatipus();
        $x['szepkartyatipusnev'] = $t->getSzepkartyatipusNev();
        $x['szepkartyakifizetve'] = $t->getSzepkartyakifizetve();
        $x['nincspenzmozgas'] = $t->getNincspenzmozgas();
        $x['barionpaymentstatus'] = $t->getBarionpaymentstatus();
        $x['isbarion'] = \mkw\store::isBarionFizmod($t->getFizmod());
        $x['isstripe'] = \mkw\store::isStripeFizmod($t->getFizmod());
        $x['stripepaymentintentid'] = $t->getStripepaymentintentid();
        $x['glsparcellabelurl'] = $t->getGlsparcellabelurl();
        $x['isglsbekuldve'] = (bool)$t->getGlsparcelid();
        $x['fedexparcellabelurlek'] = $t->getFedexparcellabelurlek();
        $x['shipdatestr'] = $t->getShipdateStr();
        $x['isfedexbekuldve'] = (bool)$t->getFedextrackingnumber();
        $x['isfedexszallitas'] = \mkw\store::isFedexSzallitasimod($t->getSzallitasimodId());
        $x['forditottadozas'] = $t->isForditottadozas();
        $x['termekertekeleskikuldve'] = $t->isTermekertekeleskikuldve();
        $x['navbekuldendo'] = $t->isNavbekuldendo() &&
            (
                ($t->getNaveredmeny() == 'ABORTED') ||
                ($t->getNaveredmeny() == 'WAITING') ||
                (!$t->getNaveredmeny())
            );
        $x['naveredmeny'] = $t->getNaveredmeny();
        $x['vegleges'] = $t->isNavbekuldendo() &&
            (
                ($t->getNaveredmeny() == 'DONE') ||
                ($t->getNaveredmeny() == 'WAITING')
            );
        if ($oper === $this->inheritOperation) {
            $x['fakekintlevoseg'] = false;
            $x['fakekifizetve'] = false;
            $x['fakekifizetesdatumstr'] = '';
            $x['afaellenorzesnemkell'] = false;
            $x['afaellenorzesnemkellon'] = '';
            $x['afaellenorzesnemkellby'] = '';
        } else {
            $x['fakekintlevoseg'] = $t->getFakekintlevoseg();
            $x['fakekifizetve'] = $t->getFakekifizetve();
            $x['fakekifizetesdatumstr'] = $t->getFakekifizetesdatumStr();
        }
        $bsc = new bizonylatstatuszController();
        $x['bizonylatstatuszlist'] = $bsc->getSelectList($t->getBizonylatstatuszId(), $t->getFizmodId(), $t->getSzallitasimodId());
        if ($forKarb) {
            foreach ($t->getBizonylatDokok() as $kepje) {
                $dok[] = $dokCtrl->loadVars($kepje);
            }
            $x['dokok'] = $dok;

            if ($t->getPartner()) {
                $afaoverride = $t->getPartner()->getAFAOverride();
                if ($afaoverride) {
                    $x['partnerafa'] = $afaoverride->getId();
                    $x['partnerafakulcs'] = $afaoverride->getErtek();
                } else {
                    $afa27 = $this->getRepo(Afa::class)->findOneBy(['ertek' => 27]);
                    if ($afa27) {
                        $x['partnerafa'] = $afa27->getId();
                        $x['partnerafakulcs'] = $afa27->getErtek();
                    }
                }
            }
            foreach ($t->getBizonylattetelek() as $ttetel) {
                $tetel[] = $tetelCtrl->loadVars($ttetel, true);
            }
//				$tetel[]=$tetelCtrl->loadVars(null,true);
            $x['tetelek'] = $tetel;
        } else {
            $x['egyenleg'] = $t->getEgyenleg() * -1 * $t->getIrany();
            if (\mkw\store::isOsztottFizmod()) {
                $ma = new \DateTime(\mkw\store::convDate(date(\mkw\store::$DateFormat)));
                $egyenlegek = [];
                $egyenleglist = $t->getOsztottEgyenleg();
                if ($egyenleglist) {
                    foreach ($egyenleglist as $e) {
                        $d = [];
                        $d['esedekesseg'] = $e['hivatkozottdatum']->format(\mkw\store::$DateFormat);
                        $d['egyenleg'] = $e['egyenleg'] * -1 * $t->getIrany();
                        $d['penzugyistatusz'] = \mkw\store::getPenzugyiStatusz($e['hivatkozottdatum'], $d['egyenleg']);
                        $egyenlegek[] = $d;
                    }
                    $x['osztottegyenlegek'] = $egyenlegek;
                }
            } else {
                $x['penzugyistatusz'] = \mkw\store::getPenzugyiStatusz($t->getEsedekesseg(), $x['egyenleg']);
            }
            $x['parbizonylat'] = $this->bizonylatReferencia($t->getParbizonylatfej());
            $szarmazok = [];
            foreach ($t->getSzulobizonylatfejek() as $gyerek) {
                $szarmazok[] = $this->bizonylatReferencia($gyerek);
            }
            $x['szarmazobizonylatok'] = $szarmazok;
            $x['szarmazobizonylatcount'] = count($szarmazok);
        }
        return $x;
    }

    private function bizonylatReferencia(\Entities\Bizonylatfej|null $b): array|null
    {
        if (!$b) {
            return null;
        }
        return [
            'id' => $b->getId(),
            'tipusnev' => $b->getBizonylattipus() ? $b->getBizonylattipus()->getNev() : $b->getBizonylatnev(),
            'keltstr' => $b->getKeltStr(),
            'createdstr' => $b->getCreatedStr(),
        ];
    }

    protected function setFields(\Entities\Bizonylatfej $obj, $parancs)
    {
        $partnerkod = $this->params->getIntRequestParam('partner');

        if ($partnerkod == -1) {
            $partneremail = $this->params->getStringRequestParam('partneremail');
            if ($partneremail) {
                $partnerobj = $this->getRepo(Partner::class)->findOneBy(['email' => $partneremail]);
                if (!$partnerobj) {
                    $partnerobj = new \Entities\Partner();
                }
            } else {
                $partnerobj = new \Entities\Partner();
            }
            $partnerobj->setAdoszam($this->params->getStringRequestParam('partneradoszam'));
            $partnerobj->setEuadoszam($this->params->getStringRequestParam('partnereuadoszam'));
            $partnerobj->setThirdadoszam($this->params->getStringRequestParam('partnerthirdadoszam'));
            $partnerobj->setEmail($this->params->getStringRequestParam('partneremail'));
            $partnerobj->setTelefon($this->params->getStringRequestParam('partnertelefon'));
            $partnerobj->setNev($this->params->getStringRequestParam('partnernev'));
            $partnerobj->setVezeteknev($this->params->getStringRequestParam('partnervezeteknev'));
            $partnerobj->setKeresztnev($this->params->getStringRequestParam('partnerkeresztnev'));
            $partnerobj->setIrszam($this->params->getStringRequestParam('partnerirszam'));
            $partnerobj->setVaros($this->params->getStringRequestParam('partnervaros'));
            $partnerobj->setUtca($this->params->getStringRequestParam('partnerutca'));
            $partnerobj->setHazszam($this->params->getStringRequestParam('partnerhazszam'));
            $ck = \mkw\store::getEm()->getRepository(Fizmod::class)->find($this->params->getIntRequestParam('fizmod'));
            if ($ck) {
                $partnerobj->setFizmod($ck);
            }
            $ck = \mkw\store::getEm()->getRepository(Orszag::class)->find($this->params->getIntRequestParam('partnerorszag'));
            if ($ck) {
                $partnerobj->setOrszag($ck);
            }
            $ck = \mkw\store::getEm()->getRepository(Orszag::class)->find($this->params->getIntRequestParam('partnerszallorszag'));
            if ($ck) {
                $partnerobj->setSzallorszag($ck);
            }
            $partnerobj->setSzallnev($this->params->getStringRequestParam('szallnev'));
            $partnerobj->setSzallirszam($this->params->getStringRequestParam('szallirszam'));
            $partnerobj->setSzallvaros($this->params->getStringRequestParam('szallvaros'));
            $partnerobj->setSzallutca($this->params->getStringRequestParam('szallutca'));
            $partnerobj->setSzallhazszam($this->params->getStringRequestParam('szallhazszam'));
            $partnerobj->setVatstatus($this->params->getIntRequestParam('partnervatstatus'));
            $partnerobj->setSzamlatipus($this->params->getIntRequestParam('partnerszamlatipus'));
            $ck = \mkw\store::getEm()->getRepository(Szallitasimod::class)->find($this->params->getIntRequestParam('szallitasimod'));
            if ($ck) {
                $partnerobj->setSzallitasimod($ck);
            }
            $ck = \mkw\store::getEm()->getRepository(Valutanem::class)->find($this->params->getIntRequestParam('valutanem'));
            if ($ck) {
                $partnerobj->setValutanem($ck);
            }
            $partnerobj->setBizonylatnyelv($this->params->getStringRequestParam('bizonylatnyelv', \mkw\store::getAdminDataLocale()));

            $kiskercimkeid = \mkw\store::getParameter(\mkw\consts::KiskerCimke);
            if ($kiskercimkeid) {
                $kiskercimke = $this->getRepo(Partnercimketorzs::class)->find($kiskercimkeid);
                $partnerobj->addCimke($kiskercimke);
            }
            $this->getEm()->persist($partnerobj);
        } else {
            $partnerobj = $this->getRepo(Partner::class)->find($partnerkod);
            if ($partnerobj) {
                $partnerobj->setAdoszam($this->params->getStringRequestParam('partneradoszam'));
                $partnerobj->setEuadoszam($this->params->getStringRequestParam('partnereuadoszam'));
                $partnerobj->setThirdadoszam($this->params->getStringRequestParam('partnerthirdadoszam'));
                $partnerobj->setVatstatus($this->params->getIntRequestParam('partnervatstatus'));

                $partnerobj->setTelefon($this->params->getStringRequestParam('partnertelefon'));
                $partnerobj->setVezeteknev($this->params->getStringRequestParam('partnervezeteknev'));
                $partnerobj->setKeresztnev($this->params->getStringRequestParam('partnerkeresztnev'));
                if (!$partnerobj->getNev() && ($partnerobj->getVezeteknev() || $partnerobj->getKeresztnev())) {
                    $partnerobj->setNev($partnerobj->getVezeteknev() . ' ' . $partnerobj->getKeresztnev());
                }
                $ck = \mkw\store::getEm()->getRepository(Orszag::class)->find($this->params->getIntRequestParam('partnerorszag'));
                if ($ck) {
                    $partnerobj->setOrszag($ck);
                }
                $partnerobj->setIrszam($this->params->getStringRequestParam('partnerirszam'));
                $partnerobj->setVaros($this->params->getStringRequestParam('partnervaros'));
                $partnerobj->setUtca($this->params->getStringRequestParam('partnerutca'));
                $partnerobj->setHazszam($this->params->getStringRequestParam('partnerhazszam'));
                $ck = \mkw\store::getEm()->getRepository(Orszag::class)->find($this->params->getIntRequestParam('partnerszallorszag'));
                if ($ck) {
                    $partnerobj->setSzallorszag($ck);
                }
                $partnerobj->setSzallnev($this->params->getStringRequestParam('szallnev'));
                $partnerobj->setSzallirszam($this->params->getStringRequestParam('szallirszam'));
                $partnerobj->setSzallvaros($this->params->getStringRequestParam('szallvaros'));
                $partnerobj->setSzallutca($this->params->getStringRequestParam('szallutca'));
                $partnerobj->setSzallhazszam($this->params->getStringRequestParam('szallhazszam'));
                $this->getEm()->persist($partnerobj);
            }
        }

        $obj->setBizonylattipus($this->getRepo(Bizonylattipus::class)->find($this->biztipus));

        $obj->setPersistentData(); // a biz. állandó adatait tölti fel (biz.tip-ból, tulaj adatok)

        $obj->setForditottadozas($this->params->getBoolRequestParam('forditottadozas'));

        if ($partnerkod > 0) {
            if ($partnerobj) {
                $obj->setPartner($partnerobj);
            }
        } else {
            $obj->setPartner($partnerobj);
        }
        $ck = \mkw\store::getEm()->getRepository(Raktar::class)->find($this->params->getIntRequestParam('raktar'));
        if ($ck) {
            $obj->setRaktar($ck);
        }
        $ck = \mkw\store::getEm()->getRepository(Fizmod::class)->find($this->params->getIntRequestParam('fizmod'));
        if ($ck) {
            $obj->setFizmod($ck);
        }
        $ck = \mkw\store::getEm()->getRepository(Szallitasimod::class)->find($this->params->getIntRequestParam('szallitasimod'));
        if ($ck) {
            $obj->setSzallitasimod($ck);
        }
        $ck = \mkw\store::getEm()->getRepository(Uzletkoto::class)->find($this->params->getIntRequestParam('uzletkoto'));
        if ($ck) {
            $obj->setUzletkoto($ck);
        } else {
            $obj->removeUzletkoto();
        }
        $ck = \mkw\store::getEm()->getRepository(Dolgozo::class)->find($this->params->getIntRequestParam('felhasznalo'));
        if ($ck) {
            $obj->setFelhasznalo($ck);
        } else {
            $obj->removeFelhasznalo();
        }
        $ck = \mkw\store::getEm()->getRepository(CsomagTerminal::class)->find($this->params->getIntRequestParam('csomagterminal'));
        if ($ck) {
            $obj->setCsomagterminal($ck);
        } else {
            $obj->removeCsomagterminal();
        }

        if ($this->params->getNumRequestParam('uzletkotojutalek') !== 0) {
            $obj->setUzletkotojutalek($this->params->getNumRequestParam('uzletkotojutalek'));
        }
        $ck = \mkw\store::getEm()->getRepository(Uzletkoto::class)->find($this->params->getIntRequestParam('belsouzletkoto'));
        if ($ck) {
            $obj->setBelsouzletkoto($ck);
        } else {
            $obj->removeBelsouzletkoto();
        }

        if ($this->params->getNumRequestParam('belsouzletkotojutalek') !== 0) {
            $obj->setBelsouzletkotojutalek($this->params->getNumRequestParam('belsouzletkotojutalek'));
        }
        $obj->setKelt($this->params->getStringRequestParam('kelt'));
        $obj->setTeljesites($this->params->getStringRequestParam('teljesites'));
        $obj->setEsedekesseg($this->params->getStringRequestParam('esedekesseg'));
        $obj->setHatarido($this->params->getStringRequestParam('hatarido'));
        $obj->setShipdate($this->params->getStringRequestParam('shipdate'));

        $obj->setErbizonylatszam($this->params->getStringRequestParam('erbizonylatszam'));
        $obj->setFuvarlevelszam($this->params->getStringRequestParam('fuvarlevelszam'));
        $obj->setCsomagcount($this->params->getIntRequestParam('csomagcount'));
        $obj->setKupon($this->params->getStringRequestParam('kupon'));

        $obj->setFakekintlevoseg($this->params->getBoolRequestParam('fakekintlevoseg'));
        $obj->setFakekifizetve($this->params->getBoolRequestParam('fakekifizetve'));
        $obj->setFakekifizetesdatum($this->params->getStringRequestParam('fakekifizetesdatum'));

        $obj->setPartnernev($this->params->getStringRequestParam('partnernev'));
        $obj->setPartneradoszam($this->params->getStringRequestParam('partneradoszam'));
        $obj->setPartnereuadoszam($this->params->getStringRequestParam('partnereuadoszam'));
        $obj->setPartnerthirdadoszam($this->params->getStringRequestParam('partnerthirdadoszam'));
        $obj->setPartnerirszam($this->params->getStringRequestParam('partnerirszam'));
        $obj->setPartnervaros($this->params->getStringRequestParam('partnervaros'));
        $obj->setPartnerutca($this->params->getStringRequestParam('partnerutca'));
        $obj->setPartnerhazszam($this->params->getStringRequestParam('partnerhazszam'));
        $obj->setPartnertelefon($this->params->getStringRequestParam('partnertelefon'));
        $obj->setPartneremail($this->params->getStringRequestParam('partneremail'));
        $obj->setPartnervatstatus($this->params->getIntRequestParam('partnervatstatus'));
        $obj->setPartnerSzamlatipus($this->params->getIntRequestParam('partnerszamlatipus'));

        $obj->setSzallnev($this->params->getStringRequestParam('szallnev'));
        $obj->setSzallirszam($this->params->getStringRequestParam('szallirszam'));
        $obj->setSzallvaros($this->params->getStringRequestParam('szallvaros'));
        $obj->setSzallutca($this->params->getStringRequestParam('szallutca'));
        $obj->setSzallhazszam($this->params->getStringRequestParam('szallhazszam'));

        $obj->setSzepkartyaszam($this->params->getStringRequestParam('szepkartyaszam'));
        $obj->setSzepkartyanev($this->params->getStringRequestParam('szepkartyanev'));
        $obj->setSzepkartyatipus($this->params->getIntRequestParam('szepkartyatipus'));
        $obj->setSzepkartyaervenyesseg($this->params->getStringRequestParam('szepkartyaervenyesseg'));

        $obj->setBizonylatnyelv($this->params->getStringRequestParam('bizonylatnyelv', \mkw\store::getAdminDataLocale()));
        $obj->setReportfile($this->params->getStringRequestParam('reportfile'));

        if (!$obj->getAfaellenorzesnemkell() && $this->params->getBoolRequestParam('afaellenorzesnemkell', false)) {
            $obj->setAfaellenorzesnemkell(true);
            $obj->setAfaellenorzesnemkellby(\mkw\store::getLoggedInDolgozo());
            $obj->setAfaellenorzesnemkellon(new \DateTime());
        }

        $ck = \mkw\store::getEm()->getRepository(Valutanem::class)->find($this->params->getIntRequestParam('valutanem'));
        if ($ck) {
            $obj->setValutanem($ck);
        }

        $obj->setArfolyam($this->params->getNumRequestParam('arfolyam'));

        $ck = \mkw\store::getEm()->getRepository(Bankszamla::class)->find($this->params->getIntRequestParam('bankszamla'));
        if ($ck) {
            $obj->setBankszamla($ck);
        }

        $ck = \mkw\store::getEm()->getRepository(Bizonylatstatusz::class)->find($this->params->getIntRequestParam('bizonylatstatusz'));
        if ($ck) {
            $obj->setBizonylatstatusz($ck);
        }

        $obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
        $obj->setBelsomegjegyzes($this->params->getStringRequestParam('belsomegjegyzes'));
        $obj->setWebshopmessage($this->params->getStringRequestParam('webshopmessage'));
        $obj->setCouriermessage($this->params->getStringRequestParam('couriermessage'));

        $obj->setKellszallitasikoltsegetszamolni($this->params->getBoolRequestParam('szallitasiktgkell'));
        $obj->setSzallitasikoltsegbrutto(0);

        $dokids = $this->params->getArrayRequestParam('dokid');
        foreach ($dokids as $dokid) {
            if (($this->params->getStringRequestParam('dokurl_' . $dokid, '') !== '') ||
                ($this->params->getStringRequestParam('dokpath_' . $dokid, '') !== '')) {
                $dokoper = $this->params->getStringRequestParam('dokoper_' . $dokid);
                if ($dokoper === 'add') {
                    $dok = new \Entities\BizonylatDok();
                    $obj->addBizonylatDok($dok);
                    $dok->setUrl($this->params->getStringRequestParam('dokurl_' . $dokid));
                    $dok->setPath($this->params->getStringRequestParam('dokpath_' . $dokid));
                    $dok->setLeiras($this->params->getStringRequestParam('dokleiras_' . $dokid));
                    $this->getEm()->persist($dok);
                } elseif ($dokoper === 'edit') {
                    $dok = \mkw\store::getEm()->getRepository(BizonylatDok::class)->find($dokid);
                    if ($dok) {
                        $dok->setUrl($this->params->getStringRequestParam('dokurl_' . $dokid));
                        $dok->setPath($this->params->getStringRequestParam('dokpath_' . $dokid));
                        $dok->setLeiras($this->params->getStringRequestParam('dokleiras_' . $dokid));
                        $this->getEm()->persist($dok);
                    }
                }
            }
        }

        switch ($parancs) {
            case $this->inheritOperation:
                $parentbiz = $this->getRepo()->find($this->params->getStringRequestParam('parentid'));
                if ($parentbiz) {
                    $obj->setParbizonylatfej($parentbiz);
                }
                $obj->setNaveredmeny(null);
                break;
            case $this->stornoOperation:
                $obj->setSysmegjegyzes($this->params->getStringRequestParam('parentid') . ' stornó bizonylata.');
                $obj->setNaveredmeny(null);
                $obj->setStorno(true);
                $obj->setStornotipus($this->params->getIntRequestParam('stornotip'));
                switch ($obj->getStornotipus()) {
                    case 0:
                        $obj->setBizonylatnev('Storno számla');
                        break;
                    case 1:
                        $obj->setBizonylatnev('Számlával egy tekintet alá eső okirat');
                        break;
                    case 2:
                        $obj->setBizonylatnev('Érvénytelenítő számla');
                        break;
                }

                $parentbiz = $this->getRepo()->find($this->params->getStringRequestParam('parentid'));
                if ($parentbiz) {
                    $obj->setParbizonylatfej($parentbiz);
                }
                break;
        }

        $quick = $this->params->getBoolRequestParam('quick');
        $tetelids = $this->params->getArrayRequestParam('tetelid');
        $biztetelcontroller = new bizonylattetelController();
        foreach ($tetelids as $tetelid) {
            if (($this->params->getIntRequestParam('teteltermek_' . $tetelid) > 0)) {
                $oper = $this->params->getStringRequestParam('teteloper_' . $tetelid);

                $termek = $this->getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('teteltermek_' . $tetelid));
                if ($termek) {
                    $termekvaltozat = $this->getEm()->getRepository(TermekValtozat::class)->find(
                        $this->params->getIntRequestParam('tetelvaltozat_' . $tetelid)
                    );
                    switch ($oper) {
                        case $this->addOperation:
                        case $this->inheritOperation:
                        case $this->stornoOperation:
                            $tetel = new Bizonylattetel();
                            $obj->addBizonylattetel($tetel);
                            $tetel->setPersistentData();
                            $tetel->setArvaltoztat(0);
                            if ($termek) {
                                $tetel->setTermek($termek);
                            }
                            if ($termekvaltozat) {
                                $tetel->setTermekvaltozat($termekvaltozat);
                            }
                            $tetel->setElolegtipus($this->params->getIntRequestParam('tetelelolegtipus_' . $tetelid));

                            if (!$quick) {
                                $tetel->setTermeknev($this->params->getStringRequestParam('tetelnev_' . $tetelid));
                                $tetel->setCikkszam($this->params->getStringRequestParam('tetelcikkszam_' . $tetelid));
                                $tetel->setMegjegyzes($this->params->getStringRequestParam('tetelmegjegyzes_' . $tetelid));
                                $tetel->setMegjegyzes2($this->params->getStringRequestParam('tetelmegjegyzes2_' . $tetelid));
                                $tetel->setTermekegyediazonosito($this->params->getStringRequestParam('teteltermekegyediazonosito_' . $tetelid));
                                $tetel->setVasarlasdatum($this->params->getStringRequestParam('tetelvasarlasdatum_' . $tetelid));
                                $tetel->setVtsz($this->params->getIntRequestParam('tetelvtsz_' . $tetelid));
                                $tetel->setAfa($this->params->getIntRequestParam('tetelafa_' . $tetelid));
                                $tetel->setMekod($this->params->getIntRequestParam('tetelme_' . $tetelid));
                                $parenttetel = $this->getRepo(Bizonylattetel::class)->find(
                                    $this->params->getStringRequestParam('tetelparentid_' . $tetelid)
                                );
                                if ($parenttetel) {
                                    $tetel->setParbizonylattetel($parenttetel);
                                }
                                $tetel->setKedvezmeny($this->params->getFloatRequestParam('tetelkedvezmeny_' . $tetelid));
                                $tetel->setMennyiseg($this->params->getFloatRequestParam('tetelmennyiseg_' . $tetelid));
                                $tetel->setNettoegysar($this->params->getFloatRequestParam('tetelnettoegysar_' . $tetelid));
                                $tetel->setBruttoegysar($this->params->getFloatRequestParam('tetelbruttoegysar_' . $tetelid));
                                $tetel->setEnettoegysar($this->params->getFloatRequestParam('tetelenettoegysar_' . $tetelid));
                                $tetel->setEbruttoegysar($this->params->getFloatRequestParam('tetelebruttoegysar_' . $tetelid));
                                $tetel->setNetto($this->params->getFloatRequestParam('tetelnetto_' . $tetelid));
                                $tetel->setBrutto($this->params->getFloatRequestParam('tetelbrutto_' . $tetelid));
                                $tetel->setAfaertek($tetel->getBrutto() - $tetel->getNetto());

                                if (\mkw\store::isMultiValuta()) {
                                    $tetel->setNettoegysarhuf($this->params->getFloatRequestParam('tetelnettoegysarhuf_' . $tetelid));
                                    $tetel->setBruttoegysarhuf($this->params->getFloatRequestParam('tetelbruttoegysarhuf_' . $tetelid));
                                    $tetel->setEnettoegysarhuf($this->params->getFloatRequestParam('tetelenettoegysarhuf_' . $tetelid));
                                    $tetel->setEbruttoegysarhuf($this->params->getFloatRequestParam('tetelebruttoegysarhuf_' . $tetelid));
                                    $tetel->setNettohuf($this->params->getFloatRequestParam('tetelnettohuf_' . $tetelid));
                                    $tetel->setBruttohuf($this->params->getFloatRequestParam('tetelbruttohuf_' . $tetelid));
                                    $tetel->setAfaertekhuf($tetel->getBruttohuf() - $tetel->getNettohuf());
                                } else {
                                    $tetel->setNettoegysarhuf($tetel->getNettoegysar());
                                    $tetel->setBruttoegysarhuf($tetel->getBruttoegysar());
                                    $tetel->setEnettoegysarhuf($tetel->getEnettoegysar());
                                    $tetel->setEbruttoegysarhuf($tetel->getEbruttoegysar());
                                    $tetel->setNettohuf($tetel->getNetto());
                                    $tetel->setBruttohuf($tetel->getBrutto());
                                    $tetel->setAfaertekhuf($tetel->getAfaertek());
                                }

                                $tetel->setHatarido($this->params->getStringRequestParam('tetelhatarido_' . $tetelid));
                                $tetel->setArfolyam($this->params->getFloatRequestParam('arfolyam'));

                                if ($oper == $this->stornoOperation) {
                                    $tetel->setStorno(true);
                                    $tetel->setStornoMozgat($this->params->getBoolRequestParam('tetelmozgat_' . $tetelid));
                                }
                            } else {
                                $tetel->setKedvezmeny($this->params->getFloatRequestParam('tetelkedvezmeny_' . $tetelid));
                                $tetel->setAfa($this->params->getIntRequestParam('tetelafa_' . $tetelid));
                                $tetel->setArfolyam($this->params->getFloatRequestParam('arfolyam'));
                                $tetel->setMennyiseg($this->params->getFloatRequestParam('tetelmennyiseg_' . $tetelid));
                                $tetel->setNettoegysar($this->params->getFloatRequestParam('tetelnettoegysar_' . $tetelid));
                                $tetel->setBruttoegysar($this->params->getFloatRequestParam('tetelbruttoegysar_' . $tetelid));
                                $tetel->setEnettoegysar($this->params->getFloatRequestParam('tetelenettoegysar_' . $tetelid));
                                $tetel->setEbruttoegysar($this->params->getFloatRequestParam('tetelebruttoegysar_' . $tetelid));
                                $arak = $biztetelcontroller->calcAr(
                                    $tetel->getAfaId(),
                                    $tetel->getArfolyam(),
                                    $tetel->getNettoegysar(),
                                    $tetel->getEnettoegysar(),
                                    $tetel->getMennyiseg()
                                );
                                $tetel->setNetto($arak['netto']);
                                $tetel->setBrutto($arak['brutto']);
                                $tetel->setAfaertek($arak['afa']);
                                $tetel->setNettoegysarhuf($arak['nettoegysarhuf']);
                                $tetel->setBruttoegysarhuf($arak['bruttoegysarhuf']);
                                $tetel->setEnettoegysarhuf($arak['enettoegysarhuf']);
                                $tetel->setEbruttoegysarhuf($arak['ebruttoegysarhuf']);
                                $tetel->setNettohuf($arak['nettohuf']);
                                $tetel->setBruttohuf($arak['bruttohuf']);
                                $tetel->setAfaertekhuf($arak['afahuf']);
                                $tetel->setHatarido($this->params->getStringRequestParam('tetelhatarido_' . $tetelid));
                            }

                            $this->getEm()->persist($tetel);
                            break;
                        case $this->editOperation:
                            $tetel = $this->getEm()->getRepository(Bizonylattetel::class)->find($tetelid);
                            if ($tetel) {
                                $tetel->setPersistentData();
                                if ($termek) {
                                    $tetel->setTermek($termek);
                                }
                                if ($termekvaltozat) {
                                    $tetel->setTermekvaltozat($termekvaltozat);
                                }
                                $tetel->setElolegtipus($this->params->getIntRequestParam('tetelelolegtipus_' . $tetelid));

                                if (!$quick) {
                                    $tetel->setKedvezmeny($this->params->getFloatRequestParam('tetelkedvezmeny_' . $tetelid));
                                    $tetel->setTermeknev($this->params->getStringRequestParam('tetelnev_' . $tetelid));
                                    $tetel->setCikkszam($this->params->getStringRequestParam('tetelcikkszam_' . $tetelid));
                                    $tetel->setMegjegyzes($this->params->getStringRequestParam('tetelmegjegyzes_' . $tetelid));
                                    $tetel->setMegjegyzes2($this->params->getStringRequestParam('tetelmegjegyzes2_' . $tetelid));
                                    $tetel->setTermekegyediazonosito($this->params->getStringRequestParam('teteltermekegyediazonosito_' . $tetelid));
                                    $tetel->setVasarlasdatum($this->params->getStringRequestParam('tetelvasarlasdatum_' . $tetelid));
                                    $tetel->setVtsz($this->params->getIntRequestParam('tetelvtsz_' . $tetelid));
                                    $tetel->setAfa($this->params->getIntRequestParam('tetelafa_' . $tetelid));
                                    $tetel->setMekod($this->params->getIntRequestParam('tetelme_' . $tetelid));
                                    $tetel->setMennyiseg($this->params->getFloatRequestParam('tetelmennyiseg_' . $tetelid));

                                    $tetel->setNettoegysar($this->params->getFloatRequestParam('tetelnettoegysar_' . $tetelid));
                                    $tetel->setBruttoegysar($this->params->getFloatRequestParam('tetelbruttoegysar_' . $tetelid));
                                    $tetel->setEnettoegysar($this->params->getFloatRequestParam('tetelenettoegysar_' . $tetelid));
                                    $tetel->setEbruttoegysar($this->params->getFloatRequestParam('tetelebruttoegysar_' . $tetelid));
                                    $tetel->setNetto($this->params->getFloatRequestParam('tetelnetto_' . $tetelid));
                                    $tetel->setBrutto($this->params->getFloatRequestParam('tetelbrutto_' . $tetelid));
                                    $tetel->setAfaertek($tetel->getBrutto() - $tetel->getNetto());

                                    if (\mkw\store::isMultiValuta()) {
                                        $tetel->setNettoegysarhuf($this->params->getFloatRequestParam('tetelnettoegysarhuf_' . $tetelid));
                                        $tetel->setBruttoegysarhuf($this->params->getFloatRequestParam('tetelbruttoegysarhuf_' . $tetelid));
                                        $tetel->setEnettoegysarhuf($this->params->getFloatRequestParam('tetelenettoegysarhuf_' . $tetelid));
                                        $tetel->setEbruttoegysarhuf($this->params->getFloatRequestParam('tetelebruttoegysarhuf_' . $tetelid));
                                        $tetel->setNettohuf($this->params->getFloatRequestParam('tetelnettohuf_' . $tetelid));
                                        $tetel->setBruttohuf($this->params->getFloatRequestParam('tetelbruttohuf_' . $tetelid));
                                        $tetel->setAfaertekhuf($tetel->getBruttohuf() - $tetel->getNettohuf());
                                    } else {
                                        $tetel->setNettoegysarhuf($tetel->getNettoegysar());
                                        $tetel->setBruttoegysarhuf($tetel->getBruttoegysar());
                                        $tetel->setEnettoegysarhuf($tetel->getEnettoegysar());
                                        $tetel->setEbruttoegysarhuf($tetel->getEbruttoegysar());
                                        $tetel->setNettohuf($tetel->getNetto());
                                        $tetel->setBruttohuf($tetel->getBrutto());
                                        $tetel->setAfaertekhuf($tetel->getAfaertek());
                                    }

                                    $tetel->setHatarido($this->params->getStringRequestParam('tetelhatarido_' . $tetelid));
                                    $tetel->setArfolyam($this->params->getFloatRequestParam('arfolyam'));
                                } else {
                                    $tetel->setKedvezmeny($this->params->getFloatRequestParam('tetelkedvezmeny_' . $tetelid));
                                    $tetel->setAfa($this->params->getIntRequestParam('tetelafa_' . $tetelid));
                                    $tetel->setArfolyam($this->params->getFloatRequestParam('arfolyam'));
                                    $tetel->setMennyiseg($this->params->getFloatRequestParam('tetelmennyiseg_' . $tetelid));
                                    $tetel->setNettoegysar($this->params->getFloatRequestParam('tetelnettoegysar_' . $tetelid));
                                    $tetel->setBruttoegysar($this->params->getFloatRequestParam('tetelbruttoegysar_' . $tetelid));
                                    $tetel->setEnettoegysar($this->params->getFloatRequestParam('tetelenettoegysar_' . $tetelid));
                                    $tetel->setEbruttoegysar($this->params->getFloatRequestParam('tetelebruttoegysar_' . $tetelid));
                                    $arak = $biztetelcontroller->calcAr(
                                        $tetel->getAfaId(),
                                        $tetel->getArfolyam(),
                                        $tetel->getNettoegysar(),
                                        $tetel->getEnettoegysar(),
                                        $tetel->getMennyiseg()
                                    );
                                    $tetel->setNetto($arak['netto']);
                                    $tetel->setBrutto($arak['brutto']);
                                    $tetel->setAfaertek($arak['afa']);
                                    $tetel->setNettoegysarhuf($arak['nettoegysarhuf']);
                                    $tetel->setBruttoegysarhuf($arak['bruttoegysarhuf']);
                                    $tetel->setEnettoegysarhuf($arak['enettoegysarhuf']);
                                    $tetel->setEbruttoegysarhuf($arak['ebruttoegysarhuf']);
                                    $tetel->setNettohuf($arak['nettohuf']);
                                    $tetel->setBruttohuf($arak['bruttohuf']);
                                    $tetel->setAfaertekhuf($arak['afahuf']);
                                    $tetel->setHatarido($this->params->getStringRequestParam('tetelhatarido_' . $tetelid));
                                }

                                $this->getEm()->persist($tetel);
                            }
                            break;
                    }
                } else {
                    \mkw\store::writelog(print_r($this->params->asArray(), true), 'nincstermek.log');
                }
            }
        }
        return $obj;
    }

    protected function afterSave($o, $parancs = null)
    {
        $oper = $this->params->getStringRequestParam('oper');
        parent::afterSave($o, $parancs);
        if ($oper === 'storno') {
            $parentid = $this->params->getStringRequestParam('parentid');
            if ($parentid) {
                $parent = $this->getRepo()->find($parentid);
                if ($parent) {
                    $parent->setKellszallitasikoltsegetszamolni(false);
                    $parent->setStornozott(true);
                    $this->getEm()->persist($parent);
                    $this->getEm()->flush();
                }
            }
        }
        if ($this->params->getBoolRequestParam('bizonylatstatuszertesito')) {
            $statusz = $o->getBizonylatstatusz();
            if ($statusz) {
                $emailtpl = $statusz->getEmailtemplate();
                $o->sendStatuszEmail($emailtpl);
            }
        }
    }

    /**
     * A negatív irányú bizonylatok egyedi azonosító autocomplete-jének forrása: a termékhez (és
     * választott változatához / raktárához) tartozó, készleten lévő egyedi azonosítók.
     */
    public function egyediAzonositoKeszlet()
    {
        $termekid = $this->params->getIntRequestParam('termekid');
        $valtozatid = $this->params->getIntRequestParam('valtozatid');
        $raktarid = $this->params->getIntRequestParam('raktarid');
        $term = trim($this->params->getStringRequestParam('term'));
        $ret = [];
        if ($termekid) {
            /** @var \Entities\Termek $termek */
            $termek = $this->getRepo(Termek::class)->find($termekid);
            if ($termek) {
                $ret = $termek->getEgyediazonositoKeszlet($valtozatid, $term, $raktarid);
            }
        }
        echo json_encode($ret);
    }

    public function checkKelt()
    {
        $ret = ['response' => 'error'];
        $keltstr = \mkw\store::convDate($this->params->getStringRequestParam('kelt'));
        $kelt = strtotime($keltstr);
        $biztipid = $this->params->getStringRequestParam('biztipus');
        $bizszam = $this->params->getStringRequestParam('bizszam');
        /** @var \Entities\Bizonylattipus $bt */
        $bt = $this->getRepo(Bizonylattipus::class)->find($biztipid);
        if ($bt) {
            if ($bt->getCheckkelt()) {
                if (!$bizszam) {
                    $filter = new \mkwhelpers\FilterDescriptor();
                    $filter
                        ->addFilter('bizonylattipus', '=', $bt)
                        ->addFilter('kelt', '>', $keltstr)
                        ->addSql('(YEAR(_xx.kelt)=' . date('Y', $kelt) . ')');
                    $db = $this->getRepo()->getCount($filter);
                    if ($db == 0) {
                        $ret = ['response' => 'ok'];
                    }
                } else {
                    $biz = $this->getRepo()->find($bizszam);
                    if ($biz && date('Y', $kelt) === $biz->getKelt()->format('Y')) {
                        $prevbizszam = \Entities\Bizonylatfej::getPrevId($bizszam);
                        $nextbizszam = \Entities\Bizonylatfej::getNextId($bizszam);
                        $prevbiz = $this->getRepo()->find($prevbizszam);
                        $nextbiz = $this->getRepo()->find($nextbizszam);
                        $prevok = ($prevbiz && ($prevbiz->getKelt()->format(\mkw\store::$SQLDateFormat) <= date(
                                        \mkw\store::$SQLDateFormat,
                                        $kelt
                                    ))) || !$prevbiz;
                        $nextok = ($nextbiz && ($nextbiz->getKelt()->format(\mkw\store::$SQLDateFormat) >= date(
                                        \mkw\store::$SQLDateFormat,
                                        $kelt
                                    ))) || !$nextbiz;
                        if ($prevok && $nextok) {
                            $ret = ['response' => 'ok'];
                        }
                    }
                }
            } else {
                $ret = ['response' => 'ok'];
            }
        }
        echo json_encode($ret);
    }

    public function calcEsedekesseg()
    {
        $kelt = $this->params->getStringRequestParam('kelt');
        $fm = $this->getRepo(Fizmod::class)->find($this->params->getIntRequestParam('fizmod'));
        $partner = $this->getRepo(Partner::class)->find($this->params->getIntRequestParam('partner'));
        echo json_encode(['esedekesseg' => \mkw\store::calcEsedekesseg($kelt, $fm, $partner)]);
    }

    /**
     * Az összesítők (nettó, áfa, bruttó, fizetendő, kerekítési különbözet) kiszámítása a kliensről
     * kapott tételértékekből, a Bizonylatfej entitásfüggetlen összegzőjével
     * (Bizonylatfej::calcOsszesenFromTetelek) – kliensoldali összegző/kerekítő nélkül. A megjelenítendő
     * nettó/bruttó összeget a bruttó kerekítése szerint, formázva is visszaadja.
     *
     * Bemenet: netto[], brutto[] (tételsoronkénti értékek), fizmod, opcionálisan valutanem.
     */
    public function calcosszesen()
    {
        $defavaluta = $this->getRepo(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
        $valutanem = $defavaluta;
        $vid = $this->params->getIntRequestParam('valutanem');
        if ($vid) {
            $v = $this->getRepo(Valutanem::class)->find($vid);
            if ($v) {
                $valutanem = $v;
            }
        }
        $kerekit = $valutanem ? (bool)$valutanem->getKerekit() : false;
        $mincimlet = $valutanem ? (float)$valutanem->getMincimlet() : 0;
        $defakerekit = $defavaluta ? (bool)$defavaluta->getKerekit() : false;

        $fizmod = $this->getRepo(Fizmod::class)->find($this->params->getIntRequestParam('fizmod'));
        $keszpenz = $fizmod ? ($fizmod->getTipus() == 'P') : false;

        $nettok = $this->params->getArrayRequestParam('netto');
        $bruttok = $this->params->getArrayRequestParam('brutto');
        $tetelek = [];
        foreach ($nettok as $i => $n) {
            $lnetto = (float)str_replace(',', '.', $n);
            $lbrutto = isset($bruttok[$i]) ? (float)str_replace(',', '.', $bruttok[$i]) : $lnetto;
            $tetelek[] = ['netto' => $lnetto, 'afaertek' => $lbrutto - $lnetto];
        }

        $o = BizonylatCalculatorService::calcOsszesenFromTetelek($tetelek, [
            'kerekit' => $kerekit,
            'mincimlet' => $mincimlet,
            'keszpenz' => $keszpenz,
            'defakerekit' => $defakerekit,
        ]);

        $egesz = $kerekit || ($mincimlet && $keszpenz);
        $nettoKerekitett = BizonylatCalculatorService::kerekitBrutto($o['netto'], $kerekit, $mincimlet, $keszpenz);
        echo json_encode([
            'netto' => $o['netto'],
            'brutto' => $o['brutto'],
            'fizetendo' => $o['fizetendo'],
            'kerkul' => $o['kerkul'],
            'nettosum' => number_format($nettoKerekitett, $egesz ? 0 : 2, '.', ' '),
            'bruttosum' => number_format($o['brutto'], $egesz ? 0 : 2, '.', ' '),
        ]);
    }

    public function ront()
    {
        $id = $this->params->getStringRequestParam('id');
        if ($id) {
            /** @var Bizonylatfej $bf */
            $bf = $this->getRepo()->find($id);
            if ($bf) {
                $bf->setKellszallitasikoltsegetszamolni(false);
                $bf->setRontott(true);
                $this->getEm()->persist($bf);
                $this->getEm()->flush();
            }
        }
    }

    public function getlistbody()
    {
        $view = $this->createView('bizonylatfejlista_tbody.tpl');

        $this->setVars($view);

        $filter = new \mkwhelpers\FilterDescriptor();
        $this->loadFilters($filter);

        $filter->addFilter('bizonylattipus', '=', $this->getRepo(Bizonylattipus::class)->find($this->biztipus));

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        $ki = $this->loadDataToView($egyedek, 'egyedlista', $view);

        $sumview = $this->createView('bizonylatfejsum.tpl');
        $this->setVars($sumview);
        $sum = $this->getRepo()->calcSumWithJoins($filter);
        $sumview->setVar('sum', $sum);

        $ki['sumhtml'] = $sumview->getTemplateResult();

        echo json_encode($ki);
    }

    public function getBizonylatHTML($id)
    {
        /** @var Bizonylatfej $o */
        $o = $this->getRepo()->findForPrint($id);
        if ($o) {
            if (!$this->biztipus) {
                $this->biztipus = $o->getBizonylattipus()->getId();
            }
            $bt = $this->getRepo(Bizonylattipus::class)->find($this->biztipus);
            if ($o->getReportfile()) {
                $tplname = $o->getReportfile();
            } elseif ($bt) {
                $tplname = $bt->getLocalizedFieldValue('tplname', $o->getBizonylatnyelv());
                if (!$tplname) {
                    $tplname = $bt->getTplname();
                }
            }
            $view = $this->createView($tplname);
            $bt->setTemplateVars($view);
            $x = $o->toLista();
            $view->setVar('egyed', $x);
            $view->setVar('afaosszesito', $this->getRepo()->getAFAOsszesito($o));
            return $view->getTemplateResult();
        }
    }

    public function doPrint()
    {
        echo $this->getBizonylatHTML($this->params->getStringRequestParam('id'));
    }

    public function doPDF()
    {
        if (\mkw\store::isPDF()) {
            $id = $this->params->getStringRequestParam('id');
            $printed = $this->params->getBoolRequestParam('printed', false);
            /** @var \Entities\Bizonylatfej $o */
            $o = $this->getRepo()->find($id);
            if ($o) {
                $this->biztipus = $o->getBizonylattipusId();
                $html = $this->getBizonylatHTML($id);
                $pdf = \mkw\store::getPDFEngine($html);
                $pdf->send(\mkw\store::urlize($id) . '.pdf');
                if ($printed !== false) {
                    $this->setNyomtatva($id, true);
                }
            }
        }
    }

    public function sendPDF()
    {
        if (\mkw\store::isPDF()) {
            $id = $this->params->getStringRequestParam('id');
            /** @var \Entities\Bizonylatfej $o */
            $o = $this->getRepo()->find($id);
            if ($o) {
                $this->biztipus = $o->getBizonylattipusId();
                $email = $o->getPartneremail();
                if ($email) {
                    $emailtpl = $this->getRepo(Emailtemplate::class)->find(\mkw\store::getParameter(\mkw\consts::SzamlalevelSablon));
                    $html = $this->getBizonylatHTML($id);
                    $pdf = \mkw\store::getPDFEngine($html);
                    if ($email && $emailtpl) {
                        $filepath = \mkw\store::storagePath(\mkw\store::urlize($id) . '.pdf');
                        $pdf->saveAs($filepath);

                        $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                        $body = \mkw\store::getTemplateFactory()->createMainView(
                            'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
                        );
                        $body->setVar('szamla', $o->toLista());
                        $body->setVar('megszolitas', $o->getPartner()->getSzamlalevelmegszolitas());


                        $mailer = \mkw\store::getMailer();

                        $mailer->setAttachment($filepath);
                        $mailer->addTo($email);
                        $mailer->setSubject($subject->getTemplateResult());
                        $mailer->setMessage($body->getTemplateResult());

                        $mailer->send();

                        \unlink($filepath);
                    }
                }
            }
        }
    }

    public function getFiokList($forfouk = false)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('bizonylattipus', '=', $this->getRepo(Bizonylattipus::class)->find($this->biztipus));
        $filter->addFilter('rontott', '=', false);

        if ($forfouk) {
            $ukrepo = $this->getRepo(Uzletkoto::class);
            $ukid = $ukrepo->getLoggedInUK();
            if ($ukid) {
                $uk = $ukrepo->find($ukid);
            }
            if ($uk && $uk->getFo()) {
                $uklist = $ukrepo->getByFouzletkoto($ukid);
                $ukarr = [];
                foreach ($uklist as $uo) {
                    $ukarr[] = $uo->getId();
                }
                $filter->addFilter('uzletkoto', 'IN', $ukarr);
            } else {
                $filter->addSql('1=0');
            }
        } else {
            $filter->addFilter('partner', '=', $this->getRepo(Partner::class)->getLoggedInUser());
        }

        $l = $this->getRepo()->getWithJoins($filter, ['kelt' => 'DESC']);
        $ret = [];
        /** @var Bizonylatfej $it */
        foreach ($l as $it) {
            $ret[] = $it->toLista();
        }
        return $ret;
    }

    /**
     * Ha be van állítva, a getkarb ezt a (nem feltétlenül mentett) bizonylatot használja a
     * findWithJoins($id) helyett. Így pl. a bolti eladásból mentés nélkül nyitható számla:
     * egy memóriában felépített forrásbizonylatot adunk át az inherit prefillnek.
     */
    protected $prebuiltRecord = null;

    public function viewkarb()
    {
        $this->getkarb($this->getKarbTplName());
    }

    public function getkarb($tplname = null, $id = null, $oper = null, $quick = null, $stornotip = null)
    {
        if (!$tplname) {
            $tplname = $this->getKarbFormTplName();
        }
        if (!$id) {
            $id = $this->params->getRequestParam('id', 0);
        }
        if (!$oper) {
            $oper = $this->params->getRequestParam('oper', '');
        }
        if (!$quick) {
            $quick = $this->params->getBoolRequestParam('quick');
        }
        if (!$stornotip) {
            $stornotip = $this->params->getIntRequestParam('stornotip');
        }
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', $this->getPageTitle());
        $view->setVar('controllerscript', $this->biztipus . 'fej.js');
        $view->setVar('formaction', '/admin/' . $this->biztipus . 'fej/save');
        $view->setVar('oper', $oper);
        $view->setVar('quick', $quick);
        //       $this->setVars($view);

        /** @var \Entities\Bizonylatfej $record */
        $record = $this->prebuiltRecord ? $this->prebuiltRecord : $this->getRepo()->findWithJoins($id);
        $mehet = true;
        if ($oper === 'storno') {
            if ($record->getNaveredmeny() != '' && $record->getNaveredmeny() !== 'DONE') {
                $mehet = false;
                $view->setVar('nostorno', true);
            }
        }

        if ($mehet) {
            $egyed = $this->loadVars($record, true, $oper);

            $bt = $this->getRepo(Bizonylattipus::class)->find($this->biztipus);
            $bt->setTemplateVars($view);

            if (!\mkw\store::isPartnerAutocomplete()) {
                $partnerc = new partnerController();
                $view->setVar('partnerlist', $partnerc->getSelectList(($record ? $record->getPartnerId() : 0)));
            }

            $raktar = new raktarController();
            if (!$record || !$record->getRaktarId()) {
                $raktarid = \mkw\store::getDefaultRaktarId();
            } else {
                $raktarid = $record->getRaktarId();
            }
            $view->setVar('raktarlist', $raktar->getSelectList($raktarid));

            $fizmod = new fizmodController();
            if (!$record || !$record->getFizmodId()) {
                $fmid = \mkw\store::getParameter(\mkw\consts::Fizmod);
            } else {
                $fmid = $record->getFizmodId();
            }
            $view->setVar('fizmodlist', $fizmod->getSelectList($fmid));

            $szallitasimod = new szallitasimodController();
            if (!$record || !$record->getSzallitasimodId()) {
                $szallmodid = \mkw\store::getParameter(\mkw\consts::Szallitasimod);
            } else {
                $szallmodid = $record->getSzallitasimodId();
            }
            $view->setVar('szallitasimodlist', $szallitasimod->getSelectList($szallmodid, true));

            $valutanem = new valutanemController();
            if (!$record || !$record->getValutanemId()) {
                $valutaid = \mkw\store::getParameter(\mkw\consts::Valutanem, 0);
            } else {
                $valutaid = $record->getValutanemId();
            }
            $view->setVar('valutanemlist', $valutanem->getSelectList($valutaid));

            $bankszla = new bankszamlaController();
            $bankszlaid = false;
            if ($record && $record->getBankszamlaId()) {
                $bankszlaid = $record->getBankszamlaId();
            } else {
                $valutanem = $this->getRepo(Valutanem::class)->find($valutaid);
                if ($valutanem && $valutanem->getBankszamlaId()) {
                    $bankszlaid = $valutanem->getBankszamlaId();
                }
            }

            $view->setVar('bankszamlalist', $bankszla->getSelectList($bankszlaid));

            $uk = new uzletkotoController();
            if ($record && $record->getUzletkotoId()) {
                $ukid = $record->getUzletkotoId();
            }
            $view->setVar('uzletkotolist', $uk->getSelectList($ukid));

            if ($record && $record->getBelsouzletkotoId()) {
                $ukid = $record->getBelsouzletkotoId();
            }
            $fofilter = new \mkwhelpers\FilterDescriptor();
            $fofilter->addFilter('belso', '=', true);
            $view->setVar('belsouzletkotolist', $uk->getSelectList($ukid, $fofilter));

            $view->setVar('esedekessegalap', \mkw\store::getParameter(\mkw\consts::Esedekessegalap, 1));
            $view->setVar(
                'reportfilelist',
                $this->getRepo()->getReportfileSelectList(
                    ($record ? $record->getReportfile() : ''),
                    ($record ? $record->getBizonylattipusId() : $this->biztipus)
                )
            );
            $view->setVar('bizonylatnyelvlist', \mkw\store::getLocaleSelectList(($record ? $record->getBizonylatnyelv() : '')));

            $csomagctrl = new csomagterminalController();
            $szallitasimodobj = $this->getRepo(Szallitasimod::class)->find($szallmodid);
            $view->setVar(
                'csomagterminallist',
                $csomagctrl->getSelectList(($record ? $record->getCsomagterminalId() : 0), ($szallitasimodobj ? $szallitasimodobj->getTerminaltipus() : null))
            );

            $felh = new dolgozoController();
            $view->setVar('felhasznalolist', $felh->getSelectList(($record ? $record->getFelhasznaloId() : 0)));

            $orszagc = new orszagController();
            $view->setVar('orszaglist', $orszagc->getSelectList(($record ? $record->getPartnerorszagId() : 0)));
            $view->setVar('szallorszaglist', $orszagc->getSelectList(($record ? $record->getPartnerszallorszagId() : 0)));

            if ($record) {
                $partner = $record->getPartner();
            }
            $view->setVar('szamlatipuslist', $this->getRepo(Partner::class)->getSzamlatipusList(($partner ? $partner->getSzamlatipus() : 0)));
            $view->setVar(
                'vatstatuslist',
                $this->getRepo(Partner::class)->getVatstatusList(($record ? $record->getPartnervatstatus() : ($partner ? $partner->getVatstatus() : 0)))
            );

            if (method_exists($this, 'onGetKarb')) {
                $egyed = $this->onGetKarb($view, $record, $egyed, $oper, $id, $stornotip);
            }

            $view->setVar('egyed', $egyed);

            $view->setVar('esedekessegalap', \mkw\store::getParameter(\mkw\consts::Esedekessegalap, 1));
        }

        $view->printTemplateResult();
    }

    public function setStatusz()
    {
        /** @var Bizonylatfej $bf */
        $bf = $this->getRepo()->find($this->params->getStringRequestParam('id'));
        if ($bf) {
            $statusz = $this->getRepo(Bizonylatstatusz::class)->find($this->params->getIntRequestParam('statusz'));
            if ($statusz) {
                $bf->setKellszallitasikoltsegetszamolni(false);
                $bf->setSimpleedit(true);
                $bf->setBizonylatstatusz($statusz);
                $this->getEm()->persist($bf);
                $this->getEm()->flush();
                if ($this->params->getBoolRequestParam('bizonylatstatuszertesito')) {
                    $emailtpl = $statusz->getEmailtemplate();
                    $bf->sendStatuszEmail($emailtpl);
                }
            }
        }
    }

    public function setNyomtatva($id = null, $printed = null)
    {
        $httpcall = $id === null && $printed === null;
        if ($id === null) {
            $id = $this->params->getStringRequestParam('id');
        }
        if ($printed === null) {
            $printed = $this->params->getBoolRequestParam('printed');
        }
        /** @var \Entities\Bizonylatfej $bf */
        $bf = $this->getRepo()->find($id);
        if ($bf) {
            $nores = $this->validateWithNAV($id);
            if ($nores !== true) {
                if (!$httpcall) {
                    return $nores;
                } else {
                    echo $nores;
                }
            } else {
                $bf->setKellszallitasikoltsegetszamolni(false);
                $bf->setSimpleedit(true);
                $bf->setNyomtatva($printed);
                $this->getEm()->persist($bf);
                $this->getEm()->flush();
                if ($printed && !$bf->isNavbekuldve()) {
                    $nores = $this->sendToNAV($id);
                    if ($nores) {
                        if (!$httpcall) {
                            return $nores;
                        } else {
                            echo $nores;
                        }
                    }
                }
            }
        }
        if (!$httpcall) {
            return false;
        }
    }

    public function fejexport()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $ids = $this->params->getStringRequestParam('ids');
        if ($ids) {
            $filter->addFilter('id', 'IN', explode(',', $ids));
        }
        $fejek = $this->getRepo()->getWithJoins($filter, []);
        $o = 0;
        $excel = new Spreadsheet();
        if ($this->biztipus === 'megrendeles') {
            $excel->setActiveSheetIndex(0)->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Státusz');
        }
        $excel->setActiveSheetIndex(0)
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Állapot')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Biz.szám')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Kelt')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Teljesítés')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Esedékesség')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Raktár')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Fizetési mód')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Szállítási mód')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Partner neve')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Partner címe')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Partner adószáma')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Nettó')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'ÁFA')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Bruttó')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Valuta')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Árfolyam')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Nettó HUF')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'ÁFA HUF')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Bruttó HUF')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Fuvarlevél');

        if ($fejek) {
            $sor = 2;
            /** @var \Entities\Bizonylatfej $fej */
            foreach ($fejek as $fej) {
                $o = 0;
                if ($this->biztipus === 'megrendeles') {
                    $excel->setActiveSheetIndex(0)->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getBizonylatstatusznev());
                }
                $excel->setActiveSheetIndex(0)
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getStornoStr())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getId())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getKeltStr())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getTeljesitesStr())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getEsedekessegStr())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getRaktarnev())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getFizmodnev())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getSzallitasimodnev())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getPartnernev())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getPartnerCim())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getPartneradoszam())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getNetto())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getAfa())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getBrutto())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getValutanemnev())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getArfolyam())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getNettohuf())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getAfahuf())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getBruttohuf())
                    ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getFuvarlevelszam());

                $sor++;
            }
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('bizonylatfej') . '.xlsx');
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }

    public function tetelexport()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $ids = $this->params->getStringRequestParam('ids');
        if ($ids) {
            $filter->addFilter('id', 'IN', explode(',', $ids));
        }

        if (\mkw\store::isSuperzoneB2B()) {
            $fejek = $this->getRepo()->getWithTetelek($filter, [], 0, 0, \mkw\store::getParameter(\mkw\consts::Locale));
        } else {
            $fejek = $this->getRepo()->getWithTetelek($filter);
        }

        $o = 0;
        $excel = new Spreadsheet();
        if ($this->biztipus === 'megrendeles') {
            $excel->setActiveSheetIndex(0)->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Státusz');
        }
        $excel->setActiveSheetIndex(0)
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Állapot')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Biz.szám')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Kelt')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Teljesítés')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Esedékesség')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Raktár')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Fizetési mód')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Szállítási mód')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Partner neve')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Partner címe')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Partner adószáma')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Nettó')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'ÁFA')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Bruttó')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Valuta')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Árfolyam')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Nettó HUF')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'ÁFA HUF')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Bruttó HUF')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Fuvarlevél')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Cikkszám')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Termék neve')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Változat érték 1')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Változat érték 2')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Mennyiség')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'ME')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Nettó egységár')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Bruttó egységár')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Nettó egységár HUF')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Bruttó egységár HUF')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Nettó érték')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'ÁFA érték')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Bruttó érték')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Nettó érték HUF')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'ÁFA érték HUF')
            ->setCellValue(\mkw\store::getExcelCoordinate($o++, 1), 'Bruttó érték HUF');

        if ($fejek) {
            $sor = 2;
            /** @var \Entities\Bizonylatfej $fej */
            foreach ($fejek as $fej) {
                /** @var \Entities\Bizonylattetel $tetel */
                foreach ($fej->getBizonylattetelek() as $tetel) {
                    $o = 0;
                    if ($this->biztipus === 'megrendeles') {
                        $excel->setActiveSheetIndex(0)->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getBizonylatstatusznev());
                    }
                    $excel->setActiveSheetIndex(0)
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getStornoStr())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getId())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getKeltStr())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getTeljesitesStr())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getEsedekessegStr())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getRaktarnev())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getFizmodnev())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getSzallitasimodnev())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getPartnernev())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getPartnerCim())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getPartneradoszam())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getNetto())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getAfa())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getBrutto())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getValutanemnev())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getArfolyam())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getNettohuf())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getAfahuf())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getBruttohuf())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $fej->getFuvarlevelszam())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getCikkszam())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getTermeknev())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getValtozatertek1())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getValtozatertek2())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getMennyiseg())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getME())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getNettoegysar())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getBruttoegysar())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getNettoegysarhuf())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getBruttoegysarhuf())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getNetto())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getAfaertek())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getBrutto())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getNettohuf())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getAfaertekhuf())
                        ->setCellValue(\mkw\store::getExcelCoordinate($o++, $sor), $tetel->getBruttohuf());

                    $sor++;
                }
            }
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('bizonylattetel') . '.xlsx');
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }

    public function quickAdd()
    {
        $biztipus = $this->params->getStringRequestParam('biztipus');

        $obj = new \Entities\Bizonylatfej();

        $penzirany = 0;

        switch ($biztipus) {
            case 'szamla':
                $obj->setBizonylattipus($this->getRepo(Bizonylattipus::class)->find('szamla'));
                $penzirany = 1;
                break;
            case 'egyeb':
                $obj->setBizonylattipus($this->getRepo(Bizonylattipus::class)->find('egyeb'));
                $penzirany = 1;
                break;
            case 'koltsegszamla':
                $obj->setBizonylattipus($this->getRepo(Bizonylattipus::class)->find('koltsegszamla'));
                $penzirany = -1;
                break;
            case 'bevet':
                $obj->setBizonylattipus($this->getRepo(Bizonylattipus::class)->find('bevet'));
                $penzirany = -1;
                break;
        }

        $obj->setPersistentData(); // a biz. állandó adatait tölti fel (biz.tip-ból, tulaj adatok)

        $partnerkod = $this->params->getIntRequestParam('partner');
        if ($partnerkod > 0) {
            /** @var \Entities\Partner $partnerobj */
            $partnerobj = \mkw\store::getEm()->getRepository(Partner::class)->find($partnerkod);
            if ($partnerobj) {
                $partnerobj->setAdoszam($this->params->getStringRequestParam('partneradoszam'));
                $partnerobj->setEuadoszam($this->params->getStringRequestParam('partnereuadoszam'));
                $partnerobj->setThirdadoszam($this->params->getStringRequestParam('partnerthirdadoszam'));
                $partnerobj->setVatstatus($this->params->getIntRequestParam('partnervatstatus'));

                $partnerobj->setEmail($this->params->getStringRequestParam('partneremail'));
                $partnerobj->setTelefon($this->params->getStringRequestParam('partnertelefon'));
                $partnerobj->setVezeteknev($this->params->getStringRequestParam('partnervezeteknev'));
                $partnerobj->setKeresztnev($this->params->getStringRequestParam('partnerkeresztnev'));
                if (!$partnerobj->getNev() && ($partnerobj->getVezeteknev() || $partnerobj->getKeresztnev())) {
                    $partnerobj->setNev($partnerobj->getVezeteknev() . ' ' . $partnerobj->getKeresztnev());
                }
                $partnerobj->setIrszam($this->params->getStringRequestParam('partnerirszam'));
                $partnerobj->setVaros($this->params->getStringRequestParam('partnervaros'));
                $partnerobj->setUtca($this->params->getStringRequestParam('partnerutca'));
                $partnerobj->setHazszam($this->params->getStringRequestParam('partnerhazszam'));
                if ($biztipus === 'koltsegszamla' || $biztipus === 'bevet') {
                    $partnerobj->setSzallito(true);
                }
                $this->getEm()->persist($partnerobj);

                $obj->setPartner($partnerobj);
            }
        } else {
            $partnerobj = null;
            $partneremail = $this->params->getStringRequestParam('partneremail');
            if ($partneremail) {
                /** @var \Entities\Partner $partnerobj */
                $partnerobj = $this->getRepo(Partner::class)->findOneBy(['email' => $partneremail]);
            }
            if (!$partnerobj) {
                $partnerobj = new \Entities\Partner();
            }
            $partnerobj->setEmail($this->params->getStringRequestParam('partneremail'));
            $partnerobj->setTelefon($this->params->getStringRequestParam('partnertelefon'));
            $partnerobj->setVezeteknev($this->params->getStringRequestParam('partnervezeteknev'));
            $partnerobj->setKeresztnev($this->params->getStringRequestParam('partnerkeresztnev'));
            if (!$partnerobj->getNev() && ($partnerobj->getVezeteknev() || $partnerobj->getKeresztnev())) {
                $partnerobj->setNev($partnerobj->getVezeteknev() . ' ' . $partnerobj->getKeresztnev());
            }
            $partnerobj->setIrszam($this->params->getStringRequestParam('partnerirszam'));
            $partnerobj->setVaros($this->params->getStringRequestParam('partnervaros'));
            $partnerobj->setUtca($this->params->getStringRequestParam('partnerutca'));
            $partnerobj->setHazszam($this->params->getStringRequestParam('partnerhazszam'));

            if (!$partnerobj->getEmail()) {
                $partnerobj->setEmail(uniqid(\Behat\Transliterator\Transliterator::transliterate($partnerobj->getNev(), '_'), true) . '@mail.local');
            }
            if (!$partnerobj->getIrszam()) {
                $partnerobj->setIrszam('1011');
            }
            if (!$partnerobj->getVaros()) {
                $partnerobj->setVaros('Budapest');
            }
            if ($biztipus === 'koltsegszamla' || $biztipus === 'bevet') {
                $partnerobj->setSzallito(true);
            }

            $this->getEm()->persist($partnerobj);

            $obj->setPartner($partnerobj);
        }

        $ck = \mkw\store::getEm()->getRepository(Raktar::class)->find(\mkw\store::getDefaultRaktarId());
        if ($ck) {
            $obj->setRaktar($ck);
        }
        /** @var \Entities\Fizmod $fizmod */
        $fizmod = \mkw\store::getEm()->getRepository(Fizmod::class)->find($this->params->getIntRequestParam('fizmod'));
        if ($fizmod) {
            $obj->setFizmod($fizmod);
        }
        $ck = \mkw\store::getEm()->getRepository(Szallitasimod::class)->find(\mkw\store::getParameter(\mkw\consts::Szallitasimod));
        if ($ck) {
            $obj->setSzallitasimod($ck);
        }
        $obj->setKelt($this->params->getStringRequestParam('kelt'));
        if ($biztipus === 'bevet' || $biztipus === 'koltsegszamla') {
            $obj->setTeljesites($this->params->getStringRequestParam('teljesites'));
            $obj->setEsedekesseg($this->params->getStringRequestParam('esedekesseg'));
            $obj->setErbizonylatszam($this->params->getStringRequestParam('erbizonylatszam'));
            $dolgozo = \mkw\store::getEm()->getRepository(Dolgozo::class)->find($this->params->getIntRequestParam('felhasznalo'));
            if ($dolgozo) {
                $obj->setFelhasznalo($dolgozo);
            }
        } else {
            $obj->setTeljesites($this->params->getStringRequestParam('kelt'));
            $obj->setEsedekesseg($this->params->getStringRequestParam('kelt'));
        }

        $ck = \mkw\store::getEm()->getRepository(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
        if ($ck) {
            $obj->setValutanem($ck);
        }

        $obj->setSzallitasikoltsegbrutto(0);

        if ($fizmod->getId() == \mkw\store::getParameter(\mkw\consts::SZEPFizmod)) {
            $obj->setSzepkartyaszam($this->params->getStringRequestParam('szepkartyaszam'));
            $obj->setSzepkartyanev($this->params->getStringRequestParam('szepkartyanev'));
            $obj->setSzepkartyatipus($this->params->getIntRequestParam('szepkartyatipus'));
            $obj->setSzepkartyaervenyesseg($this->params->getStringRequestParam('szepkartyaervenyesseg'));
        }

        if (($this->params->getIntRequestParam('termek') > 0)) {
            $termek = $this->getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termek'));
            if ($termek) {
                $tetel = new Bizonylattetel();
                $obj->addBizonylattetel($tetel);
                $tetel->setPersistentData();
                $tetel->setArvaltoztat(0);
                if ($termek) {
                    $tetel->setTermek($termek);
                }

                if ($this->params->getStringRequestParam('termeknev')) {
                    $tetel->setTermeknev($this->params->getStringRequestParam('termeknev'));
                }

                $tetel->setMennyiseg($this->params->getFloatRequestParam('mennyiseg'));
                $tetel->setBruttoegysar($this->params->getFloatRequestParam('egysegar'));

                $tetel->setNettoegysarhuf($tetel->getNettoegysar());
                $tetel->setBruttoegysarhuf($tetel->getBruttoegysar());
                $tetel->setEnettoegysarhuf($tetel->getEnettoegysar());
                $tetel->setEbruttoegysarhuf($tetel->getEbruttoegysar());
                $tetel->setNettohuf($tetel->getNetto());
                $tetel->setBruttohuf($tetel->getBrutto());
                $tetel->setAfaertekhuf($tetel->getAfaertek());

                $tetel->calc();

                $this->getEm()->persist($tetel);
            } else {
                \mkw\store::writelog(print_r($this->params->asArray(), true), 'nincstermek.log');
            }
        }

        $this->getEm()->persist($obj);
        $this->getEm()->flush();

        if ($this->params->getBoolRequestParam('vanpenzmozgas') && $this->params->getFloatRequestParam('penz')) {
            if ($fizmod) {
                if ($fizmod->getTipus() === 'P' || \mkw\store::isSportkartyaFizmod($fizmod) || \mkw\store::isSZEPFizmod($fizmod)) {
                    $pbfej = new \Entities\Penztarbizonylatfej();
                    $pbfej->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
                    $pbfej->setKelt($this->params->getStringRequestParam('penzdatum'));
                    $pbfej->setArfolyam(1);
                    $pbfej->setPartner($partnerobj);
                    $pbfej->setValutanem($obj->getValutanem());

                    $ck = \mkw\store::getEm()->getRepository(Penztar::class)->find($this->params->getIntRequestParam('penztar'));
                    if ($ck) {
                        $pbfej->setPenztar($ck);
                    }

                    $bt = $this->getRepo(Bizonylattipus::class)->find('penztar');
                    $pbfej->setBizonylattipus($bt);
                    $pbfej->setIrany($penzirany);

                    if (($this->params->getIntRequestParam('teteljogcim') > 0)) {
                        $jogcim = $this->getEm()->getRepository(Jogcim::class)->find($this->params->getIntRequestParam('teteljogcim'));
                        if ($jogcim) {
                            $pbtetel = new \Entities\Penztarbizonylattetel();
                            $pbfej->addBizonylattetel($pbtetel);

                            $pbtetel->setJogcim($jogcim);
                            $pbtetel->setHivatkozottbizonylat($obj->getId());
                            $pbtetel->setHivatkozottdatum($obj->getKelt());
                            if ($tetel) {
                                $pbtetel->setSzoveg($tetel->getTermeknev() . ' ' . at('eladás'));
                            }

                            $pbtetel->setBrutto($this->params->getFloatRequestParam('penz'));

                            $this->getEm()->persist($pbtetel);
                        } else {
                            \mkw\store::writelog(print_r($this->params->asArray(), true), 'nincsjogcim.log');
                        }
                    }
                    $this->getEm()->persist($pbfej);
                    $this->getEm()->flush();
                } elseif ($fizmod->getTipus() === 'B' && !\mkw\store::isAYCMFizmod($fizmod) && !\mkw\store::isSportkartyaFizmod($fizmod) &&
                    !\mkw\store::isSZEPFizmod($fizmod)) {
                    $bbfej = new \Entities\Bankbizonylatfej();
                    $bbfej->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
                    $bbfej->setKelt($this->params->getStringRequestParam('penzdatum'));
                    $bbfej->setValutanem($obj->getValutanem());

                    $ck = \mkw\store::getEm()->getRepository(Bankszamla::class)->find($this->params->getIntRequestParam('bankszamla'));
                    if ($ck) {
                        $bbfej->setBankszamla($ck);
                    }

                    $bt = $this->getRepo(Bizonylattipus::class)->find('bank');
                    $bbfej->setBizonylattipus($bt);

                    if (($this->params->getIntRequestParam('teteljogcim') > 0)) {
                        $jogcim = $this->getEm()->getRepository(Jogcim::class)->find($this->params->getIntRequestParam('teteljogcim'));
                        if ($jogcim) {
                            $bbtetel = new \Entities\Bankbizonylattetel();
                            $bbfej->addBizonylattetel($bbtetel);

                            $bbtetel->setDatum($this->params->getStringRequestParam('penzdatum'));
                            $bbtetel->setPartner($partnerobj);
                            $bbtetel->setJogcim($jogcim);
                            $bbtetel->setIrany($penzirany);
                            $bbtetel->setHivatkozottbizonylat($obj->getId());
                            $bbtetel->setHivatkozottdatum($obj->getTeljesites());

                            $bbtetel->setBrutto($this->params->getFloatRequestParam('penz'));

                            $this->getEm()->persist($bbtetel);
                        } else {
                            \mkw\store::writelog(print_r($this->params->asArray(), true), 'nincsjogcim.log');
                        }
                    }
                    $this->getEm()->persist($bbfej);
                    $this->getEm()->flush();
                }
            }
        }
        echo $obj->getId();
    }

    public function setNyomtatvaVissza()
    {
        $this->setNyomtatva($this->params->getStringRequestParam('b'), false);
    }

    public function repairPartnerAdat()
    {
        $mind = $this->getRepo()->getAll();
        $db = 0;
        /** @var \Entities\Bizonylatfej $bf */
        foreach ($mind as $bf) {
            $db++;
            $partner = $bf->getPartner();
            $bf->setPartnerLeiroadat($partner);
            $bf->setKellszallitasikoltsegetszamolni(false);
            $bf->setSimpleedit(true);
            $this->getEm()->persist($bf);
            if ($db % 20 == 0) {
                $this->getEm()->flush();
            }
        }
        $this->getEm()->persist($bf);
        $this->getEm()->flush();
    }

    public function getFolyoszamla()
    {
        $bizszam = $this->params->getStringRequestParam('bizszam');
        /** @var \Entities\Folyoszamla $folyoszamlak */
        $folyoszamlak = $this->getRepo(Folyoszamla::class)->getBefizetesByHivatkozottBizonylat($bizszam);
        $adat = [];
        /** @var \Entities\Folyoszamla $fszla */
        foreach ($folyoszamlak as $fszla) {
            if ($fszla->getBankbizonylattetel()) {
                $datum = $fszla->getBankbizonylattetel()->getDatumStr();
            } elseif ($fszla->getPenztarbizonylatfej()) {
                $datum = $fszla->getPenztarbizonylatfej()->getKeltStr();
            } else {
                $datum = $fszla->getDatumStr();
            }
            $adat[] = [
                'datum' => $datum,
                'bizonylatszam' => $fszla->getBankbizonylatfejId() ? $fszla->getBankbizonylatfejId() : $fszla->getPenztarbizonylatfejId(),
                'fizmodnev' => $fszla->getFizmod()->getNev(),
                'brutto' => $fszla->getBrutto(),
                'valutanemnev' => $fszla->getValutanemnev()
            ];
        }
        $view = $this->createView('bizonylatfolyoszamlareszletezo.tpl');
        $view->setVar('lista', $adat);
        $view->printTemplateResult();
    }

    public function getStatuszNaplo()
    {
        $bizszam = $this->params->getStringRequestParam('bizszam');
        /** @var \Entities\Bizonylatstatusznaplo[] $naplok */
        $naplok = $this->getRepo(Bizonylatstatusznaplo::class)->getByBizonylatfej($bizszam);
        $adat = [];
        foreach ($naplok as $naplo) {
            $adat[] = [
                'datum' => $naplo->getCreatedStr(),
                'dolgozonev' => $naplo->getDolgozonev(),
                'regi' => $naplo->getRegistatusznev(),
                'uj' => $naplo->getUjstatusznev(),
            ];
        }
        $view = $this->createView('bizonylatstatusznaploreszletezo.tpl');
        $view->setVar('lista', $adat);
        $view->printTemplateResult();
    }

    public function sendToNAV($bizszam)
    {
        /** @var \Entities\Bizonylatfej $biz */
        $biz = $this->getRepo()->find($bizszam);
        if ($biz && $biz->getBizonylattipusNavbekuldendo() && $biz->isNavbekuldendo()) {
            $xml = $biz->toNAVOnlineXML();
            $no = new \mkwhelpers\NAVOnline(\mkw\store::getTulajAdoszam(), \mkw\store::getNAVOnlineEnv());
            if ($no->sendSzamla($bizszam, $xml)) {
                $biz->setSimpleedit(true);
                $biz->setNaveredmeny($no->getResult());
                $this->getEm()->persist($biz);
                $this->getEm()->flush();
            } else {
                $noerrors = $no->getErrors();
                \mkw\store::writelog($bizszam . ' sendToNAV', 'navonline.log');
                \mkw\store::writelog(print_r($noerrors, true), 'navonline.log');
                return $no->getErrorsAsHtml();
            }
        }
        return false;
    }

    public function validateWithNAV($bizszam)
    {
        /** @var \Entities\Bizonylatfej $biz */
        $biz = $this->getRepo()->find($bizszam);
        if ($biz && $biz->getBizonylattipusNavbekuldendo() && $biz->isNavbekuldendo()) {
            $xml = $biz->toNAVOnlineXML();
            $no = new \mkwhelpers\NAVOnline(\mkw\store::getTulajAdoszam(), \mkw\store::getNAVOnlineEnv());
            if ($no->validate($xml)) {
                if ($no->getResult() !== 'OK') {
                    $noerrors = $no->getErrors();
                    \mkw\store::writelog($bizszam . ' validateWithNAV result', 'navonline.log');
                    \mkw\store::writelog(print_r($no->getResult(), true), 'navonline.log');
                    return $no->getErrorsAsHtml();
                }
                return true;
            } else {
                $noerrors = $no->getErrors();
                \mkw\store::writelog($bizszam . ' validateWithNAV kapcsolat hiba', 'navonline.log');
                \mkw\store::writelog(print_r($noerrors, true), 'navonline.log');
                return $no->getErrorsAsHtml();
            }
        }
        return true;
    }

    public function NAVEredmenyFeldolgoz()
    {
        $bizszamok = $this->getRepo()->getNAVEredmenyFeldolgozando();
        if ($bizszamok) {
            $no = new \mkwhelpers\NAVOnline(\mkw\store::getTulajAdoszam(), \mkw\store::getNAVOnlineEnv());
            if ($no->getSomeSzamlaInfo($bizszamok)) {
                $noresult = json_decode($no->getResult());
                foreach ($noresult as $res) {
                    /** @var \Entities\Bizonylatfej $biz */
                    $biz = $this->getRepo()->find($res->bizszam);
                    if ($biz) {
                        $biz->setSimpleedit(true);
                        $biz->setNaveredmeny($res->navstate);
                        $this->getEm()->persist($biz);
                        $this->getEm()->flush();
                    }
                }
            } else {
                $noerrors = $no->getErrors();
                \mkw\store::writelog(print_r($bizszamok, true), 'navonline.log');
                \mkw\store::writelog(print_r($noerrors, true), 'navonline.log');
            }
        }
    }

    public function navonline()
    {
        $id = $this->params->getStringRequestParam('id', '');
        if (\mkw\store::getParameter(\mkw\consts::NAVOnlineVersion, '2_0') === '1_1') {
            /** @var \Entities\Bizonylatfej $biz */
            $biz = $this->getRepo()->find($id);
            if ($biz && $biz->isNavbekuldendo()) {
                $xml = $biz->toNAVOnlineXML();
                header("Content-type: application/xml");
                header("Pragma: no-cache");
                header("Expires: 0");
                echo $xml;
            }
        } else {
            /** @var \Entities\Bizonylatfej $biz */
            $biz = $this->getRepo()->find($id);
            if ($biz && $biz->isNavbekuldendo()) {
                if ($biz->getNyomtatva()) {
                    $nores = $this->sendToNAV($id);
                    if ($nores) {
                        echo $nores;
                    }
                } else {
                    echo 'A bizonylat nincs kinyomtatva';
                }
            }
        }
    }

    public function requeryNavEredmeny()
    {
        $id = $this->params->getStringRequestParam('id', '');
        /** @var \Entities\Bizonylatfej $biz */
        $biz = $this->getRepo()->find($id);
        if ($biz) {
            $no = new \mkwhelpers\NAVOnline(\mkw\store::getTulajAdoszam(), \mkw\store::getNAVOnlineEnv());
            $no->requeryFromNAV($id);
        }
    }

    public function sendEmailSablon()
    {
        /** @var Bizonylatfej $bf */
        $bf = $this->getRepo()->find($this->params->getStringRequestParam('id'));
        if ($bf) {
            $sablon = $this->getRepo(Emailtemplate::class)->find($this->params->getIntRequestParam('sablon'));
            if ($sablon) {
                if ($sablon->getId() == \mkw\store::getParameter(\mkw\consts::ErtekelesKeroSablon)) {
                    if ($bf->isVanMitErtekelni()) {
                        $bf->sendEmailSablon($sablon);
                        $bf->setSimpleedit(true);
                        $bf->setTermekertekeleskikuldve(true);
                        $this->getEm()->persist($bf);
                        $this->getEm()->flush();
                    }
                } else {
                    $bf->sendEmailSablon($sablon);
                }
            }
        }
    }

    public function sendEmailSablonok()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        $keroid = \mkw\store::getParameter(\mkw\consts::ErtekelesKeroSablon);
        $sablon = $this->getRepo(Emailtemplate::class)->find($this->params->getIntRequestParam('sablon'));
        if ($sablon) {
            foreach ($ids as $id) {
                /** @var \Entities\Bizonylatfej $megrendfej */
                $megrendfej = $this->getRepo()->find($id);
                if ($megrendfej) {
                    if ($sablon->getId() == $keroid) {
                        if ($megrendfej->isVanMitErtekelni()) {
                            $megrendfej->sendEmailSablon($sablon);
                            $megrendfej->setSimpleedit(true);
                            $megrendfej->setTermekertekeleskikuldve(true);
                            $this->getEm()->persist($megrendfej);
                            $this->getEm()->flush();
                        }
                    } else {
                        $megrendfej->sendEmailSablon($sablon);
                    }
                }
            }
        }
    }

    public function calcNavEredmenyRiasztas()
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('kelt', '>=', '2021-04-01');
        $filter->addFilter('bizonylattipus', 'IN', ['szamla', 'esetiszamla']);
        $filter->addFilter('_xx.naveredmeny', '=', 'ABORTED');
        $abortedcnt = (int)$this->getRepo()->getCount($filter);
        $filter->clear();
        $filter->addFilter('kelt', '>=', '2021-04-01');
        $filter->addFilter('bizonylattipus', 'IN', ['szamla', 'esetiszamla']);
        $filter->addSql('(_xx.naveredmeny IS NULL)');
        $nullcnt = (int)$this->getRepo()->getCount($filter);
        return [
            'aborted' => $abortedcnt,
            'null' => $nullcnt
        ];
    }
}