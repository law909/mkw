<?php

namespace Controllers;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Emailtemplate;
use Entities\Partner;
use Entities\Termek;
use Entities\TermekErtekeles;
use mkwhelpers\FilterDescriptor;

class termekertekelesController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(TermekErtekeles::class);
        $this->setKarbFormTplName('termekertekeleskarbform.tpl');
        $this->setKarbTplName('termekertekeleskarb.tpl');
        $this->setListBodyRowTplName('termekertekeleslista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\TermekErtekeles();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['szoveg'] = $t->getSzoveg();
        $x['elony'] = $t->getElony();
        $x['hatrany'] = $t->getHatrany();
        $x['valasz'] = $t->getValasz();
        $x['ertekeles'] = $t->getErtekeles();
        $x['created'] = $t->getCreatedStr();
        $x['lastmod'] = $t->getLastmodStr();
        $x['createdstr'] = $t->getCreatedStr();
        $x['lastmodstr'] = $t->getLastmodStr();
        $x['partner'] = $t->getPartnerId();
        $x['partnernev'] = $t->getPartnerNev();
        $x['termek'] = $t->getTermekId();
        $x['termeknev'] = $t->getTermekNev();
        $x['termekslug'] = $t->getTermekSlug();
        $x['elutasitva'] = $t->isElutasitva();
        $x['anonim'] = $t->isAnonim();
        return $x;
    }

    /**
     * @param \Entities\TermekErtekeles $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $ck = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('partner'));
        if ($ck) {
            $obj->setPartner($ck);
        } else {
            $obj->setPartner(null);
        }
        $ck = \mkw\store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termek'));
        if ($ck) {
            $obj->setTermek($ck);
        } else {
            $obj->setTermek(null);
        }
        $obj->setSzoveg($this->params->getStringRequestParam('szoveg'));
        $obj->setElony($this->params->getStringRequestParam('elony'));
        $obj->setHatrany($this->params->getStringRequestParam('hatrany'));
        $obj->setValasz($this->params->getStringRequestParam('valasz'));
        $obj->setErtekeles($this->params->getIntRequestParam('ertekeles'));
        $obj->setElutasitva($this->params->getBoolRequestParam('elutasitva'));
        $obj->setAnonim($this->params->getBoolRequestParam('anonim'));
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('termekertekeleslista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('partnerfilter', null))) {
            $filter->addFilter('partner', '=', $this->params->getIntRequestParam('partnerfilter'));
        }

        $this->initPager($this->getRepo()->getCount($filter));
        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'termekertekeleslista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('termekertekeleslista.tpl');
        $view->setVar('pagetitle', t('Termék értékelések'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList(0));
        $tcs = new termekController($this->params);
        $view->setVar('termeklist', $tcs->getSelectList());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Termék értékelés'));
        $view->setVar('oper', $oper);

        /** @var TermekErtekeles $te */
        $te = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($te, true));

        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList(($te ? $te->getPartnerId() : 0)));

        $csoport = new termekController($this->params);
        $view->setVar('termeklist', $csoport->getSelectList(($te ? $te->getTermekId() : 0)));

        $view->printTemplateResult();
    }

    public function showErtekelesForm()
    {
        $id = $this->params->getStringRequestParam('id');
        $bizid = $this->params->getStringRequestParam('b');
        if (!$id) {
            /** @var Bizonylatfej $biz */
            $biz = $this->getRepo(Bizonylatfej::class)->find($bizid);
            if ($biz) {
                if (!$biz->getTermekertekelesid()) {
                    $biz->setSimpleedit(true);
                    $biz->generateTermekertekelesid();
                    $id = $biz->getTermekertekelesid();
                    $this->getEm()->persist($biz);
                    $this->getEm()->flush();
                } else {
                    $id = $biz->getTermekertekelesid();
                }
            }
        }
        /** @var Bizonylatfej $biz */
        $biz = $this->getRepo(Bizonylatfej::class)->findOneBy(['termekertekelesid' => $id]);
        if ($biz) {
            $view = $this->getTemplateFactory()->createMainView('termekertekelesform.tpl');
            \mkw\store::fillTemplate($view);
            $view->setVar('pagetitle', t('Értékelés') . ' - ' . \mkw\store::getParameter(\mkw\consts::Oldalcim));

            $view->setVar('megr', $biz->toLista());
            $view->printTemplateResult();
        }
    }

    public function pubSave()
    {
        $pid = $this->params->getIntRequestParam('pid');
        $partner = $this->getRepo(Partner::class)->find($pid);
        if ($partner) {
            $termekids = $this->params->getArrayRequestParam('termekids');
            foreach ($termekids as $id) {
                $ert = $this->getRepo(TermekErtekeles::class)->findOneBy(
                    [
                        'partner' => $partner,
                        'termek' => $id
                    ]
                );
                if (!$ert) {
                    $rating = $this->params->getIntRequestParam('rating_' . $id);
                    $ertekeles = $this->params->getStringRequestParam('ertekeles_' . $id);
                    $elony = $this->params->getStringRequestParam('elony_' . $id);
                    $hatrany = $this->params->getStringRequestParam('hatrany_' . $id);
                    $anonim = $this->params->getBoolRequestParam('anonim_' . $id);
                    if ($rating && $ertekeles) {
                        $termek = $this->getRepo(Termek::class)->find($id);
                        if ($termek) {
                            $ert = new TermekErtekeles();
                            $ert->setTermek($termek);
                            $ert->setPartner($partner);
                            $ert->setErtekeles($rating);
                            $ert->setSzoveg($ertekeles);
                            $ert->setElony($elony);
                            $ert->setHatrany($hatrany);
                            $ert->setAnonim($anonim);
                            $this->getEm()->persist($ert);
                            $this->getEm()->flush();

                            $mailer = \mkw\store::getMailer();
                            $emailtpl = $this->getRepo(Emailtemplate::class)->find(\mkw\store::getParameter(\mkw\consts::ErtekelesErtesitoSablon));
                            if ($emailtpl) {
                                $tpldata = $ert->toLista();

                                $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                                $subject->setVar('ertekeles', $tpldata);

                                $body = \mkw\store::getTemplateFactory()->createMainView(
                                    'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
                                );
                                $body->setVar('ertekeles', $tpldata);

                                if (\mkw\store::isDeveloper()) {
                                    \mkw\store::writelog($subject->getTemplateResult(), 'bizstatuszemail.html');
                                    \mkw\store::writelog($body->getTemplateResult(), 'bizstatuszemail.html');
                                } else {
                                    $email = \mkw\store::getParameter(\mkw\consts::EmailBcc);
                                    if ($email) {
                                        $mailer->addTo($email);
                                        $mailer->setSubject($subject->getTemplateResult());
                                        $mailer->setMessage($body->getTemplateResult());
                                        $mailer->send();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        Header('Location: ' . \mkw\store::getRouter()->generate('termekertekeleskoszonjuk'));
    }

    public function thanks()
    {
        $view = \mkw\store::getTemplateFactory()->createMainView('termekertekeleskoszonjuk.tpl');
        \mkw\store::fillTemplate($view);

        $view->printTemplateResult(false);
    }

    public function getNewList()
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('elutasitva', '=', 0);
        $filter->addSql('LENGTH(_xx.szoveg)>=5');
        $res = [];
        $ertekelesek = $this->getRepo()->getWithJoins($filter, ['created' => 'DESC'], 0, 5);
        foreach ($ertekelesek as $ert) {
            $res[] = $ert->toLista();
        }
        return $res;
    }
}
