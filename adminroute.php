<?php

$router->map('GET', '/admin', 'adminController#view', 'adminview');
$router->map('GET', '/admin/view', 'adminController#view', 'adminview2');
$router->map('GET', '/admin/egyebtorzs/view', 'egyebtorzsController#view', 'adminegyebtorzsview');
$router->map('GET', '/admin/afa/jsonlist', 'afaController#jsonlist', 'adminafajsonlist');
$router->map('GET', '/admin/afa/htmllist', 'afaController#htmllist', 'adminafahtmllist');
$router->map('POST', '/admin/afa/save', 'afaController#save', 'adminafasave');
$router->map('GET', '/admin/arfolyam/jsonlist', 'arfolyamController#jsonlist', 'adminarfolyamjsonlist');
$router->map('GET', '/admin/arfolyam/htmllist', 'arfolyamController#htmllist', 'adminarfolyamhtmllist');
$router->map('POST', '/admin/arfolyam/save', 'arfolyamController#save', 'adminarfolyamsave');
$router->map('GET', '/admin/arfolyam/getarfolyam', 'arfolyamController#getarfolyam', 'admingetarfolyam');
$router->map('POST', '/admin/arfolyam/download', 'arfolyamController#downloadArfolyam', 'admindownloadarfolyam');
$router->map('GET', '/admin/bankszamla/jsonlist', 'bankszamlaController#jsonlist', 'adminbankszamlajsonlist');
$router->map('GET', '/admin/bankszamla/htmllist', 'bankszamlaController#htmllist', 'adminbankszamlahtmllist');
$router->map('POST', '/admin/bankszamla/save', 'bankszamlaController#save', 'adminbankszamlasave');
$router->map('GET', '/admin/penztar/jsonlist', 'penztarController#jsonlist', 'adminpenztarjsonlist');
$router->map('GET', '/admin/penztar/htmllist', 'penztarController#htmllist', 'adminpenztarhtmllist');
$router->map('POST', '/admin/penztar/save', 'penztarController#save', 'adminpenztarsave');
$router->map('GET', '/admin/csk/jsonlist', 'cskController#jsonlist', 'admincskjsonlist');
$router->map('GET', '/admin/csk/htmllist', 'cskController#htmllist', 'admincskhtmllist');
$router->map('POST', '/admin/csk/save', 'cskController#save', 'admincsksave');
$router->map('GET', '/admin/felhasznalo/jsonlist', 'felhasznaloController#jsonlist', 'adminfelhasznalojsonlist');
$router->map('POST', '/admin/felhasznalo/save', 'felhasznaloController#save', 'adminfelhasznalosave');
$router->map('GET', '/admin/jelenlettipus/jsonlist', 'jelenlettipusController#jsonlist', 'adminjelenlettipusjsonlist');
$router->map('POST', '/admin/jelenlettipus/save', 'jelenlettipusController#save', 'adminjelenlettipussave');
$router->map('GET', '/admin/kapcsolatfelveteltema/jsonlist', 'kapcsolatfelveteltemaController#jsonlist', 'adminkapcsolatfelveteltemajsonlist');
$router->map('POST', '/admin/kapcsolatfelveteltema/save', 'kapcsolatfelveteltemaController#save', 'adminkapcsolatfelveteltemasave');
$router->map('GET', '/admin/munkakor/jsonlist', 'munkakorController#jsonlist', 'adminmunkakorjsonlist');
$router->map('POST', '/admin/munkakor/save', 'munkakorController#save', 'adminmunkakorsave');
$router->map('GET', '/admin/partnercimkekat/jsonlist', 'partnercimkekatController#jsonlist', 'adminpartnercimkekatjsonlist');
$router->map('POST', '/admin/partnercimkekat/save', 'partnercimkekatController#save', 'adminpartnercimkekatsave');
$router->map('GET', '/admin/raktar/jsonlist', 'raktarController#jsonlist', 'adminraktarjsonlist');
$router->map('POST', '/admin/raktar/save', 'raktarController#save', 'adminraktarsave');
$router->map('GET', '/admin/termekcimkekat/jsonlist', 'termekcimkekatController#jsonlist', 'admintermekcimkekatjsonlist');
$router->map('POST', '/admin/termekcimkekat/save', 'termekcimkekatController#save', 'admintermekcimkekatsave');
$router->map('GET', '/admin/termekvaltozatadattipus/jsonlist', 'termekvaltozatadattipusController#jsonlist', 'admintermekvaltozatadattipusjsonlist');
$router->map('POST', '/admin/termekvaltozatadattipus/save', 'termekvaltozatadattipusController#save', 'admintermekvaltozatadattipussave');
$router->map('GET', '/admin/valutanem/jsonlist', 'valutanemController#jsonlist', 'adminvalutanemjsonlist');
$router->map('GET', '/admin/valutanem/htmllist', 'valutanemController#htmllist', 'adminvalutanemhtmllist');
$router->map('POST', '/admin/valutanem/save', 'valutanemController#save', 'adminvalutanemsave');
$router->map('GET', '/admin/vtsz/jsonlist', 'vtszController#jsonlist', 'adminvtszjsonlist');
$router->map('GET', '/admin/vtsz/htmllist', 'vtszController#htmllist', 'adminvtszhtmllist');
$router->map('POST', '/admin/vtsz/save', 'vtszController#save', 'adminvtszsave');
$router->map('GET', '/admin/irszam/jsonlist', 'irszamController#jsonlist', 'adminirszamjsonlist');
$router->map('GET', '/admin/irszam/htmllist', 'irszamController#htmllist', 'adminirszamhtmllist');
$router->map('POST', '/admin/irszam/save', 'irszamController#save', 'adminirszamsave');
$router->map('GET', '/admin/irszam', 'irszamController#typeaheadList', 'adminirszamtypeahead');
$router->map('GET', '/admin/varos', 'irszamController#varosTypeaheadList', 'adminvarostypeahead');
$router->map('GET', '/admin/rw301/jsonlist', 'rewrite301Controller#jsonlist', 'adminrewrite301jsonlist');
$router->map('POST', '/admin/rw301/save', 'rewrite301Controller#save', 'adminrewrite301save');
$router->map('GET', '/admin/termekcsoport/jsonlist', 'termekcsoportController#jsonlist', 'admintermekcsoportjsonlist');
$router->map('GET', '/admin/termekcsoport/htmllist', 'termekcsoportController#htmllist', 'admintermekcsoporthtmllist');
$router->map('POST', '/admin/termekcsoport/save', 'termekcsoportController#save', 'admintermekcsoportsave');
$router->map('GET', '/admin/partnertipus/jsonlist', 'partnertipusController#jsonlist', 'adminpartnertipusjsonlist');
$router->map('GET', '/admin/partnertipus/htmllist', 'partnertipusController#htmllist', 'adminpartnertipushtmllist');
$router->map('POST', '/admin/partnertipus/save', 'partnertipusController#save', 'adminpartnertipussave');
$router->map('GET', '/admin/orszag/jsonlist', 'orszagController#jsonlist', 'adminorszagjsonlist');
$router->map('GET', '/admin/orszag/htmllist', 'orszagController#htmllist', 'adminorszaghtmllist');
$router->map('POST', '/admin/orszag/save', 'orszagController#save', 'adminorszagsave');
$router->map('GET', '/admin/mijszoklevelkibocsajto/jsonlist', 'mijszoklevelkibocsajtoController#jsonlist', 'adminmijszoklevelkibocsajtojsonlist');
$router->map('GET', '/admin/mijszoklevelkibocsajto/htmllist', 'mijszoklevelkibocsajtoController#htmllist', 'adminmijszoklevelkibocsajtohtmllist');
$router->map('POST', '/admin/mijszoklevelkibocsajto/save', 'mijszoklevelkibocsajtoController#save', 'adminmijszoklevelkibocsajtosave');
$router->map('GET', '/admin/mijszoklevelszint/jsonlist', 'mijszoklevelszintController#jsonlist', 'adminmijszoklevelszintjsonlist');
$router->map('GET', '/admin/mijszoklevelszint/htmllist', 'mijszoklevelszintController#htmllist', 'adminmijszoklevelszinthtmllist');
$router->map('POST', '/admin/mijszoklevelszint/save', 'mijszoklevelszintController#save', 'adminmijszoklevelszintsave');
$router->map('GET', '/admin/szotar/jsonlist', 'szotarController#jsonlist', 'adminszotarjsonlist');
$router->map('GET', '/admin/szotar/htmllist', 'szotarController#htmllist', 'adminszotarhtmllist');
$router->map('POST', '/admin/szotar/save', 'szotarController#save', 'adminszotarsave');
$router->map('GET', '/admin/termekrecepttipus/jsonlist', 'termekrecepttipusController#jsonlist', 'admintermekrecepttipusjsonlist');
$router->map('GET', '/admin/termekrecepttipus/htmllist', 'termekrecepttipusController#htmllist', 'admintermekrecepttipushtmllist');
$router->map('POST', '/admin/termekrecepttipus/save', 'termekrecepttipusController#save', 'admintermekrecepttipussave');

