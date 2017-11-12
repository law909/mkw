<?php

if (\mkw\store::isMindentkapni()) {
    $router->map('GET', '/ProductDetails', 'termekController#redirectOldUrl', 'termekredirectoldurl');
    $router->map('GET', '/', 'termekfaController#redirectOldUrl', 'termekfaredirectoldurl');
    $router->map('GET', '/Static', 'statlapController#redirectOldUrl', 'statlapredirectoldurl');
    $router->map('GET', '/mindentkapni.rss', 'termekController#redirectOldRSSUrl', 'termekredirectoldrssurl');
    $router->map('GET', '/hirek.rss', 'hirController#redirectOldRSSUrl', 'hirredirectoldrssurl');
    $router->map('GET', '/MiddleTier/ReadImage', 'termekController#redirectRegikepUrl', 'termekredirectregikepurl');
}

if (\mkw\store::isSuperzoneB2B()) {
    $router->map('GET', '/termekm/[:slug]', 'mainController#termekm', 'showtermekm');
    $router->map('GET', '/fanta', 'fantaController#show', 'showfanta');
    $router->map('POST', '/fanta/do', 'fantaController#doit', 'dofanta');
}

if (\mkw\store::isB2B()) {
    $router->map('POST', '/fiok/ment/[adataim|szamlaadatok|szallitasiadatok|jelszo|discounts:subject]', 'b2bpartnerController#saveAccount', 'saveaccount');
    $router->map('GET', '/regisztracio', 'b2bpartnerController#showRegistrationForm', 'showregistration');
    $router->map('POST', '/regisztracio/ment', 'b2bpartnerController#saveRegistration', 'saveregistration');
    $router->map('POST', '/changepartner', 'b2bpartnerController#changePartner', 'changepartner');
}
else {
    if (\mkw\store::isMIJSZ()) {
        $router->map('POST', '/fiok/ment/[adataim||szamlaadatok|oklevelek|jelszo|pune:subject]', 'partnerController#saveAccount', 'saveaccount');
        $router->map('POST', '/partnermijszoklevel/save', 'partnermijszoklevelController#save', 'partnermijszoklevelsave');
        $router->map('GET', '/partnermijszoklevel/getemptyrow', 'partnermijszoklevelController#getmainemptyrow', 'partnermijszoklevelgetmainemptyrow');
        $router->map('POST', '/partnermijszpune/save', 'partnermijszpuneController#save', 'partnermijszpunesave');
        $router->map('GET', '/partnermijszpune/getemptyrow', 'partnermijszpuneController#getmainemptyrow', 'partnermijszpunegetmainemptyrow');
        $router->map('GET', '/regisztracio', 'partnerController#showLoginForm', 'showregistration');
        $router->map('GET', '/regisztracio', 'partnerController#showLoginForm', 'saveregistration');
    }
    else {
        $router->map('POST', '/fiok/ment/[adataim|szamlaadatok|szallitasiadatok|jelszo:subject]', 'partnerController#saveAccount', 'saveaccount');
        $router->map('GET', '/regisztracio', 'partnerController#showLoginForm', 'showregistration');
        $router->map('POST', '/regisztracio/ment', 'partnerController#saveRegistration', 'saveregistration');
    }
}

if (\mkw\store::isDarshan()) {
    $router->map('GET', '/orarend/wp', 'orarendController#exportToWordpress', 'orarendexporttowordpress');
    $router->map('GET', '/orarend/print', 'orarendController#print', 'orarendprint');
}

$router->map('GET', '', 'mainController#view', 'home');
$router->map('GET', '/404', 'mainController#show404', 'show404');
$router->map('GET', '/login', 'partnerController#showLoginForm', 'showlogin');
$router->map('POST', '/login/ment', 'partnerController#doLogin', 'dologin');
$router->map('GET', '/logout', 'partnerController#doLogout', 'dologout');
$router->map('GET', '/fiok', 'partnerController#showAccount', 'showaccount');

$router->map('POST', '/checkemail', 'partnerController#checkemail', 'partnercheckemail');
$router->map('POST', '/getpassreminder', 'partnerController#createPassReminder', 'createpassreminder');
$router->map('GET', '/passreminder/[:id]', 'partnerController#showPassReminder', 'showpassreminder');
$router->map('POST', '/passreminder/ment', 'partnerController#savePassReminder', 'savepassreminder');

$router->map('GET', '/statlap/[:lap]', 'statlapController#show', 'showstatlap');
$router->map('GET', '/statlap/p/[:lap]', 'statlapController#showPopup', 'showstatlappopup');
$router->map('GET', '/hir/[:hir]', 'hirController#show', 'showhir');
$router->map('GET', '/hirek', 'hirController#showHirList', 'showhirlist');
$router->map('GET', '/feed/hir', 'hirController#feed', 'hirfeed');
$router->map('GET', '/feed/termek', 'termekController#feed', 'termekfeed');
$router->map('GET', '/kapcsolat', 'mainController#kapcsolat', 'showkapcsolat');
$router->map('POST', '/kapcsolat/[ment:todo]', 'mainController#kapcsolat', 'savekapcsolat');

