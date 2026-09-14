<?php

namespace Controllers;

use Entities\JogaBejelentkezes;
use Entities\Orarend;
use Entities\Partner;
use Services\PartnerResolveService;

class jogabejelentkezesController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(JogaBejelentkezes::class);
        $this->setKarbFormTplName('jogabejelentkezeskarbform.tpl');
        $this->setKarbTplName('jogabejelentkezeskarb.tpl');
        $this->setListBodyRowTplName('jogabejelentkezeslista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    protected function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\JogaBejelentkezes();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['datum'] = $t->getDatumStr();
        $x['napnev'] = $t->getDatumNapnev();

        $x['partnernev'] = $t->getPartnernev();
        $x['partneremail'] = $t->getPartneremail();

        return $x;
    }

    /**
     * @param \Entities\JogaBejelentkezes $obj
     * @param $oper
     *
     * @return mixed
     */
    protected function setFields($obj, $oper)
    {
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('jogabejelentkezeslista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();

        $f = $this->params->getStringRequestParam('partnernevfilter');
        if ($f) {
            $filter->addFilter('partnernev', 'LIKE', '%' . $f . '%');
        }

        $f = $this->params->getStringRequestParam('partneremailfilter');
        if ($f) {
            $filter->addFilter('partneremail', 'LIKE', '%' . $f . '%');
        }

        $tol = $this->params->getStringRequestParam('datumtolfilter');
        $ig = $this->params->getStringRequestParam('datumigfilter');
        if ($tol) {
            $filter->addFilter('datum', '>=', $tol);
        }
        if ($ig) {
            $filter->addFilter('datum', '<=', $ig);
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['partnernev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor['id'], 'caption' => $sor['partnernev'], 'selected' => ($sor['id'] == $selid)];
        }
        return $res;
    }

    public function viewselect()
    {
        $view = $this->createView('jogabejelentkezeslista.tpl');

        $view->setVar('pagetitle', t('Óra látogatások'));
        $view->printTemplateResult(false);
    }

    public function viewlist()
    {
        $view = $this->createView('jogabejelentkezeslista.tpl');

        $view->setVar('pagetitle', t('Óra látogatások'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Óra látogatás'));
        $view->setVar('formaction', '/admin/jogabejelentkezes/save');
        $view->setVar('oper', $oper);
        /** @var \Entities\JogaBejelentkezes $record */
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record));

        return $view->getTemplateResult();
    }

    /**
     * A publikus jelentkezési űrlapoknak: kell-e címet kérni. Szándékosan semmi mást nem ad ki – a név vagy a cím
     * bárkinek elárulná, kinek mi van a partnertörzsben. Az email ismertsége a /checkemail-lel ma is kideríthető.
     */
    public function checkEmail()
    {
        header('Content-Type: application/json; charset=utf-8');
        $email = trim($this->params->getStringRequestParam('email'));
        $partner = filter_var($email, FILTER_VALIDATE_EMAIL) ? (new PartnerResolveService())->findByEmail($email) : null;
        echo json_encode(['ismert' => (bool)$partner, 'cimhianyos' => !$partner || !$partner->hasFullCim()]);
    }

    /**
     * A publikus órarend „Bejelentkezek" gombja. A partner itt nem jön létre (azt a pubadmin Megérkezett vagy
     * vásárlás gombja hozza létre); a meglévő partnernek csak az üres címmezőit töltjük ki.
     */
    public function bejelentkezes()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!\mkw\store::isDarshanTheme()) {
            $this->saveKisszamlazoBejelentkezes();
            return;
        }
        $partnernev = implode(' ', JogaBejelentkezes::splitNev($this->params->getStringRequestParam('partnernev')));
        $email = trim($this->params->getStringRequestParam('email'));
        if ($email === '' || $partnernev === '') {
            echo json_encode(['ok' => false, 'msg' => t('Add meg az emailcímed és a teljes neved.')]);
            return;
        }
        if (!JogaBejelentkezes::isTeljesNev($partnernev)) {
            echo json_encode(['ok' => false, 'msg' => t('Kérjük, add meg a teljes neved (vezeték- és keresztnév).')]);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['ok' => false, 'msg' => t('Kérjük, ellenőrizd az emailcímed.')]);
            return;
        }
        /** @var Orarend|null $ora */
        $ora = $this->getRepo(Orarend::class)->find($this->params->getIntRequestParam('id'));
        $datum = $this->parseDatum($this->params->getStringRequestParam('datum'));
        if (!$ora || !$datum) {
            echo json_encode(['ok' => false, 'msg' => t('Ezt az órát nem találjuk.')]);
            return;
        }

        $bej = $this->getRepo()->findForEmail($ora, $datum, $email);
        if ($bej && !$bej->isLemondva()) {
            echo json_encode(['ok' => false, 'msg' => t('Erre az órára ezzel az emailcímmel már be vagy jelentkezve.')]);
            return;
        }
        // a visszaállított jelentkezés is helyet foglal; ugyanaz a szabály, mint az órarend „BETELT" felirata
        $max = (int)$ora->getMaxferohely();
        if ($max > 0 && $this->getRepo()->getAdottOraCount($datum, $ora->getId()) >= $max) {
            echo json_encode(['ok' => false, 'msg' => t('Erre az órára már nincs szabad hely.')]);
            return;
        }

        $irszam = trim($this->params->getStringRequestParam('irszam'));
        $varos = trim($this->params->getStringRequestParam('varos'));
        $utca = trim($this->params->getStringRequestParam('utca'));
        $partner = (new PartnerResolveService())->findByEmail($email);
        if ($partner) {
            $partner->fillMissingCim($irszam, $varos, $utca);
            $this->getEm()->persist($partner);
        }
        $visszaallitva = (bool)$bej;
        if (!$bej) {
            $bej = new JogaBejelentkezes();
            $bej->setOrarend($ora);
            $bej->setDatum($datum);
        }
        $bej->setLemondva(false);
        $bej->setPartnernev($partnernev);
        $bej->setPartneremail($email);
        // partner híján innen viszi tovább a címet a JogaBejelentkezes::resolvePartner(); visszaállításnál az üresen
        // hagyott mező nem törli az első jelentkezéskor megadottat
        if ($irszam !== '' || !$visszaallitva) {
            $bej->setPartnerirszam($irszam);
        }
        if ($varos !== '' || !$visszaallitva) {
            $bej->setPartnervaros($varos);
        }
        if ($utca !== '' || !$visszaallitva) {
            $bej->setPartnerutca($utca);
        }
        $this->getEm()->persist($bej);
        $this->getEm()->flush();

        $this->sendJogaBejelentkezesEmails($ora, $datum, $email, $partnernev, $partner);

        echo json_encode([
            'ok' => true,
            'msg' => $visszaallitva
                ? sprintf(t('A korábban lemondott bejelentkezésedet visszaállítottuk. Visszaigazolást küldtünk a(z) %s címre.'), $email)
                : sprintf(
                    t('Köszönjük, bejelentkeztél: %s, %s %s %s. Visszaigazolást küldtünk a(z) %s címre.'),
                    $ora->getNev(),
                    $ora->getNapNev(),
                    $datum->format(\mkw\store::$DateFormat),
                    $ora->getKezdetStr(),
                    $email
                ),
        ]);
    }

    /** A kisszámlázó (lb) órarendjének mai mentési útja: se email-ellenőrzés, se férőhely, se visszaállítás. */
    private function saveKisszamlazoBejelentkezes()
    {
        $partnernev = trim($this->params->getStringRequestParam('partnernev'));
        $email = $this->params->getStringRequestParam('email');
        $datumstr = $this->params->getStringRequestParam('datum');
        $datum = new \DateTime($datumstr);
        $orarendid = $this->params->getIntRequestParam('id');
        if (!JogaBejelentkezes::isTeljesNev($partnernev)) {
            echo json_encode(['msg' => t('Kérjük, add meg a teljes neved (vezeték- és keresztnév).')]);
            return;
        }
        $irszam = trim($this->params->getStringRequestParam('irszam'));
        $varos = trim($this->params->getStringRequestParam('varos'));
        $utca = trim($this->params->getStringRequestParam('utca'));
        if ($partnernev && $email && $orarendid && $datumstr) {
            $bej = $this->getRepo()->findOneBy(['partneremail' => $email, 'orarend' => $orarendid, 'datum' => $datum]);
            /** @var \Entities\Partner $partner */
            $partner = $this->getRepo(Partner::class)->findOneBy(['email' => $email]);
            // a címet a meglévő partnerre akkor is ráírjuk, ha a bejelentkezés már megvolt
            if ($partner) {
                $partner->fillMissingCim($irszam, $varos, $utca);
                $this->getEm()->persist($partner);
                $this->getEm()->flush();
            }
            if (!$bej) {
                /** @var \Entities\Orarend $ora */
                $ora = $this->getRepo(Orarend::class)->find($orarendid);

                $obj = new JogaBejelentkezes();
                $obj->setDatum($datumstr);
                $obj->setPartneremail($email);
                $obj->setPartnernev($partnernev);
                // partner híján innen viszi tovább a címet a JogaBejelentkezes::resolvePartner()
                $obj->setPartnerirszam($irszam);
                $obj->setPartnervaros($varos);
                $obj->setPartnerutca($utca);
                $obj->setOrarend($ora);
                $this->getEm()->persist($obj);
                $this->getEm()->flush();
                $this->sendJogaBejelentkezesEmails($ora, $datum, $email, $partnernev, $partner);
            }
        }
        echo json_encode([]);
    }

    /** A gyakorló visszaigazolása és a tanár értesítése; developer módban csak naplóba. */
    private function sendJogaBejelentkezesEmails(Orarend $ora, \DateTime $datum, $email, $partnernev, ?Partner $partner)
    {
        $emailtpl = $this->getRepo('\Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::JogaBejelentkezesKoszonoSablon));
        if ($email && $emailtpl) {
            $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
            $body = \mkw\store::getTemplateFactory()->createMainView(
                'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
            );
            $body->setVar('oranev', $ora->getNev());
            $body->setVar('tanarnev', $ora->getDolgozoNev());
            $body->setVar('idopont', $ora->getKezdetStr());
            if ($partner) {
                $body->setVar('partnerkeresztnev', $partner->getKeresztnev());
                $body->setVar('partnervezeteknev', $partner->getVezeteknev());
            } else {
                $body->setVar('partnerkeresztnev', $partnernev);
            }
            $body->setVar('datum', $datum->format(\mkw\store::$DateFormat));

            if (\mkw\store::isDeveloper()) {
                \mkw\store::writelog($subject->getTemplateResult(), 'orabejelentkezesemail.html');
                \mkw\store::writelog($body->getTemplateResult(), 'orabejelentkezesemail.html');
            } else {
                $mailer = \mkw\store::getMailer();

                $mailer->addTo($email);
                $mailer->setSubject($subject->getTemplateResult());
                $mailer->setMessage($body->getTemplateResult());

                $mailer->send();
            }
        }
        $emailtpl = $this->getRepo('\Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::JogaBejelentkezesErtesitoSablon));
        $tanaremail = $ora->getDolgozoEmail();
        if ($tanaremail && $emailtpl && $ora->isBejelentkezesertesitokell()) {
            $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
            $subject->setVar('oranev', $ora->getNev());
            $subject->setVar('tanarnev', $ora->getDolgozoNev());
            $subject->setVar('idopont', $ora->getKezdetStr());
            if ($partner) {
                $subject->setVar('partnerkeresztnev', $partner->getKeresztnev());
                $subject->setVar('partnervezeteknev', $partner->getVezeteknev());
            } else {
                $subject->setVar('partnerkeresztnev', $partnernev);
            }
            $subject->setVar('datum', $datum->format(\mkw\store::$DateFormat));
            $subject->setVar('napnev', $ora->getNapNev());

            $body = \mkw\store::getTemplateFactory()->createMainView(
                'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
            );
            $body->setVar('oranev', $ora->getNev());
            $body->setVar('tanarnev', $ora->getDolgozoNev());
            $body->setVar('idopont', $ora->getKezdetStr());
            if ($partner) {
                $body->setVar('partnerkeresztnev', $partner->getKeresztnev());
                $body->setVar('partnervezeteknev', $partner->getVezeteknev());
            } else {
                $body->setVar('partnerkeresztnev', $partnernev);
            }
            $body->setVar('datum', $datum->format(\mkw\store::$DateFormat));

            if (\mkw\store::isDeveloper()) {
                \mkw\store::writelog($subject->getTemplateResult(), 'orabejelentkezesemail.html');
                \mkw\store::writelog($body->getTemplateResult(), 'orabejelentkezesemail.html');
            } else {
                $mailer = \mkw\store::getMailer();

                $mailer->addTo($tanaremail);
                $mailer->setSubject($subject->getTemplateResult());
                $mailer->setMessage($body->getTemplateResult());

                $mailer->send();
            }
        }
    }

    /** @return \DateTime|null */
    private function parseDatum($datumstr)
    {
        $datumstr = trim((string)$datumstr);
        if ($datumstr === '') {
            return null;
        }
        try {
            return new \DateTime($datumstr);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function lemondas()
    {
        $email = trim($this->params->getStringRequestParam('email'));
        $datumstr = $this->params->getStringRequestParam('datum');
        $datum = new \DateTime($datumstr);
        $orarendid = $this->params->getIntRequestParam('id');
        if ($email && $orarendid && $datumstr) {
            /** @var JogaBejelentkezes $bej */
            $bej = $this->getRepo()->findOneBy(['partneremail' => $email, 'orarend' => $orarendid, 'datum' => $datum]);
            if ($bej) {
                $bej->setLemondva(true);
                $this->getEm()->persist($bej);
                $this->getEm()->flush();

                /** @var \Entities\Orarend $ora */
                $ora = $this->getRepo(Orarend::class)->find($orarendid);
                /** @var \Entities\Partner $partner */
                $partner = $this->getRepo(Partner::class)->findOneBy(['email' => $email]);
                $emailtpl = $this->getRepo('\Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::JogaLemondasKoszonoSablon));
                if ($email && $emailtpl && $ora) {
                    $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                    $body = \mkw\store::getTemplateFactory()->createMainView(
                        'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
                    );
                    $body->setVar('oranev', $ora->getNev());
                    $body->setVar('tanarnev', $ora->getDolgozoNev());
                    $body->setVar('idopont', $ora->getKezdetStr());
                    if ($partner) {
                        $body->setVar('partnerkeresztnev', $partner->getKeresztnev());
                        $body->setVar('partnervezeteknev', $partner->getVezeteknev());
                    }
                    $body->setVar('datum', $datum->format(\mkw\store::$DateFormat));

                    if (\mkw\store::isDeveloper()) {
                        \mkw\store::writelog($subject->getTemplateResult(), 'orabejelentkezesemail.html');
                        \mkw\store::writelog($body->getTemplateResult(), 'orabejelentkezesemail.html');
                    } else {
                        $mailer = \mkw\store::getMailer();
                        $mailer->addTo($email);
                        $mailer->setSubject($subject->getTemplateResult());
                        $mailer->setMessage($body->getTemplateResult());

                        $mailer->send();
                    }
                }
                $emailtpl = $this->getRepo('\Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::JogaLemondasErtesitoSablon));
                $tanaremail = $ora->getDolgozoEmail();
                if ($tanaremail && $emailtpl && $ora->isBejelentkezesertesitokell()) {
                    $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                    $subject->setVar('oranev', $ora->getNev());
                    $subject->setVar('tanarnev', $ora->getDolgozoNev());
                    $subject->setVar('idopont', $ora->getKezdetStr());
                    if ($partner) {
                        $subject->setVar('partnerkeresztnev', $partner->getKeresztnev());
                        $subject->setVar('partnervezeteknev', $partner->getVezeteknev());
                    }
                    $subject->setVar('datum', $datum->format(\mkw\store::$DateFormat));
                    $subject->setVar('napnev', $ora->getNapNev());

                    $body = \mkw\store::getTemplateFactory()->createMainView(
                        'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
                    );
                    $body->setVar('oranev', $ora->getNev());
                    $body->setVar('tanarnev', $ora->getDolgozoNev());
                    $body->setVar('idopont', $ora->getKezdetStr());
                    if ($partner) {
                        $body->setVar('partnerkeresztnev', $partner->getKeresztnev());
                        $body->setVar('partnervezeteknev', $partner->getVezeteknev());
                    }
                    $body->setVar('datum', $datum->format(\mkw\store::$DateFormat));

                    if (\mkw\store::isDeveloper()) {
                        \mkw\store::writelog($subject->getTemplateResult(), 'orabejelentkezesemail.html');
                        \mkw\store::writelog($body->getTemplateResult(), 'orabejelentkezesemail.html');
                    } else {
                        $mailer = \mkw\store::getMailer();

                        $mailer->addTo($tanaremail);
                        $mailer->setSubject($subject->getTemplateResult());
                        $mailer->setMessage($body->getTemplateResult());

                        $mailer->send();
                    }
                }
            }
        }
    }
}
