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
		$instance=new $classname(new \mkwhelpers\ParameterHandler($params));
		$instance->$methodname();
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
$router->map('GET','/admin/raktar/jsonlist','raktarController#jsonlist','raktarjsonlist');
$router->map('POST','/admin/raktar/save','raktarController#save','raktarsave');
$router->map('GET','/admin/termekcimkekat/jsonlist','termekcimkekatController#jsonlist','termekcimkekatjsonlist');
$router->map('POST','/admin/termekcimkekat/save','termekcimkekatController#save','termekcimkekatsave');
$router->map('GET','/admin/termekvaltozatadattipus/jsonlist','termekvaltozatadattipusController#jsonlist','termekvaltozatadattipusjsonlist');
$router->map('POST','/admin/termekvaltozatadattipus/save','termekvaltozatadattipusController#save','termekvaltozatadattipussave');
$router->map('GET','/admin/valutanem/jsonlist','valutanemController#jsonlist','valutanemjsonlist');
$router->map('GET','/admin/valutanem/htmllist','valutanemController#htmllist','valutanemhtmllist');
$router->map('POST','/admin/valutanem/save','valutanemController#save','valutanemsave');
$router->map('GET','/admin/vtsz/jsonlist','vtszController#jsonlist','vtszjsonlist');
$router->map('GET','/admin/vtsz/htmllist','vtszController#htmllist','vtszhtmllist');
$router->map('POST','/admin/vtsz/save','vtszController#save','vtszsave');

$router->map('GET','/admin/termek/viewlist','termekController#viewlist','termekviewlist');
$router->map('GET','/admin/termek/getlistbody','termekController#getlistbody','termekgetlistbody');
$router->map('GET','/admin/termek/getkarb','termekController#getkarb','termekgetkarb');
$router->map('GET','/admin/termek/viewkarb','termekController#viewkarb','termekviewkarb');
$router->map('GET','/admin/termek/getnetto','termekController#getnetto','termekgetnetto');
$router->map('GET','/admin/termek/getbrutto','termekController#getbrutto','termekgetbrutto');
$router->map('POST','/admin/termek/save','termekController#save','termeksave');
$router->map('POST','/admin/termek/setflag','termekController#setflag','termeksetflag');

$router->map('GET','/admin/emailtemplate/viewlist','emailtemplateController#viewlist','emailtemplateviewlist');
$router->map('GET','/admin/emailtemplate/getlistbody','emailtemplateController#getlistbody','emailtemplategetlistbody');
$router->map('GET','/admin/emailtemplate/getkarb','emailtemplateController#getkarb','emailtemplategetkarb');
$router->map('GET','/admin/emailtemplate/viewkarb','emailtemplateController#viewkarb','emailtemplateviewkarb');
$router->map('POST','/admin/emailtemplate/save','emailtemplateController#save','emailtemplatesave');

$router->map('GET','/admin/dolgozo/viewlist','dolgozoController#viewlist','dolgozoviewlist');
$router->map('GET','/admin/dolgozo/getlistbody','dolgozoController#getlistbody','dolgozogetlistbody');
$router->map('GET','/admin/dolgozo/getkarb','dolgozoController#getkarb','dolgozogetkarb');
$router->map('GET','/admin/dolgozo/viewkarb','dolgozoController#viewkarb','dolgozoviewkarb');
$router->map('POST','/admin/dolgozo/save','dolgozoController#save','dolgozosave');

$router->map('GET','/admin/esemeny/viewlist','esemenyController#viewlist','esemenyviewlist');
$router->map('GET','/admin/esemeny/getlistbody','esemenyController#getlistbody','esemenygetlistbody');
$router->map('GET','/admin/esemeny/getkarb','esemenyController#getkarb','esemenygetkarb');
$router->map('GET','/admin/esemeny/viewkarb','esemenyController#viewkarb','esemenyviewkarb');
$router->map('POST','/admin/esemeny/save','esemenyController#save','esemenysave');