$router->map('GET', '/szuro', 'mainController#szuro', 'showszuro');
$router->map('GET', '/termekfa/[:slug]', 'mainController#termekfa', 'showtermekfa');
$router->map('GET', '/termek/[:slug]', 'mainController#termek', 'showtermek');
$router->map('GET', '/marka/[:slug]', 'mainController#marka', 'showmarka');
$router->map('GET', '/valtozatar', 'mainController#valtozatar', 'valtozatar');
$router->map('GET', '/valtozat', 'mainController#valtozat', 'valtozat');
$router->map('GET', '/kereses', 'mainController#kereses', 'kereses');
$router->map('GET', '/markak', 'termekcimkeController#showMarkak', 'markak');
$router->map('GET', '/getmeretszinhez', 'termekController#getMeretSzinhez', 'getmeretszinhez');

$router->map('POST', '/kosar/add', 'kosarController#add', 'kosaradd');
$router->map('POST', '/kosar/multiadd', 'kosarController#multiAdd', 'kosarmultiadd');
$router->map('POST|GET', '/kosar/edit', 'kosarController#edit', 'kosaredit');
$router->map('POST|GET', '/kosar/del', 'kosarController#del', 'kosardel');
$router->map('GET', '/kosar/get', 'kosarController#get', 'kosarget');
$router->map('GET', '/kosar/gethash', 'kosarController#getHash', 'kosargethash');
$router->map('GET', '/checkout', 'checkoutController#getCheckout', 'showcheckout');
$router->map('GET', '/checkout/pay', 'checkoutController#showCheckoutFizetes', 'showcheckoutfizetes');
$router->map('POST', '/checkout/pay/ment', 'checkoutController#doCheckoutFizetes', 'docheckoutfizetes');
$router->map('POST', '/checkout/newfizmod/ment', 'checkoutController#saveCheckoutFizmod', 'savecheckoutfizmod');
$router->map('GET', '/checkout/getfizmodlist', 'checkoutController#getFizmodList', 'checkoutgetfizmod');
$router->map('GET', '/checkout/gettetellist', 'checkoutController#getTetelList', 'checkoutgettetellist');
$router->map('POST', '/checkout/ment', 'checkoutController#save', 'checkoutment');
$router->map('GET', '/checkout/koszonjuk', 'checkoutController#thanks', 'checkoutkoszonjuk');
$router->map('GET', '/checkout/getfoxpostcsoportlist', 'csomagterminalController#getCsoportok', 'checkoutgetfoxpostcsoportlist');
$router->map('GET', '/checkout/getfoxpostterminallist', 'csomagterminalController#getTerminalok', 'checkoutgetfoxpostterminallist');
$router->map('GET', '/checkout/getcsomagterminalid', 'csomagterminalController#getTerminalId', 'checkoutgetcsomagterminalid');

$router->map('GET', '/irszam', 'irszamController#typeaheadList', 'irszamtypeahead');
$router->map('GET', '/varos', 'irszamController#varosTypeaheadList', 'varostypeahead');

$router->map('POST', '/termekertesito/save', 'termekertesitoController#save', 'termekertesitosave');

$router->map('POST', '/setorszag', 'mainController#setOrszag', 'setorszag');

$router->map('GET', '/export/grando', 'exportController#GrandoExport', 'grandoexport');
$router->map('GET', '/export/shophunter', 'exportController#ShopHunterExport', 'shophunterexport');
$router->map('GET', '/export/arfurkesz', 'exportController#ArfurkeszExport', 'arfurkeszexport');
$router->map('GET', '/export/armutato', 'exportController#ArmutatoExport', 'armutatoexport');
$router->map('GET', '/export/olcso', 'exportController#OlcsoExport', 'olcsoexport');
$router->map('GET', '/export/argep', 'exportController#ArgepExport', 'argepexport');
$router->map('GET', '/export/arukereso', 'exportController#ArukeresoExport', 'arukeresoexport');
$router->map('GET', '/export/olcsobbat', 'exportController#OlcsobbatExport', 'olcsobbatexport');
//$router->map('GET', '/export/fcmoto', 'exportController#FCMotoExport', 'fcmotoexport');
$router->map('GET', '/export/mugenrace', 'exportController#MugenraceExport', 'mugenraceexport');
$router->map('GET', '/export/superzonehu', 'exportController#SuperzonehuExport', 'superzonehuexport');
$router->map('GET', '/sitemap.xml', 'sitemapController#toBot', 'sitemap');

$router->map('GET', '/t/emag/printvat', 'emagController#printVat', 'printvat');
$router->map('GET', '/t/emag/printcat', 'emagController#printCategories', 'printcat');