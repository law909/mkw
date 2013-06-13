<?php
use mkw\Store;
use \Controllers\CentralController, \mkw\generalDataLoader;
date_default_timezone_set('Europe/Budapest');

require_once('bootstrap.php');

$__translate = new Zend_Translate(
    array(
        'adapter' => 'array',
        'content' => 'locales/hu.php',
        'locale'  => 'hu'
    )
);

function write_log($text) {
 $handle=fopen("log.txt","a");
 $log="";
 $separator=" ## ";
 $log.=date('Y.m.d. H:i:s').$separator;
 $log.=$text;
 $log.="\n";
 fwrite($handle,$log);
 fclose($handle);
}

function t($msgid) {
	global $__translate;
	return $__translate->_($msgid);
}

// TODO find an appropriate place
function callTheController($target,$params) {
	$methodname='';
	$a=explode('#',$target);
	$classname=$a[0];
	if (count($a)>1) {
		$methodname=$a[1];
	}
	if (strpos($classname, '\\')===false) {
		$classname='\Controllers\\'.$classname;
	}
	$path=explode('/',str_replace('\\', '/', $classname.'.php'));
	$inc=ltrim(implode('/',$path),'/');
	if (file_exists($inc)&&$methodname) {
		require_once $inc;
		$instance=new $classname;
		$instance->$methodname(new \mkwhelpers\ParameterHandler($params));
		return true;
	}
	return false;
}

store::getMainSession();

$pc=new \Controllers\partnerController(Store::getGdl());
if ($pc->checkloggedin()) {
	$prevuri=$_SERVER['REQUEST_URI'];
	if (!$prevuri) {
		$prevuri='/';
	}
	if ($pc->autoLogout()) {
		header('Location: '.$prevuri);
	}
	else {
		$pc->setUtolsoKlikk();
	}
}

$router=Store::getRouter();

$router->map('GET','/admin','adminController#view','adminview');
$router->map('GET','/admin/view','adminController#view','adminview2');
$router->map('GET','/admin/egyebtorzs/view','egyebtorzsController#view','egyebtorzsview');
$router->map('GET','/admin/afa/jsonlist','afaController#jsonlist','afajsonlist');
$router->map('GET','/admin/afa/htmllist','afaController#htmllist','afahtmllist');
$router->map('POST','/admin/afa/save','afaController#save','afasave');

/**
$router->map('POST','/post/like/[:id]','PostController#like','postlike');
$router->map('POST','/post/delete/[:id]','PostController#delete','postdelete');
$router->map('POST','/post/report/[:id]','PostController#report','postreport');
$router->map('GET','/post/getnewui','PostController#getNewUI','postgetnewui');
$router->map('POST','/post/add','PostController#add','postadd');
$router->map('GET','/post/get/[i:from]','PostController#get','postget');
$router->map('POST','/comment/like/[:id]','CommentController#like','commentlike');
$router->map('POST','/comment/delete/[:id]','CommentController#delete','commentdelete');
$router->map('POST','/comment/edit','CommentController#edit','commentedit');
$router->map('POST','/comment/report/[:id]','CommentController#report','commentreport');
$router->map('GET','/comment/getnewui/[:postid]','CommentController#getNewUI','commentgetnewui');
$router->map('GET','/comment/geteditui/[:id]/[:postid]','CommentController#getEditUI','commentgeteditui');
$router->map('POST','/comment/add','CommentController#add','commentadd');
$router->map('GET','/comment/get/[:postid]','CommentController#get','commentget');
*/

$match=$router->match();
fb($match);

if (!callTheController($match['target'], $match)) {
}