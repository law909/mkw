<?php

namespace mkw;

use Doctrine\ORM\EntityManager;

class Store {

    private static $em;
    private static $config;
    private static $setup;
    private static $mainsession;
    private static $adminsession;
    private static $templateFactory;
    private static $router;
    private static $gdl;
    private static $sanitizer;
    public static $DateFormat = 'Y.m.d';
    public static $DateTimeFormat = 'Y.m.d. H:i:s';

    public function getJSVersion() {
        return 16;
    }

    public function getBootstrapJSVersion() {
        return 6;
    }

    public static function getClientIp() {
        $ipaddress = '';
        if (array_key_exists('HTTP_CLIENT_IP', $_SERVER) && $_SERVER['HTTP_CLIENT_IP']) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        }
        else {
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else {
                if (array_key_exists('HTTP_X_FORWARDED', $_SERVER) && $_SERVER['HTTP_X_FORWARDED']) {
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                }
                else {
                    if (array_key_exists('HTTP_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_FORWARDED_FOR']) {
                        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                    }
                    else {
                        if (array_key_exists('HTTP_FORWARDED', $_SERVER) && $_SERVER['HTTP_FORWARDED']) {
                            $ipaddress = $_SERVER['HTTP_FORWARDED'];
                        }
                        else {
                            if (array_key_exists('REMOTE_ADDR', $_SERVER) && $_SERVER['REMOTE_ADDR']) {
                                $ipaddress = $_SERVER['REMOTE_ADDR'];
                            }
                            else {
                                $ipaddress = 'UNKNOWN';
                            }
                        }
                    }
                }
            }
        }
        return $ipaddress;
    }

    public static function writelog($text, $fname = 'log.txt') {
        $handle = fopen($fname, "a");
        $log = "";
        $separator = " ## ";
        $log.=date('Y.m.d. H:i:s') . $separator;
        $log.=self::getClientIp() . $separator;
        $log.=$text;
        $log.="\n";
        fwrite($handle, $log);
        fclose($handle);
    }

    public static function getMailer() {
        if (self::getConfigValue('mail.smtp', 0)) {
            return new mkwzendmailer();
        }
        return new mkwmailer();
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public static function getEm() {
        return self::$em;
    }

    public static function setEm($em) {
        self::$em = $em;
    }

    public static function getConfig() {
        return self::$config;
    }

    public static function getConfigValue($key, $def = null) {
        if (array_key_exists($key, self::$config)) {
            return self::$config[$key];
        }
        return $def;
    }

    public static function setConfig($config) {
        self::$config = $config;
    }

    public static function getSetup() {
        return self::$setup;
    }

    public static function getSetupValue($key) {
        return self::$setup[$key];
    }

    public static function setSetup($setup) {
        self::$setup = $setup;
    }

    public static function getParameter($par, $default = null) {
        $p = self::getEm()->getRepository('Entities\Parameterek')->find($par);
        if ($p) {
            return $p->getErtek();
        }
        else {
            return $default;
        }
    }

    public static function setParameter($par, $ertek) {
        $p = self::getEm()->getRepository('Entities\Parameterek')->find($par);
        if ($p) {
            $p->setErtek($ertek);
        }
        else {
            $p = new \Entities\Parameterek();
            $p->setId($par);
            $p->setErtek($ertek);
        }
        self::getEm()->persist($p);
        self::getEm()->flush();
    }

    /* TODO ezen lehet h. csiszolni kell meg */

    public static function convDate($DateString) {
        return str_replace('.', '-', $DateString);
    }

    public static function createUID($prefix = '') {
        return uniqid($prefix);//str_replace('.', '', microtime(true));
    }

    public static function createGUID() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
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
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public static function createSmallImageUrl($kepurl, $pre = '/') {
        $t = explode('.', $kepurl);
        $ext = array_pop($t);
        $result = implode('.', $t) . store::getParameter('smallimgpost', '') . '.' . $ext;
        if ($kepurl && $kepurl[0] != $pre) {
            return $pre . $result;
        }
        return $result;
    }

    public static function createMediumImageUrl($kepurl, $pre = '/') {
        $t = explode('.', $kepurl);
        $ext = array_pop($t);
        $result = implode('.', $t) . store::getParameter('mediumimgpost', '') . '.' . $ext;
        if ($kepurl && $kepurl[0] != $pre) {
            return $pre . $result;
        }
        return $result;
    }

    public static function createBigImageUrl($kepurl, $pre = '/') {
        $t = explode('.', $kepurl);
        $ext = array_pop($t);
        $result = implode('.', $t) . store::getParameter('bigimgpost', '') . '.' . $ext;
        if ($kepurl && $kepurl[0] != $pre) {
            return $pre . $result;
        }
        return $result;
    }

    /**
     *
     * @return Zend_Session_Namespace
     */
    public static function getMainSession() {
        if (!isset(self::$mainsession)) {
            self::$mainsession = new \Zend_Session_Namespace();
        }
        if (!isset(self::$mainsession->initialized)) {
            \Zend_Session::regenerateId();
            self::$mainsession->initialized = true;
        }
        return self::$mainsession;
    }

    public static function destroyMainSession() {
        if (isset(self::$mainsession)) {
            \Zend_Session::namespaceUnset('');
            \Zend_Session::destroy(true);
            self::$mainsession = null;
        }
    }

    /**
     *
     * @return \Zend_Session_Namespace
     */
    public static function getAdminSession() {
        if (!isset(self::$adminsession)) {
            self::$adminsession = new \Zend_Session_Namespace('a');
        }
        if (!isset(self::$adminsession->initialized)) {
            \Zend_Session::regenerateId();
            self::$adminsession->initialized = true;
        }
        return self::$adminsession;
    }

    public static function destroyAdminSession() {
        if (isset(self::$adminsession)) {
            \Zend_Session::namespaceUnset('a');
            \Zend_Session::destroy(true);
            self::$adminsession = null;
        }
    }

    public static function getSalt() {
        return self::getConfigValue('so');
    }

    public static function getAdminSalt() {
        return self::getConfigValue('aso');
    }

    /**
     *
     * @return \mkwhelpers\TemplateFactory
     */
    public static function getTemplateFactory() {
        if (!isset(self::$templateFactory)) {
            self::$templateFactory = new \mkwhelpers\TemplateFactory(self::$config);
        }
        return self::$templateFactory;
    }

    public static function fillTemplate($v) {
        $tf = new \Controllers\termekfaController(null);
        $v->setVar('GAFollow', self::getParameter('GAFollow'));
        $v->setVar('seodescription', self::getParameter('seodescription'));
        $v->setVar('feedtermektitle', self::getParameter('feedtermektitle', t('Termékeink')));
        $v->setVar('feedhirtitle', self::getParameter('feedhirtitle', t('Híreink')));
        $v->setVar('dev', self::getConfigValue('developer', false));
        $v->setVar('jsversion', self::getJSVersion());
        $v->setVar('bootstrapjsversion', self::getBootstrapJSVersion());
        $v->setVar('menu1', $tf->getformenu(1, self::getSetupValue('almenunum')));
        $v->setVar('serverurl', self::getFullUrl());
        $v->setVar('logo', self::getParameter(consts::Logo));
        $v->setVar('globaltitle', self::getParameter('oldalcim'));
        $kc = new \Controllers\kosarController(null);
        $v->setVar('kosar', $kc->getMiniData());
        $pr = self::getEm()->getRepository('Entities\Partner');
        $user = array();
        $user['loggedin'] = $pr->checkloggedin();
        if ($user['loggedin']) {
            $u = $pr->getLoggedInUser();
            $user['nev'] = $u->getNev();
            $user['email'] = $u->getEmail();
            $user['vezeteknev'] = $u->getVezeteknev();
            $user['keresztnev'] = $u->getKeresztnev();
            $user['telefon'] = $u->getTelefon();
            $user['irszam'] = $u->getIrszam();
            $user['varos'] = $u->getVaros();
            $user['utca'] = $u->getUtca();
            $user['adoszam'] = $u->getAdoszam();
            $user['szallnev'] = $u->getSzallnev();
            $user['szallirszam'] = $u->getSzallirszam();
            $user['szallvaros'] = $u->getSzallvaros();
            $user['szallutca'] = $u->getSzallutca();
            $user['szalladategyezik'] = !$u->getNev() &&
                    !$u->getIrszam() &&
                    !$u->getVaros() &&
                    !$u->getUtca() &&
                    !$u->getNev();
        }
        else {
            $user['szalladategyezik'] = true;
        }
        $v->setVar('user', $user);
        $rut = self::getRouter();
        $v->setVar('showloginlink', $rut->generate('showlogin'));
        $v->setVar('showregisztraciolink', $rut->generate('showregistration'));
        $v->setVar('showaccountlink', $rut->generate('showaccount'));
        $v->setVar('dologoutlink', $rut->generate('dologout'));
        $v->setVar('kosargetlink', $rut->generate('kosarget'));
        $v->setVar('showcheckoutlink', $rut->generate('showcheckout'));
        $v->setVar('prevuri', self::getMainSession()->prevuri ? self::getMainSession()->prevuri : '/');
        $v->setVar('ujtermekjelolourl', self::getParameter(consts::UjtermekJelolo));
        $v->setVar('akciosjelolourl', self::getParameter(consts::AkcioJelolo));
        $v->setVar('top10jelolourl', self::getParameter(consts::Top10Jelolo));
        $v->setVar('ingyenszallitasjelolourl', self::getParameter(consts::IngyenszallitasJelolo));
    }

    /**
     *
     * @return \AltoRouter
     */
    public static function getRouter() {
        if (!isset(self::$router)) {
            self::$router = new \AltoRouter();
        }
        return self::$router;
    }

    public static function getGdl() {
        if (!isset(self::$gdl)) {
            self::$gdl = new generalDataLoader();
        }
        return self::$gdl;
    }

    /**
     *
     * @return \mkwhelpers\HtmlPurifierSanitizer
     */
    public static function getSanitizer() {
        if (!isset(self::$sanitizer)) {
            self::$sanitizer = new \mkwhelpers\HtmlPurifierSanitizer();
        }
        return self::$sanitizer;
    }

    public static function storePrevUri() {
        store::getMainSession()->prevuri = $_SERVER['REQUEST_URI'];
    }

    public static function redirectTo404($keresendo, $params) {
        $view = self::getTemplateFactory()->createMainView('404.tpl');
        $tc = new \Controllers\termekController($params);
        $view->setVar('ajanlotttermekek', $tc->getAjanlottLista());
        store::fillTemplate($view);
        $view->setVar('seodescription', t('Sajnos nem találjuk: ') . $keresendo);
        $view->setVar('pagetitle', t('Sajnos nem találjuk: ') . $keresendo);
        $view->printTemplateResult(false);
    }

    public static function getFullUrl($slug = null, $url = null) {
        if (!$url) {
            if (array_key_exists('SCRIPT_URI', $_SERVER)) {
                $uri = parse_url($_SERVER['SCRIPT_URI']);
                if (!array_key_exists('scheme', $uri) || !$uri['scheme']) {
                    $uri['scheme'] = 'http';
                }
                if (!array_key_exists('host', $uri) || !$uri['host']) {
                    $uri['host'] = '';
                }
            }
            else {
                $uri = array(
                    'scheme' => 'http',
                    'host' => $_SERVER['HTTP_HOST']
                );
            }
            $url = $uri['scheme'] . '://' . $uri['host'];
        }
        $rag = '';
        if (($slug[0] !== '/') && $slug) {
            $rag = '/';
        }
        return $url . $rag . $slug;
    }

    public static function calcSzallitasiKoltseg($ertek) {
        $ktg = 0;
        $h = Store::getParameter(\mkw\consts::SzallitasiKtg1Ig);
        if (($ertek <= $h) || ($h == 0)) {
            $h = Store::getParameter(\mkw\consts::SzallitasiKtg1Tol);
            if ($ertek >= $h) {
                $ktg = Store::getParameter(\mkw\consts::SzallitasiKtg1Ertek);
            }
        }
        else {
            $h = Store::getParameter(\mkw\consts::SzallitasiKtg2Ig);
            if (($ertek <= $h) || ($h == 0)) {
                $h = Store::getParameter(\mkw\consts::SzallitasiKtg2Tol);
                if ($ertek >= $h) {
                    $ktg = Store::getParameter(\mkw\consts::SzallitasiKtg2Ertek);
                }
            }
            else {
                $h = Store::getParameter(\mkw\consts::SzallitasiKtg3Ig);
                if (($ertek <= $h) || ($h == 0)) {
                    $h = Store::getParameter(\mkw\consts::SzallitasiKtg3Tol);
                    if ($ertek >= $h) {
                        $ktg = Store::getParameter(\mkw\consts::SzallitasiKtg3Ertek);
                    }
                }
            }
        }
        return $ktg;
    }

    private static function Szazas($szam) {
        $szamok = array('nulla', 'egy', 'kettő', 'három', 'négy', 'öt',
            'hat', 'hét', 'nyolc', 'kilenc', 'tíz', 'száz', 'ezer', 'millió', 'mínusz ');
        $szamok2 = array('X','tíz', 'húsz', 'harminc', 'negyven', 'ötven', 'hatvan', 'hetven', 'nyolcvan', 'kilencven');
        $szamok3 = array('Y','tizen', 'huszon');
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

    public static function Num2Text($num) {
        $szamok = array('nulla', 'egy', 'kettő', 'három', 'négy', 'öt',
            'hat', 'hét', 'nyolc', 'kilenc', 'tíz', 'száz', 'ezer', 'millió', 'mínusz ');

        $plus = $num >= 0;
        $num = abs($num);
        $t_txt = '';
        if ($num == 0) {
            $t_txt = $szamok[0];
        }
        else {
            if (floor($num / 1000000) > 0) {
                $t_txt = $t_txt . Store::Szazas(floor($num / 1000000)) . $szamok[13];
                if (($num % 1000000) > 0) {
                    $t_txt = $t_txt . '-';
                }
            }
            $num = $num % 1000000;
            if ((floor($num / 1000)) > 0) {
                $t_txt = $t_txt . Store::Szazas(floor($num / 1000)) . $szamok[12];
                if ((($num % 1000) > 0) && ($num > 2000)) {
                    $t_txt = $t_txt . '-';
                }
            }
            $num = $num % 1000;
            $t_txt = $t_txt . Store::Szazas($num);
        }
        if (!$plus) {
            $t_txt = $szamok[14] . $t_txt;
        }
        $t_txt = ucfirst($t_txt);
        return $t_txt;
    }

    public static function calcEsedekesseg($kelt, $fizmod = null, $partner = null) {
        $fmhaladek = 0;
        $partnerhaladek = 0;
        if ($fizmod) {
            $fmhaladek = $fizmod->getHaladek();
        }
        if ($partner) {
            $partnerhaladek = $partner->getFizhatido();
        }
        $dkelt = new \DateTime(self::convDate($kelt));
        if ($partnerhaladek) {
            $dkelt->add(new \DateInterval('P' . $partnerhaladek . 'D'));
        }
        else {
            if ($fmhaladek) {
                $dkelt->add(new \DateInterval('P' . $fmhaladek . 'D'));
            }
        }
        return $dkelt->format(self::$DateFormat);
    }

    public static function urlize($text) {
        return \Gedmo\Sluggable\Util\Urlizer::urlize($text);
    }

    public static function changeDirSeparator($dir) {
        if (DIRECTORY_SEPARATOR === "\\") {
            $dir = str_replace("/", "\\", $dir);
        }
        else if (DIRECTORY_SEPARATOR === "/") {
            $dir = str_replace("\\", "/", $dir);
        }
        return $dir;
    }

    public static function createDirectoryRecursively($dir) {
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
     * @param string $fileName
     * @return string
     */
    public static function getExtension( $fileName ) {
        $dotPos = strrpos( $fileName, '.' );
        if (false === $dotPos) {
            return "";
        }

        return substr( $fileName, strrpos( $fileName, '.' ) +1 ) ;
    }

    public static function explodeCim($cim) {
        if ($cim) {
            $ret = array();
            $c = explode(' ', $cim);
            $ret[] = array_key_exists(0, $c) ? $c[0] : '';
            $ret[] = array_key_exists(1, $c) ? $c[1] : '';
            unset($c[0]);
            unset($c[1]);
            $ret[] = implode(' ', $c);
        }
        else {
            $ret = array('', '', '');
        }
        return $ret;
    }

    public static function getOTPayErrorMessage($error) {
        $a = array(
            '000' => 'A művelet rendben lefutott.',
            '101' => 'Ön még nem töltötte le az OTPay alkalmazást és nem regisztrálta a bankkártyáját.',
            '102' => 'A terhelésre megjelölt Ügyfélnek nincs regisztrált fizetőeszköze.',
            '103' => 'A terhelésre megjelölt Ügyfélnek nincs aktív fizetőeszköze.',
            '104' => 'Nincs közös Küldő Csoport',
            '105' => 'Az Ügyfél Megerősítés kérés kikézbesítése nem sikerült.',
            '110' => 'Az Ügyfél Megerősítés elutasításra került.',
            '112' => 'Időközben változás történt: a választott fizetőeszköz korábban nem volt alternatíva.',
            '113' => 'Időközben változás történt: a terhelésre megjelölt fizetőeszköz már nem aktív.',
            '114' => 'Időközben változás történt: a Kereskedői Virtuális terminál adatai nem elérhetők.',
            '115' => 'Időközben változás történt: nincs közös Küldő Csoport.',
            '116' => 'Ügyfél Megerősítés válaszra adott határidő idő elmúlt.',
            '140' => 'A megadott azonosítókkal nincs regisztrált Integrált Kereskedői virtuális terminál.',
            '154' => 'A fizetőeszköz Banki hitelesítése sikertelen.',
            '161' => 'A Banki engedélyezési funkció nem elérhető.',
            '163' => 'Az Ügyfél Banki beazonosítása sikertelen.',
            '164' => 'A Kereskedő Banki beazonosítása sikertelen.',
            '165' => 'A fizetőeszköz Banki hitelesítése sikertelen.',
            '166' => 'A terhelést a bank nem engedélyezte.',
            '172' => 'Időközben változás történt2: a terhelésre megjelölt SIM-en nincs regisztrált MPI.',
            '174' => 'Időközben változás történt2: a terhelésre megjelölt fizetőeszköz nem azonosítható.',
            '185' => 'A terhelést a bank nem engedélyezte.',
            '186' => 'A terhelést a bank nem engedélyezte.',
            '189' => 'A Kereskedői referencia adatok nem találhatók.',
            '190' => 'Bankkártya rendszer elutasítás: Nem elfogadott kártya',
            '191' => 'Bankkártya rendszer elutasítás: Lejárt kártya',
            '192' => 'Bankkártya rendszer elutasítás: A tranzakció nem engedélyezett a kártyatulajdonos számára.',
            '193' => 'Bankkártya rendszer elutasítás: Elveszett kártya.',
            '194' => 'Bankkártya rendszer elutasítás: Korlátozott kártya.',
            '195' => 'Bankkártya rendszer elutasítás: Lopott kártya használata.',
            '196' => 'Bankkártya rendszer elutasítás: Érvénytelen kártyaszám.',
            '198' => 'A kérést a Bankkártya rendszer egyéb okból elutasította.',
            '200' => 'A Bankkártya rendszer nem elérhető.',
            '203' => 'A terhelés sikertelen, a kérés egyéb okból elutasításra került.',
            '208' => 'Az Integrált Kereskedő nem aktív.',
            '209' => 'Az Integrált Kereskedői Terminál nem aktív.',
            '215' => 'Technikai hiba történt, hívja az ügyfélszolgálatot',
            '218' => 'A tranzakció kérésben megadott érvényességi idő nem megfelelő.',
            '230' => 'A tranzakció sztornózásra fog kerülni.',
            '233' => 'A tranzakcióra sztornó miatt megszakítva, pénzmozgás nem történt.',
            '240' => 'A kérésben megadott bankTrxId azonosított folyamat nem a kérésben megadott kereskedőre vonatkozik.',
            '241' => 'A pénz foglalás elfogadás határideje lejárt. Felszabadításra vonatkozó üzenet kerül elküldésre.',
            '242' => 'A foglalás utáni terhelést a vevő elutasította. Felszabadításra vonatkozó üzenet kerül elküldésre.',
            '243' => 'A pénzfoglalás sikertelen volt nem küldhető rá pénzügyi tranzakció értesítés üzenet.',
            '245' => 'A vevő azonosítói (MFA , Telefonszám) megváltoztak az eredeti tranzakció óta.',
            '246' => 'Az elszámoló megváltozott az eredeti tranzakció óta.',
            '247' => 'A megadott kereskedői terminálnak nincs joga a ’Refund’ művelethez.',
            '248' => 'A Refund művelethez rendelt napi limit túllépésre került.',
            '249' => 'A Refund művelethez rendelt havi limit túllépésre került.',
            '250' => 'A jóváírásra kiválasztott MPI nem azonos az eredetileg kiválasztottal.',
            '252' => 'A műveletben csak off-us MPI vehet részt.',
            '253' => 'A megadott kereskedői terminálnak nincs joga az Authorize művelethez.',
            '254' => 'A jóváírás kérés elutasításra került.',
            '255' => 'Nincs megfelelő fizetőeszköz és a központi fizetőeszköz tulajdonságtár nem elérhető.',
            '256' => 'A Mobil Kliens eléréshez szükséges MPG nem elérhető.',
            '257' => 'A Mobil Kliens eléréshez szükséges MPG nem ismert',
            '258' => 'A központi fizetőeszköz tár nem tartalmaz megfelelő fizetőeszközt.',
            '260' => 'A megadott origBankTrxId nem létezik.',
            '261' => 'A bankkártyához nincs megfelelő tulajdonság hozzárendelés.'
        );
        return $a[$error];
    }
}
