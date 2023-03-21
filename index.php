<?php

use Doctrine\ORM\Query\ResultSetMapping;
use mkw\store;

date_default_timezone_set('Europe/Budapest');

require_once('bootstrap.php');

$__maintranslate = new Zend_Translate(
    [
        'adapter' => 'array',
        'content' => 'locales/main/hu.php',
        'locale' => 'hu_hu'
    ]
);

$__admintranslate = new Zend_Translate(
    [
        'adapter' => 'array',
        'content' => 'locales/admin/hu.php',
        'locale' => 'hu_hu'
    ]
);

function t($msgid)
{
    global $__maintranslate;
    $x = $__maintranslate->_($msgid);
    if (\mkw\store::getSetupValue('translate') && $x === $msgid) {
        store::writetranslation($msgid, 'transmain' . $__maintranslate->getLocale() . '.txt');
    }
    return $x;
}

function at($msgid)
{
    global $__admintranslate;
    $x = $__admintranslate->_($msgid);
    if (\mkw\store::getSetupValue('translate') && $x === $msgid) {
        store::writetranslation($msgid, 'transadmin' . $__admintranslate->getLocale() . '.txt');
    }
    return $x;
}

function haveJog($jog)
{
    return \mkw\store::haveJog($jog);
}

function bizformat($mit, $mire = false)
{
    if ($mire === false) {
        $mire = 2;
    }
    return number_format($mit, $mire, ',', ' ');
}

function prefixUrl($prefix, $url)
{
    return \mkw\store::prefixUrl($prefix, $url);
}

// TODO find an appropriate place
function callTheController($target, $params)
{
    if ($target) {
        $methodname = '';
        $a = explode('#', $target);
        $classname = $a[0];
        if (count($a) > 1) {
            $methodname = $a[1];
        }
        if (strpos($classname, '\\') === false) {
            $classname = '\Controllers\\' . $classname;
        }
        $path = explode('/', str_replace('\\', '/', $classname . '.php'));
        $inc = ltrim(implode('/', $path), '/');
        if (file_exists($inc) && $methodname) {
            require_once $inc;
            $instance = new $classname(new \mkwhelpers\ParameterHandler($params));
            $instance->$methodname();
            return true;
        }
    }
    return false;
}

function exc_handler($e)
{
    if (is_a($e, '\Doctrine\ORM\Query\QueryException')) {
        error_log($e->getMessage());
    }
}

//set_exception_handler('exc_handler');

if ($ini['developer']) {
    if (in_array(\mkw\store::getExtension($_SERVER['REQUEST_URI']), ['jpg', 'gif', 'png', 'jpeg'])) {
        die();
    }
}

if (\mkw\store::isMPTNGY() && !\mkw\store::isDeveloper()) {
    ini_set('session.cookie_samesite', 'None');
    ini_set('session.cookie_secure', '1');
}

$mainsess = store::getMainSession();

try {
    $conn = store::getEm()->getConnection()->connect();
} catch (\Exception $e) {
    throw $e;
}

if (store::getSetupValue('rewrite301')) {
    $rw301c = new \Controllers\rewrite301Controller([]);
    $rw301c->rewrite();
}

$router = store::getRouter();
if (file_exists('mainroute.php')) {
    require_once 'mainroute.php';
}
if (file_exists('adminroute.php')) {
    require_once 'adminroute.php';
}
if (file_exists('pubadminroute.php')) {
    require_once 'pubadminroute.php';
}
$redirected = false;

