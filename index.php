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
$router->map('GET','/admin/arfolyam/jsonlist','arfolyamController#jsonlist','arfolyamjsonlist');
$router->map('GET','/admin/arfolyam/htmllist','arfolyamController#htmllist','arfolyamhtmllist');
$router->map('POST','/admin/arfolyam/save','arfolyamController#save','arfolyamsave');
$router->map('GET','/admin/bankszamla/jsonlist','bankszamlaController#jsonlist','bankszamlajsonlist');
$router->map('GET','/admin/bankszamla/htmllist','bankszamlaController#htmllist','bankszamlahtmllist');
$router->map('POST','/admin/bankszamla/save','bankszamlaController#save','bankszamlasave');
$router->map('GET','/admin/felhasznalo/jsonlist','felhasznaloController#jsonlist','felhasznalojsonlist');
$router->map('POST','/admin/felhasznalo/save','felhasznaloController#save','felhasznalosave');
$router->map('GET','/admin/fizmod/jsonlist','fizmodController#jsonlist','fizmodjsonlist');
$router->map('GET','/admin/fizmod/htmllist','fizmodController#htmllist','fizmodhtmllist');
$router->map('POST','/admin/fizmod/save','fizmodController#save','fizmodsave');
$router->map('GET','/admin/jelenlettipus/jsonlist','jelenlettipusController#jsonlist','jelenlettipusjsonlist');
$router->map('POST','/admin/jelenlettipus/save','jelenlettipusController#save','jelenlettipussave');
$router->map('GET','/admin/kapcsolatfelveteltema/jsonlist','kapcsolatfelveteltemaController#jsonlist','kapcsolatfelveteltemajsonlist');
$router->map('POST','/admin/kapcsolatfelveteltema/save','kapcsolatfelveteltemaController#save','kapcsolatfelveteltemasave');
$router->map('GET','/admin/kontaktcimkekat/jsonlist','kontaktcimkekatController#jsonlist','kontaktcimkekatjsonlist');
$router->map('POST','/admin/kontaktcimkekat/save','kontaktcimkekatController#save','kontaktcimkekatsave');
$router->map('GET','/admin/munkakor/jsonlist','munkakorController#jsonlist','munkakorjsonlist');
$router->map('POST','/admin/munkakor/save','munkakorController#save','munkakorsave');
$router->map('GET','/admin/nullaslistatetel/jsonlist','nullaslistatetelController#jsonlist','nullaslistateteljsonlist');
$router->map('POST','/admin/nullaslistatetel/save','nullaslistatetelController#save','nullaslistatetelsave');
$router->map('GET','/admin/partnercimkekat/jsonlist','partnercimkekatController#jsonlist','partnercimkekatjsonlist');
$router->map('POST','/admin/partnercimkekat/save','partnercimkekatController#save','partnercimkekatsave');
$router->map('GET','/admin/partnercsoport/jsonlist','partnercsoportController#jsonlist','partnercsoportjsonlist');
$router->map('GET','/admin/partnercsoport/htmllist','partnercsoportController#htmllist','partnercsoporthtmllist');
$router->map('POST','/admin/partnercsoport/save','partnercsoportController#save','partnercsoportsave');
$router->map('GET','/admin/raktar/jsonlist','raktarController#jsonlist','raktarjsonlist');
$router->map('POST','/admin/raktar/save','raktarController#save','raktarsave');
$router->map('GET','/admin/targyieszkozcsoport/jsonlist','targyieszkozcsoportController#jsonlist','targyieszkozcsoportjsonlist');
$router->map('GET','/admin/targyieszkozcsoport/htmllist','targyieszkozcsoportController#htmllist','targyieszkozcsoporthtmllist');
$router->map('POST','/admin/targyieszkozcsoport/save','targyieszkozcsoportController#save','targyieszkozcsoportsave');
$router->map('GET','/admin/termekcimkekat/jsonlist','termekcimkekatController#jsonlist','termekcimkekatjsonlist');
$router->map('POST','/admin/termekcimkekat/save','termekcimkekatController#save','termekcimkekatsave');
$router->map('GET','/admin/termekcsoport/jsonlist','termekcsoportController#jsonlist','termekcsoportjsonlist');
$router->map('GET','/admin/termekcsoport/htmllist','termekcsoportController#htmllist','termekcsoporthtmllist');
$router->map('POST','/admin/termekcsoport/save','termekcsoportController#save','termekcsoportsave');
$router->map('GET','/admin/termekvaltozatadattipus/jsonlist','termekvaltozatadattipusController#jsonlist','termekvaltozatadattipusjsonlist');
$router->map('POST','/admin/termekvaltozatadattipus/save','termekvaltozatadattipusController#save','termekvaltozatadattipussave');
$router->map('GET','/admin/valutanem/jsonlist','valutanemController#jsonlist','valutanemjsonlist');
$router->map('GET','/admin/valutanem/htmllist','valutanemController#htmllist','valutanemhtmllist');
$router->map('POST','/admin/valutanem/save','valutanemController#save','valutanemsave');
$router->map('GET','/admin/vtsz/jsonlist','vtszController#jsonlist','vtszjsonlist');
$router->map('GET','/admin/vtsz/htmllist','vtszController#htmllist','vtszhtmllist');
$router->map('POST','/admin/vtsz/save','vtszController#save','vtszsave');

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