if (\mkw\store::isBankpenztar()) {
    $router->map('GET', '/admin/jogcim/jsonlist', 'jogcimController#jsonlist', 'adminjogcimjsonlist');
    $router->map('GET', '/admin/jogcim/htmllist', 'jogcimController#htmllist', 'adminjogcimhtmllist');
    $router->map('POST', '/admin/jogcim/save', 'jogcimController#save', 'adminjogcimsave');

    $router->map('GET', '/admin/bankbizonylatfej/viewlist', 'bankbizonylatfejController#viewlist', 'adminbankbizonylatfejviewlist');
    $router->map('GET', '/admin/bankbizonylatfej/getlistbody', 'bankbizonylatfejController#getlistbody', 'adminbankbizonylatfejgetlistbody');
    $router->map('GET', '/admin/bankbizonylatfej/getkarb', 'bankbizonylatfejController#getkarb', 'adminbankbizonylatfejgetkarb');
    $router->map('GET', '/admin/bankbizonylatfej/viewkarb', 'bankbizonylatfejController#viewkarb', 'adminbankbizonylatfejviewkarb');
    $router->map('POST', '/admin/bankbizonylatfej/save', 'bankbizonylatfejController#save', 'adminbankbizonylatfejsave');
    $router->map('GET', '/admin/bankbizonylatfej/print', 'bankbizonylatfejController#doPrint', 'adminbankbizonylatfejprint');
    $router->map('POST', '/admin/bankbizonylatfej/ront', 'bankbizonylatfejController#ront', 'adminbankbizonylatfejront');

    $router->map('GET', '/admin/bankbizonylattetel/getemptyrow', 'bankbizonylattetelController#getemptyrow', 'adminbankbizonylattetelgetemptyrow');
    $router->map('POST', '/admin/bankbizonylattetel/save', 'bankbizonylattetelController#save', 'adminbankbizonylattetelsave');

    $router->map('GET', '/admin/penztarbizonylatfej/viewlist', 'penztarbizonylatfejController#viewlist', 'adminpenztarbizonylatfejviewlist');
    $router->map('GET', '/admin/penztarbizonylatfej/getlistbody', 'penztarbizonylatfejController#getlistbody', 'adminpenztarbizonylatfejgetlistbody');
    $router->map('GET', '/admin/penztarbizonylatfej/getkarb', 'penztarbizonylatfejController#getkarb', 'adminpenztarbizonylatfejgetkarb');
    $router->map('GET', '/admin/penztarbizonylatfej/viewkarb', 'penztarbizonylatfejController#viewkarb', 'adminpenztarbizonylatfejviewkarb');
    $router->map('POST', '/admin/penztarbizonylatfej/save', 'penztarbizonylatfejController#save', 'adminpenztarbizonylatfejsave');
    $router->map('GET', '/admin/penztarbizonylatfej/print', 'penztarbizonylatfejController#doPrint', 'adminpenztarbizonylatfejprint');
    $router->map('POST', '/admin/penztarbizonylatfej/ront', 'penztarbizonylatfejController#ront', 'adminpenztarbizonylatfejront');

    $router->map('GET', '/admin/penztarbizonylattetel/getemptyrow', 'penztarbizonylattetelController#getemptyrow', 'adminpenztarbizonylattetelgetemptyrow');
    $router->map('POST', '/admin/penztarbizonylattetel/save', 'penztarbizonylattetelController#save', 'adminpenztarbizonylattetelsave');

    $router->map('GET', '/admin/kintlevoseglista/view', 'kintlevoseglistaController#view', 'adminkintlevoseglistaview');
    $router->map('GET', '/admin/kintlevoseglista/get', 'kintlevoseglistaController#createLista', 'adminkintlevoseglistaget');
    $router->map('GET', '/admin/kintlevoseglista/export', 'kintlevoseglistaController#exportLista', 'adminkintlevoseglistaexport');

    $router->map('GET', '/admin/penzbelista/view', 'penzbelistaController#view', 'adminpenzbelistaview');
    $router->map('GET', '/admin/penzbelista/get', 'penzbelistaController#createLista', 'adminpenzbelistaget');

    $router->map('GET', '/admin/jutaleklista/view', 'jutaleklistaController#view', 'adminjutaleklistaview');
    $router->map('GET', '/admin/jutaleklista/get', 'jutaleklistaController#createLista', 'adminjutaleklistaget');
    $router->map('GET', '/admin/jutaleklista/export', 'jutaleklistaController#exportLista', 'adminjutaleklistaexport');
}

$router->map('GET', '/admin/getsmallurl', 'adminController#getSmallUrl', 'admingetsmallurl');
$router->map('GET', '/admin/regeneratekarkod', 'adminController#regeneratekarkod', 'adminregeneratekarkod');
$router->map('GET', '/admin/setuitheme', 'adminController#setUITheme', 'adminsetuitheme');
$router->map('GET', '/admin/setgrideditbutton', 'adminController#setGridEditButton', 'adminsetgrideditbutton');
$router->map('GET', '/admin/seteditstyle', 'adminController#setEditStyle', 'adminseteditstyle');
$router->map('GET', '/admin/setvonalkodfromvaltozat', 'adminController#setVonalkodFromValtozat', 'adminsetvonalkodfromvaltozat');

$router->map('GET', '/admin/setup/view', 'setupController#view', 'adminsetupview');
$router->map('POST', '/admin/setup/save', 'setupController#save', 'adminsetupsave');

$router->map('GET', '/admin/navadatexport/view', 'navadatexportController#view', 'adminnavadatexportview');
$router->map('GET', '/admin/navadatexport/get', 'navadatexportController#createLista', 'adminnavadatexportget');
$router->map('GET', '/admin/navadatexport/check', 'navadatexportController#check', 'adminnavadatexportcheck');