$router->map('GET','/admin/hir/viewlist','hirController#viewlist','hirviewlist');
$router->map('GET','/admin/hir/getlistbody','hirController#getlistbody','hirgetlistbody');
$router->map('GET','/admin/hir/getkarb','hirController#getkarb','hirgetkarb');
$router->map('GET','/admin/hir/viewkarb','hirController#viewkarb','hirviewkarb');
$router->map('GET','/admin/hir/gethirlist','hirController#gethirlist','hirgethirlist');
$router->map('GET','/admin/hir/getfeedhirlist','hirController#getfeedhirlist','hirgetfeedhirlist');
$router->map('POST','/admin/hir/save','hirController#save','hirsave');
$router->map('POST','/admin/hir/setlathato','hirController#setlathato','hirsetlathato');

$router->map('GET','/admin/jelenletiiv/viewlist','jelenletiivController#viewlist','jelenletiivviewlist');
$router->map('GET','/admin/jelenletiiv/getlistbody','jelenletiivController#getlistbody','jelenletiivgetlistbody');
$router->map('GET','/admin/jelenletiiv/getkarb','jelenletiivController#getkarb','jelenletiivgetkarb');
$router->map('GET','/admin/jelenletiiv/viewkarb','jelenletiivController#viewkarb','jelenletiivviewkarb');
$router->map('POST','/admin/jelenletiiv/save','jelenletiivController#save','jelenletiivsave');

$router->map('GET','/admin/keresoszolog/viewlist','keresoszologController#viewlist','keresoszologviewlist');
$router->map('GET','/admin/keresoszolog/getlistbody','keresoszologController#getlistbody','keresoszologgetlistbody');
$router->map('GET','/admin/keresoszolog/getkarb','keresoszologController#getkarb','keresoszologgetkarb');
$router->map('GET','/admin/keresoszolog/viewkarb','keresoszologController#viewkarb','keresoszologviewkarb');
$router->map('POST','/admin/keresoszolog/save','keresoszologController#save','keresoszologsave');

$router->map('GET','/admin/kontakt/getemptyrow','kontaktController#getemptyrow','kontaktgetemptyrow');

$router->map('GET','/admin/kontaktcimke/viewlist','kontaktcimkeController#viewlist','kontaktcimkeviewlist');
$router->map('GET','/admin/kontaktcimke/getlistbody','kontaktcimkeController#getlistbody','kontaktcimkegetlistbody');
$router->map('GET','/admin/kontaktcimke/getkarb','kontaktcimkeController#getkarb','kontaktcimkegetkarb');
$router->map('GET','/admin/kontaktcimke/viewkarb','kontaktcimkeController#viewkarb','kontaktcimkeviewkarb');
$router->map('POST','/admin/kontaktcimke/save','kontaktcimkeController#save','kontaktcimkesave');

$router->map('GET','/admin/korhinta/viewlist','korhintaController#viewlist','korhintaviewlist');
$router->map('GET','/admin/korhinta/getlistbody','korhintaController#getlistbody','korhintagetlistbody');
$router->map('GET','/admin/korhinta/getkarb','korhintaController#getkarb','korhintagetkarb');
$router->map('GET','/admin/korhinta/viewkarb','korhintaController#viewkarb','korhintaviewkarb');
$router->map('POST','/admin/korhinta/save','korhintaController#save','korhintasave');

$router->map('GET','/admin/partner/viewlist','partnerController#viewlist','partnerviewlist');
$router->map('GET','/admin/partner/getlistbody','partnerController#getlistbody','partnergetlistbody');
$router->map('GET','/admin/partner/getkarb','partnerController#getkarb','partnergetkarb');
$router->map('GET','/admin/partner/viewkarb','partnerController#viewkarb','partnerviewkarb');
$router->map('POST','/admin/partner/save','partnerController#save','partnersave');
$router->map('POST','/admin/partner/regisztral','partnerController#regisztral','partnerregisztral');
$router->map('POST','/admin/partner/checkemail','partnerController#checkemail','partnercheckemail');

$router->map('GET','/admin/termekfa/jsonlist','termekfaController#jsonlist','termekfajsonlist');

$match=$router->match();

if (!callTheController($match['target'], $match)) {
	echo 'NINCS ROUTE';
}