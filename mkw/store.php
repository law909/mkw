<?php

namespace mkw;

use Automattic\WooCommerce\Client;
use Controllers\mnrnavigationController;
use Controllers\popupController;
use Controllers\termekfaController;
use Entities\Dolgozo;
use Entities\Fizmod;
use Entities\MNRNavigation;
use Entities\Partner;
use Entities\Termek;
use Entities\TermekFa;
use Entities\Valutanem;

class store
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private static $em;
    private static $config;
    private static $setup;
    /**
     *
     * @var \Zend_Session_Namespace
     */
    private static $mainsession;
    /**
     *
     * @var \Zend_Session_Namespace
     */
    private static $adminsession;
    /**
     *
     * @var \Zend_Session_Namespace
     */
    private static $pubadminsession;
    private static $templateFactory;
    private static $router;
    private static $gdl;
    private static $sanitizer;
    private static $translationListener;
    private static $locales = ['hu' => 'hu_hu', 'en' => 'en_us'];
    private static $valutanemperlocale = ['hu_hu' => 'HUF', 'en_us' => 'EUR'];
    private static $adminmode = false;
    private static $mainmode = false;
    private static $loggedinuser;
    private static $pubadminloggedinuser;
    private static $loggedinuk;
    private static $loggedinukpartner;
    private static $routename;
    private static $daynames = ['hétfő', 'kedd', 'szerda', 'csütörtök', 'péntek', 'szombat', 'vasárnap'];
    private static $BarionEnvironment = ['teszt', 'éles'];
    private static $FoxpostAPIVersions = ['v1', 'v2'];
    public static $DateFormat = 'Y.m.d';
    public static $EngDateFormat = 'm/d/Y';
    public static $LastDayDateFormat = 'Y.m.t';
    public static $SQLDateFormat = 'Y-m-d';
    public static $sqlDateTimeFormat = 'Y-m-d\TH:i:s';
    public static $PerDateFormat = 'Y/m/d';
    public static $DBFDateFormat = 'Ymd';
    public static $JavascriptDateFormat = 'Y.n.d';
    public static $DateTimeFormat = 'Y.m.d. H:i:s';
    public static $MiniCRMDateTimeFormat = 'Y-m-d+H:i:s';
    public static $TimeFormat = 'H:i';
    public static $FullTimeFormat = 'H:i:s';
    private static $blameableListener;

    public static function getJSVersion()
    {
        switch (self::getTheme()) {
            case 'mkwcansas':
                return 36;
        }
    }

    public static function getBootstrapJSVersion()
    {
        switch (self::getTheme()) {
            case 'mkwcansas':
                return 9;
        }
    }

    public static function getClientIp()
    {
        $ipaddress = '';
        if (array_key_exists('HTTP_CLIENT_IP', $_SERVER) && $_SERVER['HTTP_CLIENT_IP']) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                if (array_key_exists('HTTP_X_FORWARDED', $_SERVER) && $_SERVER['HTTP_X_FORWARDED']) {
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                } else {
                    if (array_key_exists('HTTP_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_FORWARDED_FOR']) {
                        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                    } else {
                        if (array_key_exists('HTTP_FORWARDED', $_SERVER) && $_SERVER['HTTP_FORWARDED']) {
                            $ipaddress = $_SERVER['HTTP_FORWARDED'];
                        } else {
                            if (array_key_exists('REMOTE_ADDR', $_SERVER) && $_SERVER['REMOTE_ADDR']) {
                                $ipaddress = $_SERVER['REMOTE_ADDR'];
                            } else {
                                $ipaddress = 'UNKNOWN';
                            }
                        }
                    }
                }
            }
        }
        return $ipaddress;
    }

    public static function writelog($text, $fname = 'log.txt')
    {
        $handle = fopen(self::logsPath($fname), "a");
        $log = "";
        $separator = " ## ";
        [$usec, $sec] = explode(" ", microtime());
        $log .= date('Y.m.d. H:i:s', $sec) . '.' . substr($usec, 2, 2) . $separator;
        $log .= self::getClientIp() . $separator;
        $log .= $text;
        $log .= "\n";
        fwrite($handle, $log);
        fclose($handle);
    }

    public static function writeLineToFile($text, $fname)
    {
        $handle = fopen(self::storagePath($fname), "a");
        $log = $text . "\n";
        fwrite($handle, $log);
        fclose($handle);
    }

    public static function deletelog($fname)
    {
        if ($fname) {
            @\unlink(self::logsPath($fname));
        }
    }

    public static function writetranslation($text, $fname = 'log.txt')
    {
        $handle = fopen($fname, "a");
        $log = "";
        $log .= '\'' . $text . '\' => \'\',';
        $log .= "\n";
        fwrite($handle, $log);
        fclose($handle);
    }

    public static function isMailerGmail()
    {
        return self::getConfigValue('mail.mailer') === 'gmail';
    }

    public static function isMailerPHPMailer()
    {
        return self::getConfigValue('mail.mailer') === 'phpmailer';
    }

    public static function getMailer()
    {
        if (self::isMailerPHPMailer()) {
            return new mkwphpmailer();
        } elseif (self::isMailerGmail()) {
            return new mkwgmailmailer();
        } elseif (self::getConfigValue('mail.smtp', 0)) {
            return new mkwzendmailer();
        }
        return new mkwmailer();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public static function getEm()
    {
        return self::$em;
    }

    public static function setEm($em)
    {
        self::$em = $em;
    }

    /**
     * @return \Gedmo\Translatable\TranslatableListener()
     */
    public static function getTranslationListener()
    {
        return self::$translationListener;
    }

    public static function setTranslationListener($l)
    {
        self::$translationListener = $l;
    }

    public static function getConfig()
    {
        return self::$config;
    }

    public static function getConfigValue($key, $def = null)
    {
        if (array_key_exists($key, self::$config)) {
            return self::$config[$key];
        }
        return $def;
    }

    public static function setConfig($config)
    {
        self::$config = $config;
    }

    public static function getSetup()
    {
        return self::$setup;
    }

    public static function getSetupValue($key, $def = null)
    {
        if (array_key_exists($key, self::$setup)) {
            return self::$setup[$key];
        }
        return $def;
    }

    public static function setSetup($setup)
    {
        self::$setup = $setup;
        if (!array_key_exists('termekautocomplete', self::$setup)) {
            self::$setup['termekautocomplete'] = true;
        }
        if (!array_key_exists('show_adataim_egyebadatok', self::$setup)) {
            self::$setup['show_adataim_egyebadatok'] = true;
        }
        if (!array_key_exists('ngyclosed', self::$setup)) {
            self::$setup['ngyclosed'] = false;
        }
    }

    public static function getIntParameter($par, $default = null)
    {
        $er = self::getParameter($par, $default);
        if (is_numeric($er)) {
            return (int)$er;
        }
        return $default;
    }

    public static function getParameter($par, $default = null)
    {
        $p = self::getEm()->getRepository('Entities\Parameterek')->find($par);
        if ($p) {
            return str_replace(chr(194) . chr(173), '', $p->getErtek());  // adoszam kotojelei ele text mezobe valamiert $C2 $AD-t ir
        } else {
            return $default;
        }
    }

    public static function setParameter($par, $ertek, $specialchars = false)
    {
        /** @var \Entities\Parameterek $p */
        $p = self::getEm()->getRepository('Entities\Parameterek')->find($par);
        if ($p) {
            $p->setErtek($ertek);
            $p->setSpecialchars($specialchars);
        } else {
            $p = new \Entities\Parameterek();
            $p->setId($par);
            $p->setErtek($ertek);
            $p->setSpecialchars($specialchars);
        }
        self::getEm()->persist($p);
        self::getEm()->flush();
    }

    public static function clearParameterIf($par, $ertek)
    {
        if (self::getParameter($par, null) == $ertek) {
            self::setParameter($par, null);
        }
    }

    /* TODO ezen lehet h. csiszolni kell meg */

    public static function convDate($DateString)
    {
        return str_replace('.', '-', rtrim($DateString, '.-/ '));
    }

    public static function toDate($adat)
    {
        return new \DateTime(\mkw\store::convDate($adat));
    }

    public static function convTime($TimeString)
    {
        return $TimeString;
    }

    public static function DateToExcel($datum)
    {
        $dat = $datum;
        if (is_string($datum)) {
            $dat = self::toDate($datum);
        }
        return $dat->format('Y-m-d');
    }

    public static function isValidDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public static function getDayname($day)
    {
        return self::$daynames[$day - 1];
    }

    public static function getDaynameSelectList($sel = null)
    {
        $ret = [];
        foreach (self::$daynames as $k => $v) {
            $ret[] = [
                'id' => $k + 1,
                'caption' => $v,
                'selected' => ($k + 1 === $sel)
            ];
        }
        return $ret;
    }

    public static function getBarionEnvironmentSelectList($sel = null)
    {
        $ret = [];
        foreach (self::$BarionEnvironment as $k => $v) {
            $ret[] = [
                'id' => $k + 1,
                'caption' => $v,
                'selected' => ($k + 1 === $sel)
            ];
        }
        return $ret;
    }

    public static function getFoxpostAPIVersionSelectList($sel = null)
    {
        $ret = [];
        foreach (self::$FoxpostAPIVersions as $k => $v) {
            $ret[] = [
                'id' => $v,
                'caption' => $v,
                'selected' => ($v === $sel)
            ];
        }
        return $ret;
    }

    public static function getExcelCoordinate($o, $sor)
    {
        if ($o < 26) {
            return chr(65 + $o) . (string)$sor;
        }
        return chr(65 + floor($o / 26) - 1) . chr(65 + ($o % 26)) . (string)$sor;
    }

    public static function toBoolStr($i)
    {
        if ($i) {
            return 'true';
        }
        return 'false';
    }

    public static function toXMLNum($n)
    {
        return number_format($n, 2, '.', '');
    }

    public static function createUID($prefix = '')
    {
        return uniqid($prefix);//str_replace('.', '', microtime(true));
    }

    public static function createGUID()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    public static function createSmallImageUrl($kepurl, $pre = '/')
    {
        $t = explode('.', $kepurl);
        $ext = array_pop($t);
        $result = implode('.', $t) . \mkw\store::getParameter('smallimgpost', '') . '.' . $ext;
        if ($kepurl && $kepurl[0] != $pre) {
            return $pre . $result;
        }
        return $result;
    }

    public static function createMediumImageUrl($kepurl, $pre = '/')
    {
        $t = explode('.', $kepurl);
        $ext = array_pop($t);
        $result = implode('.', $t) . \mkw\store::getParameter('mediumimgpost', '') . '.' . $ext;
        if ($kepurl && $kepurl[0] != $pre) {
            return $pre . $result;
        }
        return $result;
    }

    public static function createBigImageUrl($kepurl, $pre = '/')
    {
        $t = explode('.', $kepurl);
        $ext = array_pop($t);
        $result = implode('.', $t) . \mkw\store::getParameter('bigimgpost', '') . '.' . $ext;
        if ($kepurl && $kepurl[0] != $pre) {
            return $pre . $result;
        }
        return $result;
    }

    /**
     *
     * @return \Zend_Session_Namespace
     */
    public static function getMainSession()
    {
        if (!isset(self::$mainsession)) {
            self::$mainsession = new \Zend_Session_Namespace();
        }
        if (!isset(self::$mainsession->initialized)) {
            \Zend_Session::regenerateId();
            self::$mainsession->initialized = true;
        }
        return self::$mainsession;
    }

    public static function destroyMainSession()
    {
        if (isset(self::$mainsession)) {
            \Zend_Session::namespaceUnset('');
//            \Zend_Session::destroy(true);
//            self::$mainsession = null;
        }
    }

    /**
     *
     * @return \Zend_Session_Namespace
     */
    public static function getAdminSession()
    {
        if (!isset(self::$adminsession)) {
            self::$adminsession = new \Zend_Session_Namespace('a');
        }
        if (!isset(self::$adminsession->initialized)) {
            \Zend_Session::regenerateId();
            self::$adminsession->initialized = true;
        }
        return self::$adminsession;
    }

    public static function destroyAdminSession()
    {
        if (isset(self::$adminsession)) {
            \Zend_Session::namespaceUnset('a');
//            \Zend_Session::destroy(true);
//            self::$adminsession = null;
        }
    }

    /**
     *
     * @return \Zend_Session_Namespace
     */
    public static function getPubAdminSession()
    {
        if (!isset(self::$pubadminsession)) {
            self::$pubadminsession = new \Zend_Session_Namespace('pa');
        }
        if (!isset(self::$pubadminsession->initialized)) {
            \Zend_Session::regenerateId();
            self::$pubadminsession->initialized = true;
        }
        return self::$pubadminsession;
    }

    public static function destroyPubAdminSession()
    {
        if (isset(self::$pubadminsession)) {
            \Zend_Session::namespaceUnset('pa');
//            \Zend_Session::destroy(true);
//            self::$pubadminsession = null;
        }
    }

    public static function getSalt()
    {
        return self::getConfigValue('so');
    }

    public static function getAdminSalt()
    {
        return self::getConfigValue('aso');
    }

    public static function getPDFMode()
    {
        return self::getSetupValue('pdfmode');
    }

    /**
     *
     * @return \mkwhelpers\TemplateFactory
     */
    public static function getTemplateFactory()
    {
        if (!isset(self::$templateFactory)) {
            self::$templateFactory = new \mkwhelpers\TemplateFactory(self::$config);
        }
        return self::$templateFactory;
    }

    /**
     * Main sablonokhoz kell
     *
     * @param $v
     * @param bool|true $needmenu
     */
    public static function fillTemplate($v, $needmenu = true)
    {
        $tf = new \Controllers\termekfaController(null);
        $v->setVar('developer', self::isDeveloper());
        $v->setVar('GAFollow', self::getParameter('GAFollow'));
        $v->setVar('seodescription', self::getParameter('seodescription'));
        $v->setVar('feedtermektitle', self::getParameter('feedtermektitle', t('Termékeink')));
        $v->setVar('feedhirtitle', self::getParameter('feedhirtitle', t('Híreink')));
        $v->setVar('dev', self::getConfigValue('developer', false));
        $v->setVar('imagepath', self::getConfigValue('main.imagepath', ''));
        $v->setVar('locale', self::getLocale());
        $v->setVar('jsversion', self::getJSVersion());
        $v->setVar('bootstrapjsversion', self::getBootstrapJSVersion());
        if ($needmenu) {
            $v->setVar('menu1', $tf->getformenu(1, self::getSetupValue('almenunum')));
        }
        $kc = new \Controllers\kosarController(null);
        $minidata = $kc->getMiniData();
        $v->setVar('kosar', $minidata);
        if ($minidata && array_key_exists('termekdb', $minidata)) {
            $v->setVar('termekdb', \mkwhelpers\TypeConverter::toInt($minidata['termekdb']));
        }
        $v->setVar('serverurl', self::getFullUrl());
        $v->setVar('logo', self::getParameter(\mkw\consts::Logo));
        $p = new \mkwhelpers\ParameterHandler();
        $oc = new \Controllers\orszagController($p);
        $v->setVar('orszaglist', $oc->getSelectList(self::getMainSession()->orszag));
        if (self::isMugenrace()) {
            $v->setVar('mugenracelogo', self::getParameter(\mkw\consts::MugenraceLogo));
            $v->setVar('mugenracefooldalkep', self::getParameter(\mkw\consts::MugenraceFooldalKep));
            $v->setVar('mugenracefooldalszoveg', self::getParameter(\mkw\consts::MugenraceFooldalSzoveg));
            $v->setVar('mugenracefejleckep', self::getParameter(\mkw\consts::MugenraceFejlecKep));
            $v->setVar('mugenracefooterlogo', self::getParameter(\mkw\consts::MugenraceFooterLogo));
        }
        $v->setVar('globaltitle', self::getParameter('oldalcim'));
        $v->setVar('valutanemnev', self::getMainSession()->valutanemnev);
        $v->setVar('szktgtermek', self::getParameter(\mkw\consts::SzallitasiKtgTermek));
        $v->setVar('utanvetktgtermek', self::getParameter(\mkw\consts::UtanvetKtgTermek));
        $pr = self::getEm()->getRepository('Entities\Partner');
        $user = [];
        $user['loggedin'] = $pr->checkloggedin();
        if ($user['loggedin']) {
            /** @var \Entities\Partner $u */
            $u = $pr->getLoggedInUser();
            $user['nev'] = $u->getNev();
            $user['email'] = $u->getEmail();
            $user['vezeteknev'] = $u->getVezeteknev();
            $user['keresztnev'] = $u->getKeresztnev();
            $user['telefon'] = $u->getTelefon();
            $user['irszam'] = $u->getIrszam();
            $user['varos'] = $u->getVaros();
            $user['utca'] = $u->getUtca();
            $user['hazszam'] = $u->getHazszam();
            $user['adoszam'] = $u->getAdoszam();
            $user['szallnev'] = $u->getSzallnev();
            $user['szallirszam'] = $u->getSzallirszam();
            $user['szallvaros'] = $u->getSzallvaros();
            $user['szallutca'] = $u->getSzallutca();
            $user['szallhazszam'] = $u->getSzallhazszam();
            $user['szalladategyezik'] = !$u->getNev() &&
                !$u->getIrszam() &&
                !$u->getVaros() &&
                !$u->getUtca() &&
                !$u->getNev();
        } else {
            $user['szalladategyezik'] = true;
        }
        $v->setVar('user', $user);
        if (self::isB2B()) {
            /** @var \Entities\UzletkotoRepository $ukr */
            $ukr = self::getEm()->getRepository('Entities\Uzletkoto');
            $uk = [];
            $uk['loggedin'] = $ukr->checkloggedin();
            if ($uk['loggedin']) {
                /** @var \Entities\Uzletkoto $uko */
                $uko = $ukr->getLoggedInUK();
                $uk['nev'] = $uko->getNev();
                $uk['email'] = $uko->getEmail();
                $uk['jutalek'] = $uko->getJutalek();
                $uk['fo'] = $uko->getFo();
                $ukpfilter = new \mkwhelpers\FilterDescriptor();
                if ($uko->getFo()) {
                    $uklista = $ukr->getByFoUzletkoto($uko->getId());
                    $ukarr = [$uko->getId()];
                    foreach ($uklista as $u) {
                        $ukarr[] = $u->getId();
                    }
                    $ukpfilter->addFilter('uzletkoto', 'IN', $ukarr);
                } else {
                    $ukpfilter->addFilter('uzletkoto', '=', $uko);
                }
                $ukpartnerei = $pr->getAllForSelectList($ukpfilter, ['nev' => 'ASC']);
                $v->setVar('ukpartnerlist', $ukpartnerei);
            }
            $v->setVar('uzletkoto', $uk);
            $v->setVar('myownaccount', \mkw\store::getMainSession()->pk === \mkw\store::getMainSession()->ukpartner);
        } else {
            $v->setVar('myownaccount', true);
        }
        if (\mkw\store::isMugenrace2021()) {
            $mnrnavictrl = new mnrnavigationController(null);
            $mnrnavi = [];
            $navis = self::getEm()->getRepository(MNRNavigation::class)->getAll([], ['szam' => 'ASC']);
            foreach ($navis as $navi) {
                $mnrnavi[] = $mnrnavictrl->loadVars($navi);
            }
            $v->setVar('mnrnavigation', $mnrnavi);

            $tc = new termekfaController(null);
            $defakatid = store::getParameter(consts::Web4DefaKatId);
            /** @var TermekFa $web4defafa */
            $web4defafa = $tc->getRepo()->find($defakatid);
            if ($web4defafa) {
                $v->setVar('mnrdefaultkat', $web4defafa->toLista());
            }
            $v->setVar('localelist', self::getLocaleSelectList(self::getMainLocale()));
        }
        $rut = self::getRouter();
        $v->setVar('showloginlink', $rut->generate('showlogin'));
        $v->setVar('showregisztraciolink', $rut->generate('showregistration'));
        $v->setVar('showaccountlink', $rut->generate('showaccount'));
        $v->setVar('dologoutlink', $rut->generate('dologout'));
        $v->setVar('kosargetlink', $rut->generate('kosarget'));
        $v->setVar('showcheckoutlink', $rut->generate('showcheckout'));
        $v->setVar('prevuri', self::getMainSession()->prevuri ? self::getMainSession()->prevuri : '/');
        $v->setVar('ujtermekjelolourl', self::getParameter(\mkw\consts::UjtermekJelolo));
        $v->setVar('akciosjelolourl', self::getParameter(\mkw\consts::AkcioJelolo));
        $v->setVar('top10jelolourl', self::getParameter(\mkw\consts::Top10Jelolo));
        $v->setVar('ingyenszallitasjelolourl', self::getParameter(\mkw\consts::IngyenszallitasJelolo));
        $popupc = new popupController(null);
        $v->setVar('popups', $popupc->getForShow());
    }

    /**
     *
     * @return \AltoRouter
     */
    public static function getRouter()
    {
        if (!isset(self::$router)) {
            self::$router = new \AltoRouter();
        }
        return self::$router;
    }

    public static function getGdl()
    {
        if (!isset(self::$gdl)) {
            self::$gdl = new generalDataLoader();
        }
        return self::$gdl;
    }

    /**
     *
     * @return \mkwhelpers\HtmlPurifierSanitizer
     */
    public static function getSanitizer()
    {
        if (!isset(self::$sanitizer)) {
            self::$sanitizer = new \mkwhelpers\HtmlPurifierSanitizer();
        }
        return self::$sanitizer;
    }

    public static function storePrevUri()
    {
        \mkw\store::getMainSession()->prevuri = $_SERVER['REQUEST_URI'];
    }

    public static function redirectTo404($keresendo, $params)
    {
        $view = self::getTemplateFactory()->createMainView('404.tpl');
        $tc = new \Controllers\termekController($params);
        $view->setVar('ajanlotttermekek', $tc->getAjanlottLista());
        \mkw\store::fillTemplate($view);
        $view->setVar('seodescription', t('Sajnos nem találjuk: ') . $keresendo);
        $view->setVar('pagetitle', t('Sajnos nem találjuk: ') . $keresendo);
        $view->printTemplateResult(false);
    }

    public static function getFullUrl($slug = null, $url = null)
    {
        if (!$url) {
            if (array_key_exists('SCRIPT_URI', $_SERVER)) {
                $uri = parse_url($_SERVER['SCRIPT_URI']);
                if (!array_key_exists('scheme', $uri) || !$uri['scheme']) {
                    if (self::isSSL()) {
                        $uri['scheme'] = 'https';
                    } else {
                        $uri['scheme'] = 'http';
                    }
                }
                if (!array_key_exists('host', $uri) || !$uri['host']) {
                    $uri['host'] = '';
                }
            } else {
                $uri = [
                    'scheme' => 'http',
                    'host' => $_SERVER['HTTP_HOST']
                ];
                if (self::isSSL()) {
                    $uri['scheme'] = 'https';
                }
            }
            $url = $uri['scheme'] . '://' . $uri['host'];
        }
        $rag = '';
        if ($slug && ($slug[0] !== '/')) {
            $rag = '/';
        }
        return $url . $rag . $slug;
    }

    public static function addHttp($url)
    {
        if ($url && $ret = parse_url($url)) {
            if (!isset($ret['scheme'])) {
                if (self::isSSL()) {
                    $url = 'https://' . $url;
                } else {
                    $url = 'http://' . $url;
                }
            }
        }
        return $url;
    }

    public static function prefixUrl($prefix, $url)
    {
        if ($url && $ret = parse_url($url)) {
            if (!isset($ret['scheme'])) {
                $url = $prefix . $url;
            }
        }
        return $url;
    }

    public static function calcSzallitasiKoltseg($ertek)
    {
        $ktg = 0;
        $h = \mkw\store::getParameter(\mkw\consts::SzallitasiKtg1Ig);
        if (($ertek <= $h) || ($h == 0)) {
            $h = \mkw\store::getParameter(\mkw\consts::SzallitasiKtg1Tol);
            if ($ertek >= $h) {
                $ktg = \mkw\store::getParameter(\mkw\consts::SzallitasiKtg1Ertek);
            }
        } else {
            $h = \mkw\store::getParameter(\mkw\consts::SzallitasiKtg2Ig);
            if (($ertek <= $h) || ($h == 0)) {
                $h = \mkw\store::getParameter(\mkw\consts::SzallitasiKtg2Tol);
                if ($ertek >= $h) {
                    $ktg = \mkw\store::getParameter(\mkw\consts::SzallitasiKtg2Ertek);
                }
            } else {
                $h = \mkw\store::getParameter(\mkw\consts::SzallitasiKtg3Ig);
                if (($ertek <= $h) || ($h == 0)) {
                    $h = \mkw\store::getParameter(\mkw\consts::SzallitasiKtg3Tol);
                    if ($ertek >= $h) {
                        $ktg = \mkw\store::getParameter(\mkw\consts::SzallitasiKtg3Ertek);
                    }
                }
            }
        }
        return $ktg;
    }

    private static function Szazas($szam)
    {
        $szamok = [
            'nulla',
            'egy',
            'kettő',
            'három',
            'négy',
            'öt',
            'hat',
            'hét',
            'nyolc',
            'kilenc',
            'tíz',
            'száz',
            'ezer',
            'millió',
            'mínusz '
        ];
        $szamok2 = ['X', 'tíz', 'húsz', 'harminc', 'negyven', 'ötven', 'hatvan', 'hetven', 'nyolcvan', 'kilencven'];
        $szamok3 = ['Y', 'tizen', 'huszon'];
        $tt_txt = '';
        if ($szam >= 100) {
            $tt_txt = $tt_txt . $szamok[floor($szam / 100)] . $szamok[11];
            $szam = $szam % 100;
        }
        if ($szam >= 10) {
            switch ($szam) {
                case 11:
                case 12:
                case 13:
                case 14:
                case 15:
                case 16:
                case 17:
                case 18:
                case 19:
                case 21:
                case 22:
                case 23:
                case 24:
                case 25:
                case 26:
                case 27:
                case 28:
                case 29:
                    $tt_txt = $tt_txt . $szamok3[floor($szam / 10)];
                    break;
                default:
                    $tt_txt = $tt_txt . $szamok2[floor($szam / 10)];
            }
            $szam = $szam % 10;
        }
        if ($szam > 0) {
            $tt_txt = $tt_txt . $szamok[$szam];
        }
        return $tt_txt;
    }

    public static function Num2Text($num)
    {
        $szamok = [
            'nulla',
            'egy',
            'kettő',
            'három',
            'négy',
            'öt',
            'hat',
            'hét',
            'nyolc',
            'kilenc',
            'tíz',
            'száz',
            'ezer',
            'millió',
            'mínusz '
        ];

        $plus = $num >= 0;
        $num = abs($num);
        $t_txt = '';
        if ($num == 0) {
            $t_txt = $szamok[0];
        } else {
            if (floor($num / 1000000) > 0) {
                $t_txt = $t_txt . \mkw\store::Szazas(floor($num / 1000000)) . $szamok[13];
                if (($num % 1000000) > 0) {
                    $t_txt = $t_txt . '-';
                }
            }
            $num = $num % 1000000;
            if ((floor($num / 1000)) > 0) {
                $t_txt = $t_txt . \mkw\store::Szazas(floor($num / 1000)) . $szamok[12];
                if ((($num % 1000) > 0) && ($num > 2000)) {
                    $t_txt = $t_txt . '-';
                }
            }
            $num = $num % 1000;
            $t_txt = $t_txt . \mkw\store::Szazas($num);
        }
        if (!$plus) {
            $t_txt = $szamok[14] . $t_txt;
        }
        if (self::getTheme() == 'mkwcansas') {
            $t_txt = ucfirst($t_txt);
        }
        return $t_txt;
    }

    /**
     * @param $kelt
     * @param Fizmod $fizmod
     * @param Partner $partner
     *
     * @return string
     * @throws \Exception
     */
    public static function calcEsedekesseg($kelt, $fizmod = null, $partner = null)
    {
        $fmhaladek = 0;
        $partnerhaladek = 0;
        $fmtipus = 'B';
        if ($fizmod) {
            $fmhaladek = $fizmod->getHaladek();
            $fmtipus = $fizmod->getTipus();
        }
        if ($partner) {
            $partnerhaladek = $partner->getFizhatido();
        }
        $dkelt = new \DateTime(self::convDate($kelt));
        if ($fmtipus === 'B') {
            if ($partnerhaladek) {
                $dkelt->add(new \DateInterval('P' . $partnerhaladek . 'D'));
            } else {
                if ($fmhaladek) {
                    $dkelt->add(new \DateInterval('P' . $fmhaladek . 'D'));
                }
            }
        }
        return $dkelt->format(self::$DateFormat);
    }

    public static function urlize($text)
    {
        return \Gedmo\Sluggable\Util\Urlizer::urlize($text);
    }

    public static function changeDirSeparator($dir)
    {
        if (DIRECTORY_SEPARATOR === "\\") {
            $dir = str_replace("/", "\\", $dir);
        } else {
            if (DIRECTORY_SEPARATOR === "/") {
                $dir = str_replace("\\", "/", $dir);
            }
        }
        return $dir;
    }

    public static function createDirectoryRecursively($dir)
    {
        $dir = self::changeDirSeparator($dir);
//            $oldUmask = umask(0);
//            $bCreated = @mkdir($dir, $perms, true);
//            umask($oldUmask);
        $bCreated = @mkdir($dir, 0755, true);

        return $bCreated;
    }

    /**
     * Get file extension (only last part - e.g. extension of file.foo.bar.jpg = jpg)
     *
     * @static
     * @access public
     *
     * @param string $fileName
     *
     * @return string
     */
    public static function getExtension($fileName)
    {
        $dotPos = strrpos($fileName, '.');
        if (false === $dotPos) {
            return "";
        }

        return substr($fileName, strrpos($fileName, '.') + 1);
    }

    public static function explodeCim($cim)
    {
        if ($cim) {
            $ret = [];
            $c = explode(' ', $cim);
            $ret[] = array_key_exists(0, $c) ? $c[0] : '';
            $ret[] = array_key_exists(1, $c) ? $c[1] : '';
            unset($c[0]);
            unset($c[1]);
            $ret[] = implode(' ', $c);
        } else {
            $ret = ['', '', ''];
        }
        return $ret;
    }

    public static function implodeCim($irszam, $varos, $utca, $hazszam)
    {
        $cim = $irszam;
        if (($cim !== '') && ($varos !== '')) {
            $cim .= ' ';
        }
        $cim .= $varos;
        if (($cim !== '') && ($utca !== '')) {
            $cim .= ', ';
        }
        $cim .= $utca;
        if (($cim !== '') && ($hazszam !== '')) {
            $cim .= ' ';
        }
        $cim .= $hazszam;
        return $cim;
    }

    public static function getAdminLocale()
    {
        $l = self::getSetupValue('adminlocale', 'hu');
        if ($l) {
            $l = self::getLocaleName($l);
        }
        return $l;
    }

    public static function getLocale()
    {
        if (self::isMugenrace2021()) {
            $l = self::getMainLocale();
            if (!$l) {
                $l = self::getSetupValue('locale', false);
            }
            self::setMainLocale($l);
        } elseif (self::isMPTNGY()) {
            $l = self::getMainLocale();
        } else {
            $l = self::getSetupValue('locale', false);
            if ($l) {
                $l = self::getLocaleName($l);
            } else {
                $l = self::getParameter(\mkw\consts::Locale);
            }
        }
        return $l;
    }

    public static function getLocaleName($ny)
    {
        return self::$locales[$ny];
    }

    public static function translateLocale($ny)
    {
        if (array_key_exists(strtolower($ny), self::$locales)) {
            return self::$locales[$ny];
        }
        return $ny;
    }

    /**
     * @param $ny
     *
     * @return null|Valutanem
     */
    public static function getValutanemForLocale($ny)
    {
        $v = self::$valutanemperlocale[$ny];
        $valutanem = self::getEm()->getRepository(Valutanem::class)->findOneBy(['nev' => $v]);
        return $valutanem;
    }

    public static function getLocaleList()
    {
        return array_values(self::$locales);
    }

    public static function toLocale($ny)
    {
        $a = self::getLocaleList();
        if (in_array($ny, $a, true)) {
            return $ny;
        }
        return null;
    }

    public static function getLocaleSelectList($sel = null)
    {
        $ret = [];
        foreach (self::$locales as $i => $v) {
            $ret[] = [
                'id' => $v,
                'caption' => strtoupper($i),
                'selected' => ($v === $sel)
            ];
        }
        return $ret;
    }

    /**
     * @return \Entities\Partner
     */
    public static function getLoggedInUser()
    {
        if (!self::$loggedinuser) {
            $pr = self::getEm()->getRepository('Entities\Partner');
            self::$loggedinuser = $pr->getLoggedInUser();
        }
        return self::$loggedinuser;
    }

    /**
     * @return \Entities\Uzletkoto
     */
    public static function getLoggedInUK()
    {
        if (!self::$loggedinuk) {
            $ur = self::getEm()->getRepository('Entities\Uzletkoto');
            self::$loggedinuk = $ur->getLoggedInUK();
        }
        return self::$loggedinuk;
    }

    public static function clearLoggedInUser()
    {
        self::$loggedinuser = null;
    }

    public static function getPubadminLoggedInUser()
    {
        if (!self::$pubadminloggedinuser) {
            $pr = self::getEm()->getRepository(Dolgozo::class);
            self::$pubadminloggedinuser = $pr->getLoggedInUser();
        }
        return self::$pubadminloggedinuser;
    }

    public static function clearPubadminLoggedInUser()
    {
        self::$pubadminloggedinuser = null;
    }

    public static function getTheme()
    {
        return self::getConfigValue('main.theme');
    }

    public static function isMailDebug()
    {
        return self::getConfigValue('mail.debug');
    }

    public static function isDeveloper()
    {
        return self::getConfigValue('developer');
    }

    public static function isReintexTeszt()
    {
        return self::getConfigValue('reintexteszt');
    }

    public static function isMakszutovTeszt()
    {
        return self::getConfigValue('makszutovteszt') || self::getConfigValue('maxutovteszt');
    }

    public static function isCopydepoTeszt()
    {
        return self::getConfigValue('copydepoteszt');
    }

    public static function isMultilang()
    {
        return self::getSetupValue('multilang');
    }

    public static function isArsavok()
    {
        return self::getSetupValue('arsavok');
    }

    public static function isOTPay()
    {
        return self::getSetupValue('otpay');
    }

    public static function isMasterPass()
    {
        return self::getSetupValue('masterpass');
    }

    public static function isEmailTemplateCKEditor()
    {
        return self::getSetupValue('emailtemplateckeditor');
    }

    public static function mustLogin()
    {
        return self::getSetupValue('mustlogin');
    }

    public static function isOsztottFizmod()
    {
        return self::getSetupValue('osztottfizmod');
    }

    public static function isFoglalas()
    {
        return self::getSetupValue('foglalas');
    }

    public static function isB2B()
    {
        return self::getSetupValue('b2b');
    }

    public static function isBankpenztar()
    {
        return self::getSetupValue('bankpenztar');
    }

    public static function isMultiValuta()
    {
        return self::getSetupValue('multivaluta');
    }

    public static function isFakeKintlevoseg()
    {
        return self::getSetupValue('fakekintlevoseg');
    }

    public static function isPartnerAutocomplete()
    {
        return self::getSetupValue('partnerautocomplete');
    }

    public static function isTermekAutocomplete()
    {
        return self::getSetupValue('termekautocomplete', true);
    }

    public static function isKPFolyoszamla()
    {
        return self::getSetupValue('kpfolyoszamla');
    }

    public static function isMultiShop()
    {
        return self::getSetupValue('multishop');
    }

    public static function isEmag()
    {
        return self::getSetupValue('emag');
    }

    public static function isPDF()
    {
        return self::getSetupValue('pdf');
    }

    public static function isSSL()
    {
        return self::getSetupValue('ssl');
    }

    public static function isSzallitasiKtgTermek($termek)
    {
        if (!$termek) {
            return false;
        }
        $i = $termek;
        if (is_a($termek, Termek::class)) {
            $i = $termek->getId();
        }
        return $i == self::getParameter(\mkw\consts::SzallitasiKtgTermek);
    }

    public static function isUtanvetKtgTermek($termek)
    {
        if (!$termek) {
            return false;
        }
        $i = $termek;
        if (is_a($termek, Termek::class)) {
            $i = $termek->getId();
        }
        return $i == self::getParameter(\mkw\consts::UtanvetKtgTermek);
    }

    public static function isFoxpostSzallitasimod($szm)
    {
        if (!self::getParameter(\mkw\consts::FoxpostSzallitasiMod)) {
            return false;
        }
        $i = $szm;
        if (is_a($szm, 'Entities\Szallitasimod')) {
            $i = $szm->getId();
        }
        return $i == self::getParameter(\mkw\consts::FoxpostSzallitasiMod);
    }

    public static function isTOFSzallitasimod($szm)
    {
        if (!self::getParameter(\mkw\consts::TOFSzallitasiMod)) {
            return false;
        }
        $i = $szm;
        if (is_a($szm, 'Entities\Szallitasimod')) {
            $i = $szm->getId();
        }
        return $i == self::getParameter(\mkw\consts::TOFSzallitasiMod);
    }

    public static function isGLSSzallitasimod($szm)
    {
        if (!self::getParameter(\mkw\consts::GLSSzallitasiMod)) {
            return false;
        }
        $i = $szm;
        if (is_a($szm, 'Entities\Szallitasimod')) {
            $i = $szm->getId();
        }
        return $i == self::getParameter(\mkw\consts::GLSSzallitasiMod);
    }

    public static function isGLSFutarSzallitasimod($szm)
    {
        if (!self::getParameter(\mkw\consts::GLSFutarSzallitasmod)) {
            return false;
        }
        $i = $szm;
        if (is_a($szm, 'Entities\Szallitasimod')) {
            $i = $szm->getId();
        }
        return $i == self::getParameter(\mkw\consts::GLSFutarSzallitasmod);
    }

    public static function isUtanvetFizmod($fm)
    {
        if (!self::getParameter(\mkw\consts::UtanvetFizmod)) {
            return false;
        }
        $i = $fm;
        if (is_a($fm, 'Entities\Fizmod')) {
            $i = $fm->getId();
        }
        return $i == self::getParameter(\mkw\consts::UtanvetFizmod);
    }

    public static function isAYCMFizmod($fm)
    {
        if (!self::getParameter(\mkw\consts::AYCMFizmod)) {
            return false;
        }
        $i = $fm;
        if (is_a($fm, 'Entities\Fizmod')) {
            $i = $fm->getId();
        }
        return $i == self::getParameter(\mkw\consts::AYCMFizmod);
    }

    public static function isBarionFizmod($fm)
    {
        if (!self::getParameter(\mkw\consts::BarionFizmod)) {
            return false;
        }
        $i = $fm;
        if (is_a($fm, 'Entities\Fizmod')) {
            $i = $fm->getId();
        }
        return $i == self::getParameter(\mkw\consts::BarionFizmod);
    }

    public static function isSZEPFizmod($fm)
    {
        if (!self::getParameter(\mkw\consts::SZEPFizmod)) {
            return false;
        }
        $i = $fm;
        if (is_a($fm, 'Entities\Fizmod')) {
            $i = $fm->getId();
        }
        return $i == self::getParameter(\mkw\consts::SZEPFizmod);
    }

    public static function isSportkartyaFizmod($fm)
    {
        if (!self::getParameter(\mkw\consts::SportkartyaFizmod)) {
            return false;
        }
        $i = $fm;
        if (is_a($fm, 'Entities\Fizmod')) {
            $i = $fm->getId();
        }
        return $i == self::getParameter(\mkw\consts::SportkartyaFizmod);
    }

    public static function isSuperzoneB2B()
    {
        return self::getTheme() === 'superzoneb2b';
    }

    public static function isMindentkapni()
    {
        return self::getTheme() === 'mkwcansas';
    }

    public static function isVarganyomda()
    {
        return self::getTheme() === 'varganyomda';
    }

    public static function isMugenrace()
    {
        return self::getTheme() === 'mugenrace';
    }

    public static function isMugenrace2021()
    {
        return self::getTheme() === 'mugenrace2021';
    }

    public static function isKisszamlazo()
    {
        return self::getTheme() === 'kisszamlazo';
    }

    public static function isMIJSZ()
    {
        return self::getTheme() === 'mijsz';
    }

    public static function isDarshan()
    {
        return self::getTheme() === 'darshan' || self::getSetupValue('darshan', false);
    }

    public static function isMPT()
    {
        return self::getTheme() === 'mpt' || self::getSetupValue('mpt', false);
    }

    public static function isMPTNGY()
    {
        return self::getTheme() === 'mptngy' || self::getSetupValue('mptngy', false);
    }

    public static function isMiniCRMOn()
    {
        return self::getParameter(\mkw\consts::MiniCRMHasznalatban, false) == 1;
    }

    public static function isWoocommerceOn()
    {
        return self::getSetupValue('woocommerce', false) == 1;
    }

    public static function getWcKey()
    {
        return self::getConfigValue('wc.key');
    }

    public static function getWcSecret()
    {
        return self::getConfigValue('wc.secret');
    }

    public static function getWcUrl()
    {
        return self::getConfigValue('wc.url');
    }

    public static function getWcWebshopNum()
    {
        return self::getConfigValue('wc.webshopnum');
    }

    public static function getWcParcelLabelUrl()
    {
        return self::getConfigValue('wc.parcellabelurl');
    }

    public static function getWcImageUrlPrefix()
    {
        return self::getConfigValue('wc.imageurlprefix');
    }

    public static function getWcClient($options = [])
    {
        return new Client(
            self::getWcUrl(),
            self::getWcKey(),
            self::getWcSecret(),
            array_merge(
                [
                    'wp_api' => true,
                    'version' => 'wc/v3',
                    'timeout' => 360000
                ],
                $options
            )
        );
    }

    public static function getWpAppName()
    {
        return self::getConfigValue('wp.appname');
    }

    public static function getWpAppPassword()
    {
        return self::getConfigValue('wp.apppassword');
    }

    public static function getSzallitasiKoltsegMode()
    {
        $szm = self::getSetupValue('szallitasikoltsegmode');
        switch ($szm) {
            case 'orszagonkent':
                return $szm;
            default:
                return 'normal';
        }
    }

    public static function setAdminMode()
    {
        self::$adminmode = true;
        self::$mainmode = false;
    }

    public static function setMainMode()
    {
        self::$adminmode = false;
        self::$mainmode = true;
    }

    public static function isAdminMode()
    {
        return self::$adminmode;
    }

    public static function isMainMode()
    {
        return self::$mainmode;
    }

    public static function getWebshopFieldName($fname)
    {
        if (self::getWebshopNum() == 1) {
            return $fname;
        }
        return $fname . self::getWebshopNum();
    }

    public static function getWebshopNum()
    {
        return self::getSetupValue('webshopnum', 1);
    }

    public static function getEnabledWebshops()
    {
        return self::getSetupValue('enabledwebshops', 1);
    }

    public static function setTranslationHint($q, $locale)
    {
        if (self::isMultilang() && $locale) {
            $q->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');
            $q->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale);
        }
    }

    public static function getAdminTemplatePath()
    {
        return self::getConfigValue('path.template');
    }

    public static function getAdminDefaultTemplatePath()
    {
        return self::getConfigValue('path.template.default');
    }

    public static function getTmpPath()
    {
        return self::getConfigValue('path.tmp');
    }

    public static function kerekit($mit, $mire)
    {
        if ($mire == 0) {
            return $mit;
        }
        $bmit = abs($mit);
        $sg = min(1, max(-1, $mit == 0 ? 0 : $mit * INF));
        $s = $bmit / $mire;
        $q = strstr($s, '.'); //Frac($s);
        $s = $s - $q;
        if (abs($q) >= 0.5) {
            $s++;
        }
        return $s * $mire * $sg;
    }

    public static function getPartnerValutanem($partner)
    {
        if ($partner) {
            $valutanem = $partner->getValutanem();
        }
        if (!$valutanem) {
            $orszag = self::getEm()->getRepository('Entities\Orszag')->find(self::getMainSession()->orszag);
            if ($orszag) {
                $valutanem = $orszag->getValutanem();
            }
        }
        if (!$valutanem) {
            $valutanem = self::getEm()->getRepository('Entities\Valutanem')->find(self::getParameter(\mkw\consts::Valutanem));
        }
        return $valutanem;
    }

    public static function getPartnerOrszag($partner)
    {
        if ($partner) {
            $orszag = $partner->getOrszag();
        }
        if (!$orszag) {
            $orszag = self::getEm()->getRepository('Entities\Orszag')->find(self::getMainSession()->orszag);
        }
        return $orszag;
    }

    public static function getSysValutanem()
    {
        if (self::getMainSession()->valutanem) {
            return self::getMainSession()->valutanem;
        }
        return self::getParameter(\mkw\consts::Valutanem);
    }

    public static function getPenzugyiStatusz($esedekesseg, $egyenleg)
    {
        $ma = new \DateTime(self::convDate(date(self::$DateFormat)));
        if ($egyenleg != 0) {
            if ($egyenleg > 0) {
                if ($esedekesseg < $ma) {
                    return -2;
                } else {
                    return -1;
                }
            } else {
                return 1;
            }
        }
        return 0;
    }

    public static function setRouteName($name)
    {
        self::$routename = $name;
    }

    public static function getRouteName()
    {
        return self::$routename;
    }

    public static function haveJog($jog)
    {
        $lu = self::getAdminSession()->loggedinuser;
        if (is_array($lu) && array_key_exists('jog', $lu)) {
            return $lu['jog'] >= $jog;
        }
        return false;
    }

    public static function getJog()
    {
        $lu = self::getAdminSession()->loggedinuser;
        if (is_array($lu) && array_key_exists('jog', $lu)) {
            return $lu['jog'];
        }
        return 0;
    }

    public static function translate($mit, $mire = null)
    {
        $sz = [
            'en' => [
                'Rendelés szám' => 'Order no.',
                'Szállítólevél szám' => 'Delivery bill no.'
            ],
            'hu' => [
                'Rendelés szám' => 'Rendelés szám',
                'Szállítólevél szám' => 'Szállítólevél szám'
            ]
        ];

        $mire = substr($mire, 0, 2);

        if ($mire) {
            if (array_key_exists($mire, $sz)) {
                if (array_key_exists($mit, $sz[$mire])) {
                    return $sz[$mire][$mit];
                }
            }
        }
        return $mit;
    }

    public static function toiso($mit)
    {
        if (is_array($mit)) {
            return array_map(function ($el) {
                return mb_convert_encoding($el, 'ISO-8859-2', 'UTF8');
            }, $mit);
        }
        return mb_convert_encoding($mit, 'ISO-8859-2', 'UTF8');
    }

    public static function toutf($mit)
    {
        if (is_array($mit)) {
            return array_map(function ($el) {
                return mb_convert_encoding($el, 'UTF8', 'ISO-8859-2');
            }, $mit);
        }
        return mb_convert_encoding($mit, 'UTF8', 'ISO-8859-2');
    }

    public static function generateEAN13($number)
    {
        $code = '200' . str_pad($number, 9, '0');
        $weightflag = true;
        $sum = 0;
        // Weight for a digit in the checksum is 3, 1, 3.. starting from the last digit.
        // loop backwards to make the loop length-agnostic. The same basic functionality
        // will work for codes of different lengths.
        for ($i = strlen($code) - 1; $i >= 0; $i--) {
            $sum += (int)$code[$i] * ($weightflag ? 3 : 1);
            $weightflag = !$weightflag;
        }
        $code .= (10 - ($sum % 10)) % 10;
        return $code;
    }

    /**
     * @return mixed
     */
    public static function getBlameableListener()
    {
        return self::$blameableListener;
    }

    /**
     * @param mixed $blameableListener
     */
    public static function setBlameableListener($blameableListener)
    {
        self::$blameableListener = $blameableListener;
    }

    public static function cimletez($osszegek)
    {
        $cimletek = [20000, 10000, 5000, 2000, 1000, 500, 200, 100, 50, 20, 10, 5];
        $kiszamolt = [];
        $ret = [];
        $sum = 0;
        foreach ($cimletek as $e) {
            $kiszamolt[$e] = 0;
        }
        $osszegtomb = explode(',', $osszegek);
        foreach ($osszegtomb as $osszeg) {
            $maradek = self::kerekit((int)trim($osszeg), 5);
            $sum += $maradek;
            foreach ($cimletek as $cimlet) {
                $kiszamolt[$cimlet] += intdiv($maradek, $cimlet);
                $maradek = $maradek % $cimlet;
            }
        }
        $ret['cimletek'] = $kiszamolt;
        $ret['osszesen'] = $sum;
        return $ret;
    }

    public static function getIds($mibol)
    {
        $ret = [];
        foreach ($mibol as $m) {
            $ret[] = $m->getId();
        }
        return $ret;
    }

    public static function generateRandomStr($length, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $charactersLength = strlen($chars);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function watermark($img, $dest, $ext)
    {
        $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
        if ($mainpath) {
            $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
        $watermark = imagecreatefrompng($mainpath . ltrim(self::getParameter(\mkw\consts::Watermark), DIRECTORY_SEPARATOR));
        if ($watermark) {
            $watermark_width = imagesx($watermark);
            $watermark_height = imagesy($watermark);
            switch (strtolower($ext)) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($img);
                    break;
                case 'png':
                    $image = imagecreatefrompng($img);
                    break;
            }
            if ($image) {
                $size = getimagesize($img);
                $dest_x = $size[0] - $watermark_width - 5;
                $dest_y = $size[1] - $watermark_height - 5;
                imagecopymerge($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, 40);

                switch (strtolower($ext)) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($image, $dest, 100);
                        break;
                    case 'png':
                        imagepng($image, $dest, 100);
                        break;
                }
                imagedestroy($image);
            }
            imagedestroy($watermark);
        }
    }

    public static function CData($s)
    {
        return '<![CDATA[' . $s . ']]>';
    }

    public static function NAVNum($num)
    {
        return number_format($num, 2, '.', '');
    }

    public static function strpos_array($haystack, $needles)
    {
        if ($haystack && $needles && is_array($needles)) {
            foreach ($needles as $word) {
                if ($word && strpos($haystack, $word) !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function n($mit)
    {
        return ord($mit) - 97;
    }

    public static function mb_ucfirst($str)
    {
        $s = mb_strtolower($str);
        $fc = mb_strtoupper(mb_substr($s, 0, 1));
        return $fc . mb_substr($s, 1);
    }

    public static function startOfWeek($date = null)
    {
        if (is_a($date, 'DateTime')) {
            $d = clone $date;
            if ($d->format('N') === '1') {
                return $d;
            }
            return $d->modify('last monday');
        } else {
            if ($date == '') {
                $d = date(\mkw\store::$DateFormat);
            }
            $d = new \DateTime(\mkw\store::convDate($d));
            if ($d->format('N') === '1') {
                return $d;
            }
            return $d->modify('last monday');
        }
    }

    public static function endOfWeek($date = null)
    {
        if (is_a($date, 'DateTime')) {
            $d = clone $date;
            if ($d->format('N') === '7') {
                return $d;
            }
            return $d->modify('next sunday');
        } else {
            if ($date == '') {
                $d = date(\mkw\store::$DateFormat);
            }
            $d = new \DateTime(\mkw\store::convDate($d));
            if ($d->format('N') === '7') {
                return $d;
            }
            return $d->modify('next sunday');
        }
    }

    public static function getCommaList($vmi)
    {
        if (is_array($vmi)) {
            return implode(',', $vmi);
        }
        return $vmi;
    }

    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function isSendableEmail($email)
    {
        if (strpos($email, '@mail.local') || strpos($email, '_@jogadarshan.hu')) {
            return false;
        }
        return self::isValidEmail($email);
    }

    public static function mainStoragePath($filename)
    {
        $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainstoragepath'));
        if ($mainpath) {
            $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        } else {
            $mainpath = getcwd() . '/storage/';
        }
        return $mainpath . $filename;
    }

    public static function storagePath($filename)
    {
        return getcwd() . '/' . self::getConfigValue('path.storage', '') . $filename;
    }

    public static function exporttemplatePath($filename)
    {
        return getcwd() . '/exporttemplates/' . $filename;
    }

    public static function storageUrl($filename)
    {
        return '/' . self::getConfigValue('path.storage', '') . $filename;
    }

    public static function logsPath($filename)
    {
        return getcwd() . '/' . self::getConfigValue('path.storage', '') . 'logs/' . $filename;
    }

    public static function logsUrl($filename)
    {
        return '/' . self::getConfigValue('path.storage', '') . 'logs/' . $filename;
    }

    public static function getTulajAdoszam()
    {
        return self::getParameter(\mkw\consts::Tulajadoszam);
    }

    public static function getNAVOnlineEnv()
    {
        if (self::isDeveloper()) {
            return 'dev';
        }
        return self::getParameter(\mkw\consts::NAVOnlineEnv);
    }

    public static function getNAVOnlineErtekhatar()
    {
        if (self::isDeveloper()) {
            return 0;
        }
        return (float)self::getParameter(\mkw\consts::NAVOnlineErtekhatar, 0);
    }

    public static function isMagyarAdoszam($adoszam)
    {
        preg_match('/\d{8}-\d{1}-\d{2}/m', $adoszam, $out);
        if ($out) {
            return $out[0] == $adoszam;
        }
        return false;
    }

    public static function isClosed()
    {
        return self::getConfigValue('closed', false);
    }

    public static function calcSzallitasiidoAddition($date)
    {
        if ($date->format(self::$TimeFormat) > '13:00') {
            if ($date->format('N') < 6) {
                return 1;
            }
        }
        return 0;
    }

    public static function setMainLocale($ny)
    {
        $ny = self::translateLocale($ny);
        \mkw\store::getMainSession()->locale = $ny;
        $v = self::getValutanemForLocale($ny);
        if ($v) {
            \mkw\store::getMainSession()->valutanem = $v->getId();
            \mkw\store::getMainSession()->valutanemnev = $v->getNev();
        }
    }

    public static function getMainLocale()
    {
        return \mkw\store::getMainSession()->locale;
    }

    public static function getMainValutanemNev()
    {
        return \mkw\store::getMainSession()->valutanemnev;
    }

    public static function getMainValutanemId()
    {
        return \mkw\store::getMainSession()->valutanem;
    }

    public static function quarterRound($mit)
    {
        $x = $mit * 4;
        $x = ceil($x);
        return $x / 4;
    }

    public static function getMPTNGYDateList($day = null)
    {
        return [
            1 => [
                'id' => 1,
                'caption' => self::getParameter(\mkw\consts::MPTNGYDatum1),
                'selected' => $day === 1
            ],
            2 => [
                'id' => 2,
                'caption' => self::getParameter(\mkw\consts::MPTNGYDatum2),
                'selected' => $day === 2
            ],
            3 => [
                'id' => 3,
                'caption' => self::getParameter(\mkw\consts::MPTNGYDatum3),
                'selected' => $day === 3
            ]
        ];
    }

    public static function getMPTNGYDate($day)
    {
        if ($day < 1 || $day > 3) {
            return '';
        }
        $l = self::getMPTNGYDateList();
        return $l[$day]['caption'];
    }

    public static function getPDFEngine($html)
    {
        switch (self::getPDFMode()) {
            case 'dompdf':
                return new mkwdompdf($html);
            case 'wkhtmltopdf':
            default:
                return new mkwwkhtmltopdf($html);
        }
    }

    public static function generatePassword($length = 8)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $password;
    }

    public static function getCredentialsPath()
    {
        if (self::getConfigValue('credentials.path')) {
            return self::getConfigValue('credentials.path');
        }
        return getcwd();
    }

    public static function keysPath($filename)
    {
        return self::getCredentialsPath() . '/keys/' . $filename;
    }

    public static function tokensPath($filename)
    {
        return self::getCredentialsPath() . '/tokens/' . $filename;
    }

}