$router->map('GET', '/admin/bizonylattetel/getar', 'bizonylattetelController#getar', 'adminbizonylattetelgetar');
$router->map('GET', '/admin/bizonylattetel/calcar', 'bizonylattetelController#calcarforclient', 'adminbizonylattetelcalcar');
$router->map('GET', '/admin/bizonylattetel/getemptyrow', 'bizonylattetelController#getemptyrow', 'adminbizonylattetelgetemptyrow');
$router->map('GET', '/admin/bizonylattetel/getquickemptyrow', 'bizonylattetelController#getquickemptyrow', 'adminbizonylattetelgetquickemptyrow');
$router->map('POST', '/admin/bizonylattetel/save', 'bizonylattetelController#save', 'adminbizonylattetelsave');
$router->map('GET', '/admin/bizonylattetel/gettermeklist', 'termekController#getBizonylattetelSelectList', 'adminbizonylattetelgettermeklist');
$router->map('GET', '/admin/bizonylattetel/valtozatlist', 'bizonylattetelController#valtozathtmllist', 'adminvaltozatlist');
$router->map('GET', '/admin/bizonylattetel/quickvaltozatlist', 'bizonylattetelController#quickvaltozathtmllist', 'adminquickvaltozatlist');

$router->map('GET', '/admin/bizonylatfej/checkkelt', 'bizonylatfejController#checkKelt', 'adminbizonylatfejcheckkelt');
$router->map('GET', '/admin/bizonylatfej/calcesedekesseg', 'bizonylatfejController#calcesedekesseg', 'adminbizonylatfejcalcesedekesseg');
$router->map('POST', '/admin/bizonylatfej/setstatusz', 'bizonylatfejController#setStatusz', 'adminbizonylatfejsetstatusz');
$router->map('POST', '/admin/bizonylatfej/setnyomtatva', 'bizonylatfejController#setNyomtatva', 'adminbizonylatfejsetnyomtatva');
$router->map('GET', '/admin/bizonylatfej/getpartnerlist', 'partnerController#getBizonylatfejSelectList', 'adminbizonylatfejgetpartnerlist');

$router->map('GET', '/admin/megrendelesfej/viewlist', 'megrendelesfejController#viewlist', 'adminmegrendelesfejviewlist');
$router->map('GET', '/admin/megrendelesfej/getlistbody', 'megrendelesfejController#getlistbody', 'adminmegrendelesfejgetlistbody');
$router->map('GET', '/admin/megrendelesfej/getkarb', 'megrendelesfejController#getkarb', 'adminmegrendelesfejgetkarb');
$router->map('GET', '/admin/megrendelesfej/viewkarb', 'megrendelesfejController#viewkarb', 'adminmegrendelesfejviewkarb');
$router->map('POST', '/admin/megrendelesfej/save', 'megrendelesfejController#save', 'adminmegrendelesfejsave');
$router->map('GET', '/admin/megrendelesfej/getszamlakarb', 'megrendelesfejController#getszamlakarb', 'adminmegrendelesfejgetszamlakarb');
$router->map('GET', '/admin/megrendelesfej/print', 'megrendelesfejController#doPrint', 'adminmegrendelesfejprint');
$router->map('GET', '/admin/megrendelesfej/printelolegbekero', 'megrendelesfejController#doPrintelolegbekero', 'adminmegrendelesfejprintelolegbekero');
$router->map('POST', '/admin/megrendelesfej/sendtofoxpost', 'megrendelesfejController#sendToFoxPost', 'adminmegrendelessendtofoxpost');
$router->map('POST', '/admin/megrendelesfej/ront', 'megrendelesfejController#ront', 'adminmegrendelesfejront');
$router->map('POST', '/admin/megrendelesfej/backorder', 'megrendelesfejController#backOrder', 'adminmegrendelesfejbackorder');
$router->map('POST', '/admin/megrendelesfej/fejexport', 'megrendelesfejController#fejexport', 'adminmegrendelesfejfejexport');
$router->map('POST', '/admin/megrendelesfej/tetelexport', 'megrendelesfejController#tetelexport', 'adminmegrendelesfejtetelexport');

$router->map('GET', '/admin/szallitofej/viewlist', 'szallitofejController#viewlist', 'adminszallitofejviewlist');
$router->map('GET', '/admin/szallitofej/getlistbody', 'szallitofejController#getlistbody', 'adminszallitofejgetlistbody');
$router->map('GET', '/admin/szallitofej/getkarb', 'szallitofejController#getkarb', 'adminszallitofejgetkarb');
$router->map('GET', '/admin/szallitofej/viewkarb', 'szallitofejController#viewkarb', 'adminszallitofejviewkarb');
$router->map('POST', '/admin/szallitofej/save', 'szallitofejController#save', 'adminszallitofejsave');
$router->map('GET', '/admin/szallitofej/print', 'szallitofejController#doPrint', 'adminszallitofejprint');
$router->map('POST', '/admin/szallitofej/ront', 'szallitofejController#ront', 'adminszallitofejront');
$router->map('POST', '/admin/szallitofej/fejexport', 'szallitofejController#fejexport', 'adminszallitofejfejexport');
$router->map('POST', '/admin/szallitofej/tetelexport', 'szallitofejController#tetelexport', 'adminszallitofejtetelexport');

$router->map('GET', '/admin/autokiserofej/viewlist', 'autokiserofejController#viewlist', 'adminautokiserofejviewlist');
$router->map('GET', '/admin/autokiserofej/getlistbody', 'autokiserofejController#getlistbody', 'adminautokiserofejgetlistbody');
$router->map('GET', '/admin/autokiserofej/getkarb', 'autokiserofejController#getkarb', 'adminautokiserofejgetkarb');
$router->map('GET', '/admin/autokiserofej/viewkarb', 'autokiserofejController#viewkarb', 'adminautokiserofejviewkarb');
$router->map('POST', '/admin/autokiserofej/save', 'autokiserofejController#save', 'adminautokiserofejsave');
$router->map('GET', '/admin/autokiserofej/print', 'autokiserofejController#doPrint', 'adminautokiserofejprint');
$router->map('POST', '/admin/autokiserofej/ront', 'autokiserofejController#ront', 'adminautokiserofejront');
$router->map('POST', '/admin/autokiserofej/fejexport', 'autokiserofejController#fejexport', 'adminautokiserofejfejexport');
$router->map('POST', '/admin/autokiserofej/tetelexport', 'autokiserofejController#tetelexport', 'adminautokiserofejtetelexport');

$router->map('GET', '/admin/szamlafej/viewlist', 'szamlafejController#viewlist', 'adminszamlafejviewlist');
$router->map('GET', '/admin/szamlafej/getlistbody', 'szamlafejController#getlistbody', 'adminszamlafejgetlistbody');
$router->map('GET', '/admin/szamlafej/getkarb', 'szamlafejController#getkarb', 'adminszamlafejgetkarb');
$router->map('GET', '/admin/szamlafej/viewkarb', 'szamlafejController#viewkarb', 'adminszamlafejviewkarb');
$router->map('POST', '/admin/szamlafej/save', 'szamlafejController#save', 'adminszamlafejsave');
$router->map('GET', '/admin/szamlafej/print', 'szamlafejController#doPrint', 'adminszamlafejprint');
$router->map('GET', '/admin/szamlafej/storno', 'szamlafejController#storno', 'adminszamlafejstorno');
$router->map('POST', '/admin/szamlafej/fejexport', 'szamlafejController#fejexport', 'adminszamlafejfejexport');
$router->map('POST', '/admin/szamlafej/tetelexport', 'szamlafejController#tetelexport', 'adminszamlafejtetelexport');

