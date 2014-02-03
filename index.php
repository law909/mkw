<?php
use mkw\Store;

date_default_timezone_set('Europe/Budapest');

require_once('bootstrap.php');

$__translate = new Zend_Translate(
		array(
	'adapter' => 'array',
	'content' => 'locales/hu.php',
	'locale' => 'hu'
		)
);

function t($msgid) {
	global $__translate;
	return $__translate->_($msgid);
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

$mainsess = store::getMainSession();

$pc = new \Controllers\partnerController(Store::getGdl());
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

$router = Store::getRouter();
if (file_exists('mainroute.php')) {
	require_once 'mainroute.php';
}

$match = $router->match();

if (!$match) {
	if ($ini['admin'] && file_exists('adminroute.php')) {
		require_once 'adminroute.php';
	}
	$match = $router->match();
    if ($match &&
            (substr($match['name'], 0, 5) === 'admin') &&
            (!in_array($match['name'], array('adminshowlogin', 'adminlogin')))) {
        $linuser = Store::getAdminSession()->pk;
        if (!$linuser) {
            Header('Location: ' . $router->generate('adminshowlogin'));
        }
    }
}
else {
	if (!$mainsess->referrer) {
        if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            $mainsess->referrer = $_SERVER['HTTP_REFERER'];
        }
	}
}

if (!callTheController($match['target'], $match)) {
    header('HTTP/1.1 404 Not found');
	callTheController('mainController#show404', array());
}