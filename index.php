<?php
use mkw\store;

date_default_timezone_set('Europe/Budapest');

require_once('bootstrap.php');

$__maintranslate = new Zend_Translate(
		array(
	'adapter' => 'array',
	'content' => 'locales/main/hu.php',
	'locale' => 'hu_hu'
		)
);

$__admintranslate = new Zend_Translate(
    array(
        'adapter' => 'array',
        'content' => 'locales/admin/hu.php',
        'locale' => 'hu_hu'
    )
);

function t($msgid) {
    global $__maintranslate;
    $x = $__maintranslate->_($msgid);
    if (\mkw\store::getSetupValue('translate') && $x === $msgid) {
        store::writetranslation($msgid, 'transmain' . $__maintranslate->getLocale() . '.txt');
    }
    return $x;
}

function at($msgid) {
	global $__admintranslate;
	$x = $__admintranslate->_($msgid);
	if (\mkw\store::getSetupValue('translate') && $x === $msgid) {
	    store::writetranslation($msgid, 'transadmin' . $__admintranslate->getLocale() . '.txt');
    }
	return $x;
}

function haveJog($jog) {
    return \mkw\store::haveJog($jog);
}

function bizformat($mit, $mire = false) {
    if ($mire === false) {
        $mire = 2;
    }
    return number_format($mit, $mire, ',', ' ');
}

// TODO find an appropriate place
function callTheController($target, $params) {
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
	return false;
}

function exc_handler($e) {
    if (is_a($e, '\Doctrine\ORM\Query\QueryException')) {
        error_log($e->getMessage());
    }
}
//set_exception_handler('exc_handler');

if ($ini['developer']) {
    if (in_array(\mkw\store::getExtension($_SERVER['REQUEST_URI']), array('jpg', 'gif', 'png', 'jpeg')))  {
        die();
    }
}

$mainsess = store::getMainSession();

if ($ini['mail.smtp'] == 1) {
    $mailparams = array(
        'name' => $ini['mail.name'],
        'port' => $ini['mail.port'],
        'auth' => 'Login',
        'username' => $ini['mail.username'],
        'password' => $ini['mail.password']
    );
    if (array_key_exists('mail.ssl', $ini) && $ini['mail.ssl']) {
        $mailparams['ssl'] = $ini['mail.ssl'];
    }
    $mailtr = new Zend_Mail_Transport_Smtp($ini['mail.host'], $mailparams);
    Zend_Mail::setDefaultTransport($mailtr);
}

try {
    $conn = store::getEm()->getConnection()->connect();
}
catch (\Exception $e) {
    $lee = file_get_contents('lasterroremail.log');
    if (time() - ($lee * 1) > 600) {
        if (!$ini['developer']) {
            file_put_contents('lasterroremail.log', time());
            if ($ini['onerror.email']) {
                $m = store::getMailer();
                $m->setTo($ini['onerror.email']);
                $m->setSubject($ini['onerror.subject']);
                $m->setMessage('Error code: ' . $e->getCode() . ' ' . $e->getMessage());
                $m->send();
            }
        }
        else {
            file_put_contents('lasterroremail.log', time());
            file_put_contents('erroremail.log', array(
                'to' => $ini['onerror.email'],
                'subject' => $ini['onerror.subject'],
                'message' => 'Error code: ' . $e->getCode() . ' ' . $e->getMessage()
            ));
        }
    }
    throw $e;
}

if (store::getSetupValue('rewrite301')) {
    $rw301c = new \Controllers\rewrite301Controller(array());
    $rw301c->rewrite();
}

$router = store::getRouter();
if (file_exists('mainroute.php')) {
    require_once 'mainroute.php';
}
if ($ini['admin'] && file_exists('adminroute.php')) {
    require_once 'adminroute.php';
}

$match = $router->match();
if (store::getParameter(\mkw\consts::Off) && substr($match['name'], 0, 5) !== 'admin') {
    callTheController('mainController#showOff', array());
}
else {
    if ($match) {
        store::setRouteName($match['name']);
        if (substr($match['name'], 0, 5) === 'admin') {
            store::setAdminMode();

            $__admintranslate->addTranslation(
                array(
                    'adapter' => 'array',
                    'content' => 'locales/admin/en.php',
                    'locale' => 'en_us'
                )
            );
            if (store::getAdminLocale()) {
                $__admintranslate->setLocale(store::getAdminLocale());
            }

            if ((!in_array($match['name'], array('adminshowlogin', 'adminlogin', 'adminrlbexport')))) {
                $linuser = store::getAdminSession()->pk;
                if (!$linuser) {
                    Header('Location: ' . $router->generate('adminshowlogin'));
                }
                \mkw\store::getBlameableListener()->setUserValue(\mkw\store::getEm()->getRepository('Entities\Dolgozo')->find($linuser));
            }
        }
        else {
            store::setMainMode();

            $__maintranslate->addTranslation(
                array(
                    'adapter' => 'array',
                    'content' => 'locales/main/en.php',
                    'locale' => 'en_us'
                )
            );
            if (store::getLocale()) {
                $__maintranslate->setLocale(store::getLocale());
            }

            if (!$mainsess->referrer) {
                if (array_key_exists('HTTP_REFERER', $_SERVER)) {
                    $mainsess->referrer = $_SERVER['HTTP_REFERER'];
                }
            }
            $pc = new \Controllers\partnerController(null);
            if ($pc->checkloggedin()) {
                $prevuri = $_SERVER['REQUEST_URI'];
                if (!$prevuri) {
                    $prevuri = '/';
                }
                if ($pc->autoLogout()) {
                    header('Location: ' . $prevuri);
                }
                else {
                    $pc->setUtolsoKlikk();
                }
            }
            elseif (store::mustLogin() && !in_array($match['name'], array('showlogin', 'dologin', 'showfanta', 'dofanta', 'fcmotoexport',
                    'mugenraceexport', 'superzonehuexport'))) {
                $mainsess->redirafterlogin = $_SERVER['REQUEST_URI'];
                header('Location: ' . $router->generate('showlogin'));
            }
        }
    }

    try {
        if (!callTheController($match['target'], $match)) {
            header('HTTP/1.1 404 Not found');
            callTheController('mainController#show404', array());
        }
    } catch (\Doctrine\ORM\Query\QueryException $e) {
        error_log($e->getMessage());
        throw $e;
    }
}