$router->map('GET', '/admin/egyebfej/viewlist', 'egyebmozgasfejController#viewlist', 'adminegyebfejviewlist');
$router->map('GET', '/admin/egyebfej/getlistbody', 'egyebmozgasfejController#getlistbody', 'adminegyebfejgetlistbody');
$router->map('GET', '/admin/egyebfej/getkarb', 'egyebmozgasfejController#getkarb', 'adminegyebfejgetkarb');
$router->map('GET', '/admin/egyebfej/viewkarb', 'egyebmozgasfejController#viewkarb', 'adminegyebfejviewkarb');
$router->map('POST', '/admin/egyebfej/save', 'egyebmozgasfejController#save', 'adminegyebfejsave');
$router->map('GET', '/admin/egyebfej/print', 'egyebmozgasfejController#doPrint', 'adminegyebfejprint');
$router->map('POST', '/admin/egyebfej/ront', 'egyebmozgasfejController#ront', 'adminegyebfejront');
$router->map('POST', '/admin/egyebfej/fejexport', 'egyebmozgasfejController#fejexport', 'adminegyebfejfejexport');
$router->map('POST', '/admin/egyebfej/tetelexport', 'egyebmozgasfejController#tetelexport', 'adminegyebfejtetelexport');

$router->map('GET', '/admin/selejtfej/getlistbody', 'selejtfejController#getlistbody', 'adminselejtfejgetlistbody');
$router->map('GET', '/admin/selejtfej/viewlist', 'selejtfejController#viewlist', 'adminselejtfejviewlist');
$router->map('GET', '/admin/selejtfej/getkarb', 'selejtfejController#getkarb', 'adminselejtfejgetkarb');
$router->map('GET', '/admin/selejtfej/viewkarb', 'selejtfejController#viewkarb', 'adminselejtfejviewkarb');
$router->map('POST', '/admin/selejtfej/save', 'selejtfejController#save', 'adminselejtfejsave');
$router->map('GET', '/admin/selejtfej/print', 'selejtfejController#doPrint', 'adminselejtfejprint');
$router->map('POST', '/admin/selejtfej/ront', 'selejtfejController#ront', 'adminselejtfejront');
$router->map('POST', '/admin/selejtfej/fejexport', 'selejtfejController#fejexport', 'adminselejtfejfejexport');
$router->map('POST', '/admin/selejtfej/', 'selejtfejController#', 'adminselejtfej');

$router->map('GET', '/admin/csomagfej/viewlist', 'csomagfejController#viewlist', 'admincsomagfejviewlist');
$router->map('GET', '/admin/csomagfej/getlistbody', 'csomagfejController#getlistbody', 'admincsomagfejgetlistbody');
$router->map('GET', '/admin/csomagfej/getkarb', 'csomagfejController#getkarb', 'admincsomagfejgetkarb');
$router->map('GET', '/admin/csomagfej/viewkarb', 'csomagfejController#viewkarb', 'admincsomagfejviewkarb');
$router->map('POST', '/admin/csomagfej/save', 'csomagfejController#save', 'admincsomagfejsave');
$router->map('GET', '/admin/csomagfej/print', 'csomagfejController#doPrint', 'admincsomagfejprint');
$router->map('POST', '/admin/csomagfej/ront', 'csomagfejController#ront', 'admincsomagfejront');
$router->map('POST', '/admin/csomagfej/fejexport', 'csomagfejController#fejexport', 'admincsomagfejfejexport');
$router->map('POST', '/admin/csomagfej/tetelexport', 'csomagfejController#tetelexport', 'admincsomagfejtetelexport');

$router->map('GET', '/admin/keziszamlafej/viewlist', 'keziszamlafejController#viewlist', 'adminkeziszamlafejviewlist');
$router->map('GET', '/admin/keziszamlafej/getlistbody', 'keziszamlafejController#getlistbody', 'adminkeziszamlafejgetlistbody');
$router->map('GET', '/admin/keziszamlafej/getkarb', 'keziszamlafejController#getkarb', 'adminkeziszamlafejgetkarb');
$router->map('GET', '/admin/keziszamlafej/viewkarb', 'keziszamlafejController#viewkarb', 'adminkeziszamlafejviewkarb');
$router->map('POST', '/admin/keziszamlafej/save', 'keziszamlafejController#save', 'adminkeziszamlafejsave');
$router->map('GET', '/admin/keziszamlafej/print', 'keziszamlafejController#doPrint', 'adminkeziszamlafejprint');
$router->map('POST', '/admin/keziszamlafej/ront', 'keziszamlafejController#ront', 'adminkeziszamlafejront');
$router->map('POST', '/admin/keziszamlafej/fejexport', 'keziszamlafejController#fejexport', 'adminkeziszamlafejfejexport');
$router->map('POST', '/admin/keziszamlafej/tetelexport', 'keziszamlafejController#tetelexport', 'adminkeziszamlafejtetelexport');

$router->map('GET', '/admin/bevetfej/viewlist', 'bevetfejController#viewlist', 'adminbevetfejviewlist');
$router->map('GET', '/admin/bevetfej/getlistbody', 'bevetfejController#getlistbody', 'adminbevetfejgetlistbody');
$router->map('GET', '/admin/bevetfej/getkarb', 'bevetfejController#getkarb', 'adminbevetfejgetkarb');
$router->map('GET', '/admin/bevetfej/viewkarb', 'bevetfejController#viewkarb', 'adminbevetfejviewkarb');
$router->map('POST', '/admin/bevetfej/save', 'bevetfejController#save', 'adminbevetfejsave');
$router->map('GET', '/admin/bevetfej/print', 'bevetfejController#doPrint', 'adminbevetfejprint');
$router->map('POST', '/admin/bevetfej/ront', 'bevetfejController#ront', 'adminbevetfejront');
$router->map('POST', '/admin/bevetfej/fejexport', 'bevetfejController#fejexport', 'adminbevetfejfejexport');
$router->map('POST', '/admin/bevetfej/tetelexport', 'bevetfejController#tetelexport', 'adminbevetfejtetelexport');

$router->map('GET', '/admin/koltsegszamlafej/viewlist', 'koltsegszamlafejController#viewlist', 'adminkoltsegszamlafejviewlist');
$router->map('GET', '/admin/koltsegszamlafej/getlistbody', 'koltsegszamlafejController#getlistbody', 'adminkoltsegszamlafejgetlistbody');
$router->map('GET', '/admin/koltsegszamlafej/getkarb', 'koltsegszamlafejController#getkarb', 'adminkoltsegszamlafejgetkarb');
$router->map('GET', '/admin/koltsegszamlafej/viewkarb', 'koltsegszamlafejController#viewkarb', 'adminkoltsegszamlafejviewkarb');
$router->map('POST', '/admin/koltsegszamlafej/save', 'koltsegszamlafejController#save', 'adminkoltsegszamlafejsave');
$router->map('GET', '/admin/koltsegszamlafej/print', 'koltsegszamlafejController#doPrint', 'adminkoltsegszamlafejprint');
$router->map('POST', '/admin/koltsegszamlafej/ront', 'koltsegszamlafejController#ront', 'adminkoltsegszamlafejront');
$router->map('POST', '/admin/koltsegszamlafej/fejexport', 'koltsegszamlafejController#fejexport', 'adminkoltsegszamlafejfejexport');
$router->map('POST', '/admin/koltsegszamlafej/tetelexport', 'koltsegszamlafejController#tetelexport', 'adminkoltsegszamlafejtetelexport');

$router->map('GET', '/admin/kivetfej/viewlist', 'kivetfejController#viewlist', 'adminkivetfejviewlist');
$router->map('GET', '/admin/kivetfej/getlistbody', 'kivetfejController#getlistbody', 'adminkivetfejgetlistbody');
$router->map('GET', '/admin/kivetfej/getkarb', 'kivetfejController#getkarb', 'adminkivetfejgetkarb');
$router->map('GET', '/admin/kivetfej/viewkarb', 'kivetfejController#viewkarb', 'adminkivetfejviewkarb');
$router->map('POST', '/admin/kivetfej/save', 'kivetfejController#save', 'adminkivetfejsave');
$router->map('GET', '/admin/kivetfej/print', 'kivetfejController#doPrint', 'adminkivetfejprint');
$router->map('POST', '/admin/kivetfej/ront', 'kivetfejController#ront', 'adminkivetfejront');
$router->map('POST', '/admin/kivetfej/fejexport', 'kivetfejController#fejexport', 'adminkivetfejfejexport');
$router->map('POST', '/admin/kivetfej/tetelexport', 'kivetfejController#tetelexport', 'adminkivetfejtetelexport');