$webshopnum = store::getSetupValue('webshopnum', '1');
if ($webshopnum == '1') {
    $webshopnum = '';
}
$match = $router->match();
if (store::getParameter(\mkw\consts::Off . $webshopnum) &&
    substr($match['name'], 0, 5) !== 'admin' &&
    substr($match['name'], 0, 8) !== 'pubadmin'
) {
    callTheController('mainController#showOff', []);
} else {
    if ($match) {
        store::setRouteName($match['name']);
        switch (true) {
            case (substr($match['name'], 0, 5) === 'admin'):
                if ($ini['admin']) {
                    store::setAdminMode();

                    require_once 'runonce.php';

                    $__admintranslate->addTranslation(
                        [
                            'adapter' => 'array',
                            'content' => 'locales/admin/en.php',
                            'locale' => 'en_us'
                        ]
                    );
                    if (store::getAdminLocale()) {
                        $__admintranslate->setLocale(store::getAdminLocale());
                    }

                    if ((!in_array($match['name'], ['admincron', 'adminshowlogin', 'adminlogin', 'adminrlbexport', 'adminminicrmmail']))) {
                        $linuser = store::getAdminSession()->pk;
                        if (!$linuser) {
                            $redirected = true;
                            header('Location: ' . $router->generate('adminshowlogin'));
                        }
                        \mkw\store::getBlameableListener()->setUserValue(\mkw\store::getEm()->getRepository('Entities\Dolgozo')->find($linuser));
                    }
                } else {
                    $redirected = true;
                    header('HTTP/1.1 404 Not found');
                    callTheController('mainController#show404', []);
                }
                break;
            case (substr($match['name'], 0, 8) === 'pubadmin'):
                if ($ini['pubadmin']) {
                    if ((!in_array($match['name'], ['pubadminshowlogin', 'pubadminlogin']))) {
                        $linuser = store::getPubAdminSession()->pk;
                        if (!$linuser) {
                            $redirected = true;
                            header('Location: ' . $router->generate('pubadminshowlogin'));
                        }
                        \mkw\store::getBlameableListener()->setUserValue(\mkw\store::getEm()->getRepository('Entities\Dolgozo')->find($linuser));
                    }
                } else {
                    $redirected = true;
                    header('HTTP/1.1 404 Not found');
                    callTheController('mainController#show404', []);
                }
                break;
            default:
                if ($ini['main']) {
                    store::setMainMode();

                    if (!\mkw\store::getMainSession()->orszag) {
                        $mc = new \Controllers\mainController(null);
                        $orszag = \mkw\store::getParameter(\mkw\consts::Orszag);
                        if ($orszag) {
                            $mc->setOrszag($orszag);
                        }
                    }

                    $__maintranslate->addTranslation(
                        [
                            'adapter' => 'array',
                            'content' => 'locales/main/en.php',
                            'locale' => 'en_us'
                        ]
                    );

                    if (\mkw\store::isMPTNGY()) {
                        $params = new mkwhelpers\ParameterHandler($match);
                        if ($params->getStringRequestParam('lang')) {
                            if ($params->getStringRequestParam('lang') === 'en') {
                                store::setMainLocale('en_us');
                            } elseif ($params->getStringRequestParam('lang') === 'hu') {
                                store::setMainLocale('hu_hu');
                            }
                        }
                    }

                    $mainlocale = store::getLocale();
                    if ($mainlocale) {
                        $__maintranslate->setLocale($mainlocale);
                        if (\mkw\store::isMultilang() && \mkw\store::isMugenrace2021()) {
                            \mkw\store::getTranslationListener()->setTranslatableLocale($mainlocale);
                        }
                    }

                    if (!$mainsess->referrer) {
                        if (array_key_exists('HTTP_REFERER', $_SERVER)) {
                            $mainsess->referrer = substr($_SERVER['HTTP_REFERER'], 0, 255);
                        }
                    }
                    $pc = new \Controllers\partnerController(null);
                    if ($pc->checkloggedin()) {
                        $prevuri = $_SERVER['REQUEST_URI'];
                        if (!$prevuri) {
                            $prevuri = '/';
                        }
                        if ($pc->autoLogout()) {
                            $redirected = true;
                            header('Location: ' . $prevuri);
                        } else {
                            $pc->setUtolsoKlikk();
                        }
                    } elseif (store::mustLogin() && !in_array($match['name'], [
                            'showlogin',
                            'dologin',
                            'showfanta',
                            'dofanta',
                            'fcmotoexport',
                            'mugenraceexport',
                            'superzonehuexport',
                            'pubregistration',
                            'savepubregistration',
                            'pubregistrationthx',
                            'createpassreminder',
                            'showpassreminder',
                            'savepassreminder',
                            'szamlaprint',
                            'szamlapdf',
                            'a2aprocesscmd',
                            'kaposimotoexport',
                            'mptngygetszerepkorlist',
                            'mptngysaveregistration',
                            'partnercheckemail'
                        ])) {
                        $mainsess->redirafterlogin = $_SERVER['REQUEST_URI'];
                        $redirected = true;
                        header('Location: ' . $router->generate('showlogin'));
                    }
                } else {
                    $redirected = true;
                    header('HTTP/1.1 404 Not found');
                    callTheController('mainController#show404', []);
                }
                break;
        }
    }

    if (!$redirected) {
        try {
            if (!callTheController($match['target'], $match)) {
                header('HTTP/1.1 404 Not found');
                callTheController('mainController#show404', []);
            }
        } catch (\Doctrine\ORM\Query\QueryException $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }
}