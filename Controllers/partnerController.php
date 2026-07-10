<?php

namespace Controllers;

use Entities\Partner;
use Traits\PartnerAdminCrud;
use Traits\PartnerAuth;
use Traits\PartnerRegistration;
use Traits\PartnerPassReminder;
use Traits\PartnerFiok;
use Traits\PartnerDataProvider;
use Traits\PartnerBulkOps;
use Traits\PartnerExport;

/**
 * @see \Traits\PartnerAdminCrud     admin lista/karbantartás (loadVars, setFields, getlistbody, viewlist, _getkarb)
 * @see \Traits\PartnerAuth          storefront belépés/kilépés/session (login, logout, autologout, doLogin, doLogout, showLoginForm)
 * @see \Traits\PartnerRegistration  regisztráció + API regisztráció + email-ellenőrzés
 * @see \Traits\PartnerPassReminder        jelszó-emlékeztető (elfelejtett jelszó) folyamat
 * @see \Traits\PartnerFiok          "fiókom" oldal (showAccount, saveAccount, checkPartnerData)
 * @see \Traits\PartnerDataProvider  AJAX/select-list adatszolgáltatók más kontrollereknek
 * @see \Traits\PartnerBulkOps       tömeges admin műveletek (ársávcsere, kedvezmény, flag, email, anonimizálás)
 * @see \Traits\PartnerExport        XLSX exportok (megjegyzés, hírlevél, MPTNGY számlázás)
 */
class partnerController extends \mkwhelpers\MattableController
{
    use PartnerAdminCrud;
    use PartnerAuth;
    use PartnerRegistration;
    use PartnerPassReminder;
    use PartnerFiok;
    use PartnerDataProvider;
    use PartnerBulkOps;
    use PartnerExport;

    public function __construct()
    {
        $this->setEntityName(Partner::class);
        $this->setKarbFormTplName('partnerkarbform.tpl');
        $this->setKarbTplName('partnerkarb.tpl');
        $this->setListBodyRowTplName('partnerlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_partner');
        parent::__construct();
    }
}