$router->map('GET', '/admin/termek/viewlist', 'termekController#viewlist', 'admintermekviewlist');
$router->map('GET', '/admin/termek/htmllist', 'termekController#htmllist', 'admintermekhtmllist');
$router->map('GET', '/admin/termek/getlistbody', 'termekController#getlistbody', 'admintermekgetlistbody');
$router->map('GET', '/admin/termek/getkarb', 'termekController#getkarb', 'admintermekgetkarb');
$router->map('GET', '/admin/termek/viewkarb', 'termekController#viewkarb', 'admintermekviewkarb');
$router->map('GET', '/admin/termek/getnetto', 'termekController#getnetto', 'admintermekgetnetto');
$router->map('GET', '/admin/termek/getbrutto', 'termekController#getbrutto', 'admintermekgetbrutto');
$router->map('POST', '/admin/termek/save', 'termekController#save', 'admintermeksave');
$router->map('POST', '/admin/termek/setflag', 'termekController#setflag', 'admintermeksetflag');
$router->map('GET', '/admin/termek/arexport', 'termekController#arexport', 'admintermekarexport');
$router->map('GET', '/admin/termek/getkeszletbyraktar', 'termekController#getKeszletByRaktar', 'admingetkeszletbyraktar');
$router->map('POST', '/admin/termek/tcsset', 'termekController#setTermekcsoport', 'admintermektcsset');
$router->map('GET', '/admin/termek/getkapcsolodolist', 'termekController#getKapcsolodoSelectList', 'admingettermekkapcsolodolist');
$router->map('POST', '/admin/nepszeruseg/clear', 'termekController#clearNepszeruseg', 'adminclearnepszeruseg');

$router->map('GET', '/admin/termekkapcsolodo/getemptyrow', 'termekkapcsolodoController#getemptyrow', 'admintermekkapcsolodogetemptyrow');
$router->map('POST', '/admin/termekkapcsolodo/save', 'termekkapcsolodoController#save', 'admintermekkapcsolodosave');

$router->map('GET', '/admin/termekar/getemptyrow', 'termekarController#getemptyrow', 'admintermekargetemptyrow');
$router->map('POST', '/admin/termekar/save', 'termekarController#save', 'admintermekarsave');

$router->map('GET', '/admin/partnertermekcsoportkedvezmeny/getemptyrow', 'partnertermekcsoportkedvezmenyController#getemptyrow', 'adminpartnertermekcsoportkedvezmenygetemptyrow');
$router->map('POST', '/admin/partnertermekcsoportkedvezmeny/save', 'partnertermekcsoportkedvezmenyController#save', 'adminpartnertermekcsoportkedvezmenysave');

$router->map('GET', '/admin/partnertermekkedvezmeny/getemptyrow', 'partnertermekkedvezmenyController#getemptyrow', 'adminpartnertermekkedvezmenygetemptyrow');
$router->map('POST', '/admin/partnertermekkedvezmeny/save', 'partnertermekkedvezmenyController#save', 'adminpartnertermekkedvezmenysave');

$router->map('GET', '/admin/partnermijszoklevel/getemptyrow', 'partnermijszoklevelController#getemptyrow', 'adminpartnermijszoklevelgetemptyrow');
$router->map('POST', '/admin/partnermijszoklevel/save', 'partnermijszoklevelController#save', 'adminpartnermijszoklevelsave');

$router->map('GET', '/admin/termektranslation/getemptyrow', 'termektranslationController#getemptyrow', 'admintermektranslationgetemptyrow');
$router->map('POST', '/admin/termektranslation/save', 'termektranslationController#save', 'admintermektranslationsave');

$router->map('GET', '/admin/termekfatranslation/getemptyrow', 'termekfatranslationController#getemptyrow', 'admintermekfatranslationgetemptyrow');
$router->map('POST', '/admin/termekfatranslation/save', 'termekfatranslationController#save', 'admintermekfatranslationsave');

$router->map('GET', '/admin/statlaptranslation/getemptyrow', 'statlaptranslationController#getemptyrow', 'adminstatlaptranslationgetemptyrow');
$router->map('POST', '/admin/statlaptranslation/save', 'statlaptranslationController#save', 'adminstatlaptranslationsave');

$router->map('GET', '/admin/fizmodtranslation/getemptyrow', 'fizmodtranslationController#getemptyrow', 'adminfizmodtranslationgetemptyrow');
$router->map('POST', '/admin/fizmodtranslation/save', 'fizmodtranslationController#save', 'adminfizmodtranslationsave');

$router->map('GET', '/admin/szallitasimodtranslation/getemptyrow', 'szallitasimodtranslationController#getemptyrow', 'adminszallitasimodtranslationgetemptyrow');
$router->map('POST', '/admin/szallitasimodtranslation/save', 'szallitasimodtranslationController#save', 'adminszallitasimodtranslationsave');

$router->map('GET', '/admin/termekkep/getemptyrow', 'termekkepController#getemptyrow', 'admintermekkepgetemptyrow');
$router->map('POST', '/admin/termekkep/del', 'termekkepController#del', 'admintermekkepdel');

$router->map('GET', '/admin/termekrecept/getemptyrow', 'termekreceptController#getemptyrow', 'admintermekreceptgetemptyrow');
$router->map('POST', '/admin/termekrecept/save', 'termekreceptController#save', 'admintermekreceptsave');

$router->map('GET', '/admin/termekvaltozat/getemptyrow', 'termekvaltozatController#getemptyrow', 'admintermekvaltozatgetemptyrow');
$router->map('POST', '/admin/termekvaltozat/generate', 'termekvaltozatController#generate', 'admintermekvaltozatgenerate');
$router->map('POST', '/admin/termekvaltozat/save', 'termekvaltozatController#save', 'admintermekvaltozatsave');
$router->map('POST', '/admin/termekvaltozat/delall', 'termekvaltozatController#delall', 'admintermekvaltozatdelall');
$router->map('GET', '/admin/termekvaltozat/getkeszletbyraktar', 'termekvaltozatController#getKeszletByRaktar', 'admingetvaltozatkeszletbyraktar');

$router->map('GET', '/admin/szallitasimod/viewlist', 'szallitasimodController#viewlist', 'adminszallitasimodviewlist');
$router->map('GET', '/admin/szallitasimod/getlistbody', 'szallitasimodController#getlistbody', 'adminszallitasimodgetlistbody');
$router->map('GET', '/admin/szallitasimod/getkarb', 'szallitasimodController#getkarb', 'adminszallitasimodgetkarb');
$router->map('GET', '/admin/szallitasimod/viewkarb', 'szallitasimodController#viewkarb', 'adminszallitasimodviewkarb');
$router->map('POST', '/admin/szallitasimod/save', 'szallitasimodController#save', 'adminszallitasimodsave');
$router->map('GET', '/admin/szallitasimod/htmllist', 'szallitasimodController#htmllist', 'adminszallitasimodhtmllist');
$router->map('GET', '/admin/szallitasimodhatar/getemptyrow', 'szallitasimodhatarController#getemptyrow', 'adminszallitasimodhatargetemptyrow');
$router->map('POST', '/admin/szallitasimodhatar/save', 'szallitasimodhatarController#save', 'adminszallitasimodhatarsave');

$router->map('GET', '/admin/fizetesimod/viewlist', 'fizmodController#viewlist', 'adminfizetesimodviewlist');
$router->map('GET', '/admin/fizetesimod/getlistbody', 'fizmodController#getlistbody', 'adminfizetesimodgetlistbody');
$router->map('GET', '/admin/fizetesimod/getkarb', 'fizmodController#getkarb', 'adminfizetesimodgetkarb');
$router->map('GET', '/admin/fizetesimod/viewkarb', 'fizmodController#viewkarb', 'adminfizetesimodviewkarb');
$router->map('POST', '/admin/fizetesimod/save', 'fizmodController#save', 'adminfizetesimodsave');
$router->map('GET', '/admin/fizetesimod/htmllist', 'fizmodController#htmllist', 'adminfizetesimodhtmllist');
$router->map('GET', '/admin/fizmodhatar/getemptyrow', 'fizmodhatarController#getemptyrow', 'adminfizmodhatargetemptyrow');
$router->map('POST', '/admin/fizmodhatar/save', 'fizmodhatarController#save', 'adminfizmodhatarsave');

$router->map('GET', '/admin/emailtemplate/viewlist', 'emailtemplateController#viewlist', 'adminemailtemplateviewlist');
$router->map('GET', '/admin/emailtemplate/getlistbody', 'emailtemplateController#getlistbody', 'adminemailtemplategetlistbody');
$router->map('GET', '/admin/emailtemplate/getkarb', 'emailtemplateController#getkarb', 'adminemailtemplategetkarb');
$router->map('GET', '/admin/emailtemplate/viewkarb', 'emailtemplateController#viewkarb', 'adminemailtemplateviewkarb');
$router->map('POST', '/admin/emailtemplate/save', 'emailtemplateController#save', 'adminemailtemplatesave');

$router->map('GET', '/admin/dolgozo/viewlist', 'dolgozoController#viewlist', 'admindolgozoviewlist');
$router->map('GET', '/admin/dolgozo/getlistbody', 'dolgozoController#getlistbody', 'admindolgozogetlistbody');
$router->map('GET', '/admin/dolgozo/getkarb', 'dolgozoController#getkarb', 'admindolgozogetkarb');
$router->map('GET', '/admin/dolgozo/viewkarb', 'dolgozoController#viewkarb', 'admindolgozoviewkarb');
$router->map('POST', '/admin/dolgozo/save', 'dolgozoController#save', 'admindolgozosave');

$router->map('GET', '/admin/esemeny/viewlist', 'esemenyController#viewlist', 'adminesemenyviewlist');
$router->map('GET', '/admin/esemeny/getlistbody', 'esemenyController#getlistbody', 'adminesemenygetlistbody');
$router->map('GET', '/admin/esemeny/getkarb', 'esemenyController#getkarb', 'adminesemenygetkarb');
$router->map('GET', '/admin/esemeny/viewkarb', 'esemenyController#viewkarb', 'adminesemenyviewkarb');
$router->map('POST', '/admin/esemeny/save', 'esemenyController#save', 'adminesemenysave');

$router->map('GET', '/admin/teendo/viewlist', 'teendoController#viewlist', 'adminteendoviewlist');
$router->map('GET', '/admin/teendo/getlistbody', 'teendoController#getlistbody', 'adminteendogetlistbody');
$router->map('GET', '/admin/teendo/getkarb', 'teendoController#getkarb', 'adminteendogetkarb');
$router->map('GET', '/admin/teendo/viewkarb', 'teendoController#viewkarb', 'adminteendoviewkarb');
$router->map('POST', '/admin/teendo/save', 'teendoController#save', 'adminteendosave');
$router->map('POST', '/admin/teendo/setflag', 'teendoController#setflag', 'adminteendossetflag');

$router->map('GET', '/admin/hir/viewlist', 'hirController#viewlist', 'adminhirviewlist');
$router->map('GET', '/admin/hir/getlistbody', 'hirController#getlistbody', 'adminhirgetlistbody');
$router->map('GET', '/admin/hir/getkarb', 'hirController#getkarb', 'adminhirgetkarb');
$router->map('GET', '/admin/hir/viewkarb', 'hirController#viewkarb', 'adminhirviewkarb');
//$router->map('GET', '/admin/hir/gethirlist', 'hirController#gethirlist', 'adminhirgethirlist');
$router->map('GET', '/admin/hir/getfeedhirlist', 'hirController#getfeedhirlist', 'adminhirgetfeedhirlist');
$router->map('POST', '/admin/hir/save', 'hirController#save', 'adminhirsave');
$router->map('POST', '/admin/hir/setlathato', 'hirController#setlathato', 'adminhirsetlathato');

$router->map('GET', '/admin/jelenletiiv/viewlist', 'jelenletiivController#viewlist', 'adminjelenletiivviewlist');
$router->map('GET', '/admin/jelenletiiv/getlistbody', 'jelenletiivController#getlistbody', 'adminjelenletiivgetlistbody');
$router->map('GET', '/admin/jelenletiiv/getkarb', 'jelenletiivController#getkarb', 'adminjelenletiivgetkarb');
$router->map('GET', '/admin/jelenletiiv/viewkarb', 'jelenletiivController#viewkarb', 'adminjelenletiivviewkarb');
$router->map('POST', '/admin/jelenletiiv/save', 'jelenletiivController#save', 'adminjelenletiivsave');
$router->map('POST', '/admin/jelenletiiv/generatenapi', 'jelenletiivController#generatenapi', 'adminjelenletiivgeneratenapi');

$router->map('GET', '/admin/keresoszolog/viewlist', 'keresoszologController#viewlist', 'adminkeresoszologviewlist');
$router->map('GET', '/admin/keresoszolog/getlistbody', 'keresoszologController#getlistbody', 'adminkeresoszologgetlistbody');
$router->map('GET', '/admin/keresoszolog/getkarb', 'keresoszologController#getkarb', 'adminkeresoszologgetkarb');
$router->map('GET', '/admin/keresoszolog/viewkarb', 'keresoszologController#viewkarb', 'adminkeresoszologviewkarb');
$router->map('POST', '/admin/keresoszolog/save', 'keresoszologController#save', 'adminkeresoszologsave');

$router->map('GET', '/admin/statlap/viewlist', 'statlapController#viewlist', 'adminstatlapviewlist');
$router->map('GET', '/admin/statlap/getlistbody', 'statlapController#getlistbody', 'adminstatlapgetlistbody');
$router->map('GET', '/admin/statlap/getkarb', 'statlapController#getkarb', 'adminstatlapgetkarb');
$router->map('GET', '/admin/statlap/viewkarb', 'statlapController#viewkarb', 'adminstatlapviewkarb');
$router->map('POST', '/admin/statlap/save', 'statlapController#save', 'adminstatlapsave');

$router->map('GET', '/admin/template/viewlist', 'templateController#viewlist', 'admintemplateviewlist');
$router->map('GET', '/admin/template/getlistbody', 'templateController#getlistbody', 'admintemplategetlistbody');
$router->map('GET', '/admin/template/getkarb', 'templateController#getkarb', 'admintemplategetkarb');
$router->map('GET', '/admin/template/viewkarb', 'templateController#viewkarb', 'admintemplateviewkarb');
$router->map('POST', '/admin/template/save', 'templateController#save', 'admintemplatesave');

$router->map('GET', '/admin/feketelista/viewlist', 'feketelistaController#viewlist', 'adminfeketelistaviewlist');
$router->map('GET', '/admin/feketelista/getlistbody', 'feketelistaController#getlistbody', 'adminfeketelistagetlistbody');
$router->map('GET', '/admin/feketelista/getkarb', 'feketelistaController#getkarb', 'adminfeketelistagetkarb');
$router->map('GET', '/admin/feketelista/viewkarb', 'feketelistaController#viewkarb', 'adminfeketelistaviewkarb');
$router->map('POST', '/admin/feketelista/save', 'feketelistaController#save', 'adminfeketelistasave');
$router->map('POST', '/admin/feketelista/add', 'feketelistaController#add', 'adminfeketelistaadd');

$router->map('GET', '/admin/termekcimke/viewlist', 'termekcimkeController#viewlist', 'admintermekcimkeviewlist');
$router->map('GET', '/admin/termekcimke/getlistbody', 'termekcimkeController#getlistbody', 'admintermekcimkegetlistbody');
$router->map('GET', '/admin/termekcimke/getkarb', 'termekcimkeController#getkarb', 'admintermekcimkegetkarb');
$router->map('GET', '/admin/termekcimke/viewkarb', 'termekcimkeController#viewkarb', 'admintermekcimkeviewkarb');
$router->map('POST', '/admin/termekcimke/save', 'termekcimkeController#save', 'admintermekcimkesave');
$router->map('POST', '/admin/termekcimke/setmenulathato', 'termekcimkeController#setmenulathato', 'admintermekcimkesetmenulathato');
$router->map('POST', '/admin/termekcimke/add', 'termekcimkeController#add', 'admintermekcimkeadd');

$router->map('GET', '/admin/partnercimke/viewlist', 'partnercimkeController#viewlist', 'adminpartnercimkeviewlist');
$router->map('GET', '/admin/partnercimke/getlistbody', 'partnercimkeController#getlistbody', 'adminpartnercimkegetlistbody');
$router->map('GET', '/admin/partnercimke/getkarb', 'partnercimkeController#getkarb', 'adminpartnercimkegetkarb');
$router->map('GET', '/admin/partnercimke/viewkarb', 'partnercimkeController#viewkarb', 'adminpartnercimkeviewkarb');
$router->map('POST', '/admin/partnercimke/save', 'partnercimkeController#save', 'adminpartnercimkesave');
$router->map('POST', '/admin/partnercimke/setmenulathato', 'partnercimkeController#setmenulathato', 'adminpartnercimkesetmenulathato');
$router->map('POST', '/admin/partnercimke/add', 'partnercimkeController#add', 'adminpartnercimkeadd');

$router->map('GET', '/admin/korhinta/viewlist', 'korhintaController#viewlist', 'adminkorhintaviewlist');
$router->map('GET', '/admin/korhinta/getlistbody', 'korhintaController#getlistbody', 'adminkorhintagetlistbody');
$router->map('GET', '/admin/korhinta/getkarb', 'korhintaController#getkarb', 'adminkorhintagetkarb');
$router->map('GET', '/admin/korhinta/viewkarb', 'korhintaController#viewkarb', 'adminkorhintaviewkarb');
$router->map('POST', '/admin/korhinta/save', 'korhintaController#save', 'adminkorhintasave');
$router->map('POST', '/admin/korhinta/setflag', 'korhintaController#setflag', 'adminkorhintasetflag');

$router->map('GET', '/admin/partner/viewlist', 'partnerController#viewlist', 'adminpartnerviewlist');
$router->map('GET', '/admin/partner/getlistbody', 'partnerController#getlistbody', 'adminpartnergetlistbody');
$router->map('GET', '/admin/partner/getkarb', 'partnerController#getkarb', 'adminpartnergetkarb');
$router->map('GET', '/admin/partner/viewkarb', 'partnerController#viewkarb', 'adminpartnerviewkarb');
$router->map('POST', '/admin/partner/save', 'partnerController#save', 'adminpartnersave');
$router->map('POST', '/admin/partner/regisztral', 'partnerController#regisztral', 'adminpartnerregisztral');
$router->map('POST', '/admin/partner/checkemail', 'partnerController#checkemail', 'adminpartnercheckemail');
$router->map('GET', '/admin/partner/getdata', 'partnerController#getPartnerData', 'adminpartnergetdata');
$router->map('POST', '/admin/partner/getkiegyenlitetlenbiz', 'partnerController#getKiegyenlitetlenBiz', 'admingetkiegyenlitetlenbiz');
$router->map('POST', '/admin/partner/mijszexport', 'partnerController#mijszExport', 'adminmijszexport');
$router->map('POST', '/admin/partner/megjegyzesexport', 'partnerController#megjegyzesExport', 'adminmegjegyzesexport');
$router->map('POST', '/admin/partner/roadrecordexport', 'partnerController#roadrecordExport', 'adminroadrecordexport');

$router->map('GET', '/admin/termekfa/getkarb', 'termekfaController#getkarb', 'admintermekfagetkarb');
$router->map('GET', '/admin/termekfa/jsonlist', 'termekfaController#jsonlist', 'admintermekfajsonlist');
$router->map('POST', '/admin/termekfa/save', 'termekfaController#save', 'admintermekfasave');
$router->map('GET', '/admin/termekfa/isdeletable', 'termekfaController#isdeletable', 'admintermekfaisdeletable');
$router->map('POST', '/admin/termekfa/move', 'termekfaController#move', 'admintermekfamove');
$router->map('GET', '/admin/termekfa/viewlist', 'termekfaController#viewlist', 'admintermekfaviewlist');
$router->map('GET', '/admin/termekfa/regenerateslug', 'termekfaController#regenerateSlug', 'admintermekfaregenerateslug');

$router->map('GET', '/admin/kosar/viewlist', 'kosarController#viewlist', 'adminkosarviewlist');
$router->map('GET', '/admin/kosar/getlistbody', 'kosarController#getlistbody', 'adminkosargetlistbody');
$router->map('POST', '/admin/kosar/save', 'kosarController#save', 'adminkosarsave');

$router->map('GET', '/admin/uzletkoto/viewlist', 'uzletkotoController#viewlist', 'adminuzletkotoviewlist');
$router->map('GET', '/admin/uzletkoto/getlistbody', 'uzletkotoController#getlistbody', 'adminuzletkotogetlistbody');
$router->map('GET', '/admin/uzletkoto/getkarb', 'uzletkotoController#getkarb', 'adminuzletkotogetkarb');
$router->map('GET', '/admin/uzletkoto/viewkarb', 'uzletkotoController#viewkarb', 'adminuzletkotoviewkarb');
$router->map('POST', '/admin/uzletkoto/save', 'uzletkotoController#save', 'adminuzletkotosave');

$router->map('GET', '/admin/kupon/viewlist', 'kuponController#viewlist', 'adminkuponviewlist');
$router->map('GET', '/admin/kupon/getlistbody', 'kuponController#getlistbody', 'adminkupongetlistbody');
$router->map('GET', '/admin/kupon/getkarb', 'kuponController#getkarb', 'adminkupongetkarb');
$router->map('GET', '/admin/kupon/viewkarb', 'kuponController#viewkarb', 'adminkuponviewkarb');
$router->map('POST', '/admin/kupon/save', 'kuponController#save', 'adminkuponsave');
$router->map('GET', '/admin/kupon/print', 'kuponController#doPrint', 'adminkuponprint');

$router->map('GET', '/admin/sitemap/view', 'sitemapController#view', 'adminsitemapview');
$router->map('GET', '/admin/sitemap/create', 'sitemapController#create', 'adminsitemapcreate');

$router->map('GET', '/admin/export/view', 'exportController#view', 'adminexportview');
$router->map('GET', '/admin/export/grando', 'exportController#GrandoExport', 'admingrandoexport');

$router->map('POST', '/admin/import/stop/[:impname]', 'importController#stop', 'adminimportstop');
$router->map('GET', '/admin/import/view', 'importController#view', 'adminimportview');
$router->map('POST','/admin/import/kreativ', 'importController#kreativpuzzleImport', 'adminkreativpuzzleimport');
$router->map('POST','/admin/import/delton', 'importController#deltonImport', 'admindeltonimport');
//$router->map('POST','/admin/import/nomad', 'importController#nomadImport', 'adminnomadimport');
$router->map('POST','/admin/import/reintex', 'importController#reintexImport', 'adminreinteximport');
$router->map('POST','/admin/import/legavenue', 'importController#legavenueImport', 'adminlegavenueimport');
$router->map('POST','/admin/import/legavenueszotar', 'importController#legavenueSzotar', 'adminlegavenueSzotar');
$router->map('POST','/admin/import/tutisport', 'importController#tutisportImport', 'admintutisportimport');
$router->map('POST','/admin/import/makszutov', 'importController#makszutovImport', 'adminmakszutovimport');
$router->map('POST','/admin/import/silko', 'importController#silkoImport', 'adminsilkoimport');
$router->map('POST','/admin/import/btech', 'importController#btechImport', 'adminbtechimport');
$router->map('POST','/admin/import/kressgep', 'importController#kressgepImport', 'adminkressgepimport');
$router->map('POST','/admin/import/kresstartozek', 'importController#kresstartozekImport', 'adminkresstartozekimport');
$router->map('POST','/admin/import/vatera', 'importController#vateraImport', 'adminvateraimport');
//$router->map('POST','/admin/import/szatalakit', 'importController#szAtalakit', 'adminszatalakit');
//$router->map('POST','/admin/import/szinvarimport', 'importController#szInvarImport', 'adminszinvarimport');
//$router->map('POST','/admin/import/szpartnerimport', 'importController#szInvarPartnerImport', 'adminszinvarpartnerimport');
$router->map('POST','/admin/import/szimport', 'importController#szImport', 'adminszimport');
$router->map('POST','/admin/import/foxpostterminal', 'foxpostController#downloadTerminalList', 'adminfoxpostterminalimport');
$router->map('GET','/admin/import/szpartnerimport', 'importController#szSIIKerPartnerImport', 'adminszsiikerpartnerimport');


$router->map('GET', '/admin/login/show', 'dolgozoController#showlogin', 'adminshowlogin');
$router->map('POST', '/admin/login', 'dolgozoController#login', 'adminlogin');
$router->map('GET', '/admin/logout', 'dolgozoController#logout', 'adminlogout');

$router->map('GET', '/admin/bizonylatstatusz/viewlist', 'bizonylatstatuszController#viewlist', 'adminbizonylatstatuszviewlist');
$router->map('GET', '/admin/bizonylatstatusz/getlistbody', 'bizonylatstatuszController#getlistbody', 'adminbizonylatstatuszgetlistbody');
$router->map('GET', '/admin/bizonylatstatusz/getkarb', 'bizonylatstatuszController#getkarb', 'adminbizonylatstatuszgetkarb');
$router->map('GET', '/admin/bizonylatstatusz/viewkarb', 'bizonylatstatuszController#viewkarb', 'adminbizonylatstatuszviewkarb');
$router->map('POST', '/admin/bizonylatstatusz/save', 'bizonylatstatuszController#save', 'adminbizonylatstatuszsave');

$router->map('GET', '/admin/rlbexport', 'exportController#RLBExport', 'adminrlbexport');

$router->map('GET', '/admin/fifoteszt', 'fifoController#teszt', 'adminfifoteszt');
$router->map('GET', '/admin/fifo/view', 'fifoController#view', 'adminfifoview');
$router->map('POST', '/admin/fifo/calc', 'fifoController#calculate', 'adminfifocalc');
$router->map('GET', '/admin/fifo/alapadat', 'fifoController#getAlapadat', 'adminfifoalapadat');
$router->map('GET', '/admin/fifo/keszletertek', 'fifoController#getKeszletertek', 'adminkeszletertek');

$router->map('POST', '/admin/otpay/refund', 'megrendelesfejController#otpayrefund', 'adminotpayrefund');
$router->map('POST', '/admin/otpay/storno', 'megrendelesfejController#otpaystorno', 'adminotpaystorno');

$router->map('GET', '/admin/lista/boltbannincsmasholvan', 'listaController#boltbannincsmasholvan', 'adminlistaboltbannincsmasholvan');
$router->map('GET', '/admin/lista/nemkaphatoertesito', 'listaController#nemkaphatoertesito', 'adminlistanemkaphatoertesito');
//$router->map('GET', '/admin/fillbiztetelvaltozat', 'adminController#fillBiztetelValtozat', 'adminfillbiztetelvaltozat');
$router->map('POST', '/admin/napijelentes', 'adminController#printNapijelentes', 'adminnapijelentes');
$router->map('POST', '/admin/teljesitmenyjelentes', 'adminController#printTeljesitmenyJelentes', 'adminteljesitmenyjelentes');
//$router->map('GET', '/admin/genfszla', 'adminController#generateFolyoszamla', 'admingeneratefolyoszamla');

$router->map('GET', '/admin/keszletlista/view', 'keszletlistaController#view', 'adminkeszletlistaview');
$router->map('GET', '/admin/keszletlista/get', 'keszletlistaController#createLista', 'adminkeszletlistaget');
$router->map('GET', '/admin/keszletlista/export', 'keszletlistaController#exportLista', 'adminkeszletlistaexport');

$router->map('GET', '/admin/keresoszolista/view', 'keresoszolistaController#view', 'adminkeresoszolistaview');
$router->map('GET', '/admin/keresoszolista/get', 'keresoszolistaController#createLista', 'adminkeresoszolistaget');

$router->map('GET', '/admin/termekkarton/view', 'termekkartonController#view', 'admintermekkartonview');
$router->map('GET', '/admin/termekkarton/refresh', 'termekkartonController#refresh', 'admintermekkartonrefresh');

$router->map('GET', '/admin/termekforgalmilista/view', 'termekforgalmilistaController#view', 'admintermekforgalmilistaview');
$router->map('GET', '/admin/termekforgalmilista/refresh', 'termekforgalmilistaController#refresh', 'admintermekforgalmilistarefresh');
$router->map('GET', '/admin/termekforgalmilista/export', 'termekforgalmilistaController#export', 'admintermekforgalmilistaexport');

$router->map('GET', '/admin/bizonylattetellista/view', 'bizonylattetellistaController#view', 'adminbizonylattetellistaview');
$router->map('GET', '/admin/bizonylattetellista/refresh', 'bizonylattetellistaController#refresh', 'adminbizonylattetellistarefresh');
$router->map('GET', '/admin/bizonylattetellista/export', 'bizonylattetellistaController#export', 'adminbizonylattetellistaexport');
$router->map('GET', '/admin/bizonylattetellista/print', 'bizonylattetellistaController#doPrint', 'adminbizonylattetellistaprint');

$router->map('GET', '/admin/bizomanyosertekesiteslista/view', 'bizomanyosertekesiteslistaController#view', 'adminbizomanyosertekesiteslistaview');
$router->map('GET', '/admin/bizomanyosertekesiteslista/refresh', 'bizomanyosertekesiteslistaController#refresh', 'adminbizomanyosertekesiteslistarefresh');

if (\mkw\store::isSuperzoneB2B()) {
    if (haveJog(99)) {
        $router->map('GET', '/admin/mese', 'fantaController#mese', 'adminmese');
    }
}

$router->map('GET', '/admin/t/minicrm', 'adminController#minicrm', 'adminminicrm');
$router->map('GET', '/admin/t/kerriiimport', 'importController#kerriiimport', 'adminkerriiimport');
$router->map('GET', '/admin/t/genean13', 'adminController#genean13', 'admingenean13');
