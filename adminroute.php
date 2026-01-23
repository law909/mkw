<?php

$router->map('GET', '/admin/cron', 'cronController#run', 'admincron');

$router->map('GET', '/admin', 'adminController#view', 'adminview');
$router->map('GET', '/admin/view', 'adminController#view', 'adminview2');
$router->map('GET', '/admin/egyebtorzs/view', 'egyebtorzsController#view', 'adminegyebtorzsview');
$router->map('GET', '/admin/afa/jsonlist', 'afaController#jsonlist', 'adminafajsonlist');
$router->map('GET', '/admin/afa/htmllist', 'afaController#htmllist', 'adminafahtmllist');
$router->map('POST', '/admin/afa/save', 'afaController#save', 'adminafasave');
$router->map('GET', '/admin/afa/navcaselist', 'afaController#navcaselist', 'adminafanavcaselist');
$router->map('GET', '/admin/arfolyam/jsonlist', 'arfolyamController#jsonlist', 'adminarfolyamjsonlist');
$router->map('GET', '/admin/arfolyam/htmllist', 'arfolyamController#htmllist', 'adminarfolyamhtmllist');
$router->map('POST', '/admin/arfolyam/save', 'arfolyamController#save', 'adminarfolyamsave');
$router->map('GET', '/admin/arfolyam/getarfolyam', 'arfolyamController#getarfolyam', 'admingetarfolyam');
$router->map('POST', '/admin/arfolyam/download', 'arfolyamController#downloadArfolyam', 'admindownloadarfolyam');
$router->map('GET', '/admin/unnepnap/jsonlist', 'unnepnapController#jsonlist', 'adminunnepnapjsonlist');
$router->map('GET', '/admin/unnepnap/htmllist', 'unnepnapController#htmllist', 'adminunnepnaphtmllist');
$router->map('POST', '/admin/unnepnap/save', 'unnepnapController#save', 'adminunnepnapsave');
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
$router->map('GET', '/admin/me/jsonlist', 'meController#jsonlist', 'adminmejsonlist');
$router->map('GET', '/admin/me/htmllist', 'meController#htmllist', 'adminmehtmllist');
$router->map('POST', '/admin/me/save', 'meController#save', 'adminmesave');
$router->map('GET', '/admin/me/navtipuslist', 'meController#navtipuslist', 'adminmenavtipuslist');
$router->map('GET', '/admin/korzetszam/jsonlist', 'korzetszamController#jsonlist', 'adminkorzetszamjsonlist');
$router->map('GET', '/admin/korzetszam/htmllist', 'korzetszamController#htmllist', 'adminkorzetszamhtmllist');
$router->map('POST', '/admin/korzetszam/save', 'korzetszamController#save', 'adminkorzetszamsave');
$router->map('GET', '/admin/mijszoklevelkibocsajto/jsonlist', 'mijszoklevelkibocsajtoController#jsonlist', 'adminmijszoklevelkibocsajtojsonlist');
$router->map('GET', '/admin/mijszoklevelkibocsajto/htmllist', 'mijszoklevelkibocsajtoController#htmllist', 'adminmijszoklevelkibocsajtohtmllist');
$router->map('POST', '/admin/mijszoklevelkibocsajto/save', 'mijszoklevelkibocsajtoController#save', 'adminmijszoklevelkibocsajtosave');
$router->map('GET', '/admin/mijszoklevelszint/jsonlist', 'mijszoklevelszintController#jsonlist', 'adminmijszoklevelszintjsonlist');
$router->map('GET', '/admin/mijszoklevelszint/htmllist', 'mijszoklevelszintController#htmllist', 'adminmijszoklevelszinthtmllist');
$router->map('POST', '/admin/mijszoklevelszint/save', 'mijszoklevelszintController#save', 'adminmijszoklevelszintsave');
$router->map('GET', '/admin/mijszgyakorlasszint/jsonlist', 'mijszgyakorlasszintController#jsonlist', 'adminmijszgyakorlasszintjsonlist');
$router->map('GET', '/admin/mijszgyakorlasszint/htmllist', 'mijszgyakorlasszintController#htmllist', 'adminmijszgyakorlasszinthtmllist');
$router->map('POST', '/admin/mijszgyakorlasszint/save', 'mijszgyakorlasszintController#save', 'adminmijszgyakorlasszintsave');
$router->map('GET', '/admin/szotar/jsonlist', 'szotarController#jsonlist', 'adminszotarjsonlist');
$router->map('GET', '/admin/szotar/htmllist', 'szotarController#htmllist', 'adminszotarhtmllist');
$router->map('POST', '/admin/szotar/save', 'szotarController#save', 'adminszotarsave');
$router->map('GET', '/admin/termekrecepttipus/jsonlist', 'termekrecepttipusController#jsonlist', 'admintermekrecepttipusjsonlist');
$router->map('GET', '/admin/termekrecepttipus/htmllist', 'termekrecepttipusController#htmllist', 'admintermekrecepttipushtmllist');
$router->map('POST', '/admin/termekrecepttipus/save', 'termekrecepttipusController#save', 'admintermekrecepttipussave');
$router->map('GET', '/admin/jogaterem/jsonlist', 'jogateremController#jsonlist', 'adminjogateremjsonlist');
$router->map('GET', '/admin/jogaterem/htmllist', 'jogateremController#htmllist', 'adminjogateremhtmllist');
$router->map('POST', '/admin/jogaterem/save', 'jogateremController#save', 'adminjogateremsave');
$router->map('GET', '/admin/jogaoratipus/jsonlist', 'jogaoratipusController#jsonlist', 'adminjogaoratipusjsonlist');
$router->map('GET', '/admin/jogaoratipus/htmllist', 'jogaoratipusController#htmllist', 'adminjogaoratipushtmllist');
$router->map('POST', '/admin/jogaoratipus/save', 'jogaoratipusController#save', 'adminjogaoratipussave');
$router->map('GET', '/admin/rendezvenyallapot/jsonlist', 'rendezvenyallapotController#jsonlist', 'adminrendezvenyallapotjsonlist');
$router->map('GET', '/admin/rendezvenyallapot/htmllist', 'rendezvenyallapotController#htmllist', 'adminrendezvenyallapothtmllist');
$router->map('POST', '/admin/rendezvenyallapot/save', 'rendezvenyallapotController#save', 'adminrendezvenyallapotsave');

$router->map('GET', '/admin/arsav/jsonlist', 'arsavController#jsonlist', 'adminarsavjsonlist');
$router->map('GET', '/admin/arsav/htmllist', 'arsavController#htmllist', 'adminarsavhtmllist');
$router->map('POST', '/admin/arsav/save', 'arsavController#save', 'adminarsavsave');

$router->map('GET', '/admin/arlista/view', 'arlistaController#view', 'adminarlistaview');
$router->map('GET', '/admin/arlista/get', 'arlistaController#createLista', 'adminarlistaget');
$router->map('GET', '/admin/arlista/export', 'arlistaController#exportLista', 'adminarlistaexport');

$router->map('GET', '/admin/refreshkintlevoseg', 'adminController#refreshKintlevoseg', 'adminrefreshkintlevoseg');
$router->map('GET', '/admin/refreshspanyolkintlevoseg', 'adminController#refreshSpanyolKintlevoseg', 'adminrefreshspanyolkintlevoseg');
$router->map('GET', '/admin/refreshteljesithetobackorderek', 'adminController#refreshTeljesithetoBackorderek', 'adminrefreshteljesithetobackorderek');

if (\mkw\store::isMPT()) {
    $router->map('GET', '/admin/mpttagozat/jsonlist', 'mpttagozatController#jsonlist', 'adminmpttagozatjsonlist');
    $router->map('GET', '/admin/mpttagozat/htmllist', 'mpttagozatController#htmllist', 'adminmpttagozathtmllist');
    $router->map('POST', '/admin/mpttagozat/save', 'mpttagozatController#save', 'adminmpttagozatsave');

    $router->map('GET', '/admin/mptszekcio/jsonlist', 'mptszekcioController#jsonlist', 'adminmptszekciojsonlist');
    $router->map('GET', '/admin/mptszekcio/htmllist', 'mptszekcioController#htmllist', 'adminmptszekciohtmllist');
    $router->map('POST', '/admin/mptszekcio/save', 'mptszekcioController#save', 'adminmptszekciosave');

    $router->map('GET', '/admin/mpttagsagforma/jsonlist', 'mpttagsagformaController#jsonlist', 'adminmpttagsagformajsonlist');
    $router->map('GET', '/admin/mpttagsagforma/htmllist', 'mpttagsagformaController#htmllist', 'adminmpttagsagformahtmllist');
    $router->map('POST', '/admin/mpttagsagforma/save', 'mpttagsagformaController#save', 'adminmpttagsagformasave');

    $router->map('GET', '/admin/mptfolyoszamla/getemptyeloirasrow', 'mptfolyoszamlaController#getemptyeloirasrow', 'admingetemptyeloirasrow');
    $router->map('POST', '/admin/mptfolyoszamla/saveeloiras', 'mptfolyoszamlaController#saveeloiras', 'adminmptfolyoszamlasaveeloiras');
    $router->map('GET', '/admin/mptfolyoszamla/getemptybefizetesrow', 'mptfolyoszamlaController#getemptybefizetesrow', 'admingetemptybefizetesrow');
    $router->map('POST', '/admin/mptfolyoszamla/savebefizetes', 'mptfolyoszamlaController#savebefizetes', 'adminmptfolyoszamlasavebefizetes');
    $router->map('POST', '/admin/mptfolyoszamla/del', 'mptfolyoszamlaController#del', 'adminmptfolyoszamladel');
}

if (\mkw\store::isMPTNGY()) {
    $router->map('GET', '/admin/mptngytemakor/jsonlist', 'mptngytemakorController#jsonlist', 'adminmptngytemakorjsonlist');
    $router->map('GET', '/admin/mptngytemakor/htmllist', 'mptngytemakorController#htmllist', 'adminmptngytemakorhtmllist');
    $router->map('POST', '/admin/mptngytemakor/save', 'mptngytemakorController#save', 'adminmptngytemakorsave');

    $router->map('GET', '/admin/mptngytema/jsonlist', 'mptngytemaController#jsonlist', 'adminmptngytemajsonlist');
    $router->map('GET', '/admin/mptngytema/htmllist', 'mptngytemaController#htmllist', 'adminmptngytemahtmllist');
    $router->map('POST', '/admin/mptngytema/save', 'mptngytemaController#save', 'adminmptngytemasave');

    $router->map('GET', '/admin/mptngyszerepkor/jsonlist', 'mptngyszerepkorController#jsonlist', 'adminmptngyszerepkorjsonlist');
    $router->map('GET', '/admin/mptngyszerepkor/htmllist', 'mptngyszerepkorController#htmllist', 'adminmptngyszerepkorhtmllist');
    $router->map('POST', '/admin/mptngyszerepkor/save', 'mptngyszerepkorController#save', 'adminmptngyszerepkorsave');

    $router->map('GET', '/admin/mptngyszakmaianyagtipus/jsonlist', 'mptngyszakmaianyagtipusController#jsonlist', 'adminmptngyszakmaianyagtipusjsonlist');
    $router->map('GET', '/admin/mptngyszakmaianyagtipus/htmllist', 'mptngyszakmaianyagtipusController#htmllist', 'adminmptngyszakmaianyagtipushtmllist');
    $router->map('POST', '/admin/mptngyszakmaianyagtipus/save', 'mptngyszakmaianyagtipusController#save', 'adminmptngyszakmaianyagtipussave');

    $router->map('GET', '/admin/mptngyegyetem/jsonlist', 'mptngyegyetemController#jsonlist', 'adminmptngyegyetemjsonlist');
    $router->map('GET', '/admin/mptngyegyetem/htmllist', 'mptngyegyetemController#htmllist', 'adminmptngyegyetemhtmllist');
    $router->map('POST', '/admin/mptngyegyetem/save', 'mptngyegyetemController#save', 'adminmptngyegyetemsave');

    $router->map('GET', '/admin/mptngykar/jsonlist', 'mptngykarController#jsonlist', 'adminmptngykarjsonlist');
    $router->map('GET', '/admin/mptngykar/htmllist', 'mptngykarController#htmllist', 'adminmptngykarhtmllist');
    $router->map('POST', '/admin/mptngykar/save', 'mptngykarController#save', 'adminmptngykarsave');

    $router->map('GET', '/admin/mptngyszakmaianyag/viewlist', 'mptngyszakmaianyagController#viewlist', 'adminmptngyszakmaianyagviewlist');
    $router->map('GET', '/admin/mptngyszakmaianyag/getlistbody', 'mptngyszakmaianyagController#getlistbody', 'adminmptngyszakmaianyaggetlistbody');
    $router->map('GET', '/admin/mptngyszakmaianyag/getkarb', 'mptngyszakmaianyagController#getkarb', 'adminmptngyszakmaianyaggetkarb');
    $router->map('GET', '/admin/mptngyszakmaianyag/viewkarb', 'mptngyszakmaianyagController#viewkarb', 'adminmptngyszakmaianyagviewkarb');
    if (!\mkw\store::isClosed()) {
        $router->map('POST', '/admin/mptngyszakmaianyag/save', 'mptngyszakmaianyagController#save', 'adminmptngyszakmaianyagsave');
        $router->map('POST', '/admin/mptngyszakmaianyag/ront', 'mptngyszakmaianyagController#ront', 'adminmptngyszakmaianyagront');
        $router->map('POST', '/admin/bankbizonylattetel/save', 'bankbizonylattetelController#save', 'adminbankbizonylattetelsave');
    }
    $router->map('GET', '/admin/bankbizonylattetel/getemptyrow', 'bankbizonylattetelController#getemptyrow', 'adminbankbizonylattetelgetemptyrow');

    $router->map('GET', '/admin/recalcksz', 'adminController#recalcKonferencianszerepelhet', 'adminmptngyrecalckonferencianszerepelhet');
    $router->map('GET', '/admin/setszerzobyemail', 'adminController#setSzerzoByEmail', 'adminmptngysetszerzobyemail');

    $router->map('POST', '/admin/import/mptngybiraloimport', 'importController#mptngybiraloimport', 'adminmptngybiraloimport');
}

if (\mkw\store::isBankpenztar()) {
    $router->map('GET', '/admin/jogcim/jsonlist', 'jogcimController#jsonlist', 'adminjogcimjsonlist');
    $router->map('GET', '/admin/jogcim/htmllist', 'jogcimController#htmllist', 'adminjogcimhtmllist');
    $router->map('POST', '/admin/jogcim/save', 'jogcimController#save', 'adminjogcimsave');

    $router->map('GET', '/admin/bankbizonylatfej/viewlist', 'bankbizonylatfejController#viewlist', 'adminbankbizonylatfejviewlist');
    $router->map('GET', '/admin/bankbizonylatfej/getlistbody', 'bankbizonylatfejController#getlistbody', 'adminbankbizonylatfejgetlistbody');
    $router->map('GET', '/admin/bankbizonylatfej/getkarb', 'bankbizonylatfejController#getkarb', 'adminbankbizonylatfejgetkarb');
    $router->map('GET', '/admin/bankbizonylatfej/viewkarb', 'bankbizonylatfejController#viewkarb', 'adminbankbizonylatfejviewkarb');
    if (!\mkw\store::isClosed()) {
        $router->map('POST', '/admin/bankbizonylatfej/save', 'bankbizonylatfejController#save', 'adminbankbizonylatfejsave');
        $router->map('POST', '/admin/bankbizonylatfej/ront', 'bankbizonylatfejController#ront', 'adminbankbizonylatfejront');
        $router->map('POST', '/admin/bankbizonylattetel/save', 'bankbizonylattetelController#save', 'adminbankbizonylattetelsave');
    }
    $router->map('GET', '/admin/bankbizonylatfej/print', 'bankbizonylatfejController#doPrint', 'adminbankbizonylatfejprint');
    $router->map('GET', '/admin/bankbizonylattetel/getemptyrow', 'bankbizonylattetelController#getemptyrow', 'adminbankbizonylattetelgetemptyrow');
    $router->map('GET', '/admin/bankbizonylattetel/viewlist', 'bankbizonylattetelController#viewlist', 'adminbankbizonylattetelviewlist');
    $router->map('GET', '/admin/bankbizonylattetel/viewselect', 'bankbizonylattetelController#viewselect', 'adminbankbizonylattetelviewselect');
    $router->map('GET', '/admin/bankbizonylattetel/getlistbody', 'bankbizonylattetelController#getlistbody', 'adminbankbizonylattetelgetlistbody');

    $router->map('GET', '/admin/penztarbizonylatfej/viewlist', 'penztarbizonylatfejController#viewlist', 'adminpenztarbizonylatfejviewlist');
    $router->map('GET', '/admin/penztarbizonylatfej/getlistbody', 'penztarbizonylatfejController#getlistbody', 'adminpenztarbizonylatfejgetlistbody');
    $router->map('GET', '/admin/penztarbizonylatfej/getkarb', 'penztarbizonylatfejController#getkarb', 'adminpenztarbizonylatfejgetkarb');
    $router->map('GET', '/admin/penztarbizonylatfej/viewkarb', 'penztarbizonylatfejController#viewkarb', 'adminpenztarbizonylatfejviewkarb');
    if (!\mkw\store::isClosed()) {
        $router->map('POST', '/admin/penztarbizonylatfej/save', 'penztarbizonylatfejController#save', 'adminpenztarbizonylatfejsave');
        $router->map('POST', '/admin/penztarbizonylatfej/ront', 'penztarbizonylatfejController#ront', 'adminpenztarbizonylatfejront');
        $router->map('POST', '/admin/penztarbizonylattetel/save', 'penztarbizonylattetelController#save', 'adminpenztarbizonylattetelsave');
    }
    $router->map('GET', '/admin/penztarbizonylatfej/print', 'penztarbizonylatfejController#doPrint', 'adminpenztarbizonylatfejprint');
    $router->map('GET', '/admin/penztarbizonylatfej/checkdatum', 'penztarbizonylatfejController#checkZartIdoszak', 'adminpenztarbizonylatfejcheckzartidoszak');
    $router->map('GET', '/admin/penztarbizonylattetel/getemptyrow', 'penztarbizonylattetelController#getemptyrow', 'adminpenztarbizonylattetelgetemptyrow');

    $router->map('GET', '/admin/kintlevoseglista/view', 'kintlevoseglistaController#view', 'adminkintlevoseglistaview');
    $router->map('GET', '/admin/kintlevoseglista/get', 'kintlevoseglistaController#createLista', 'adminkintlevoseglistaget');
    $router->map('GET', '/admin/kintlevoseglista/export', 'kintlevoseglistaController#exportLista', 'adminkintlevoseglistaexport');

    $router->map('GET', '/admin/tartozaslista/view', 'tartozaslistaController#view', 'admintartozaslistaview');
    $router->map('GET', '/admin/tartozaslista/get', 'tartozaslistaController#createLista', 'admintartozaslistaget');
    $router->map('GET', '/admin/tartozaslista/export', 'tartozaslistaController#exportLista', 'admintartozaslistaexport');

    $router->map('GET', '/admin/penzbelista/view', 'penzbelistaController#view', 'adminpenzbelistaview');
    $router->map('GET', '/admin/penzbelista/get', 'penzbelistaController#createLista', 'adminpenzbelistaget');

    $router->map('GET', '/admin/munkaidolista/view', 'munkaidolistaController#view', 'adminmunkaidolistaview');
    $router->map('GET', '/admin/munkaidolista/get', 'munkaidolistaController#createLista', 'adminmunkaidolistaget');

    $router->map('GET', '/admin/jutaleklista/view', 'jutaleklistaController#view', 'adminjutaleklistaview');
    $router->map('GET', '/admin/jutaleklista/get', 'jutaleklistaController#createLista', 'adminjutaleklistaget');
    $router->map('GET', '/admin/jutaleklista/export', 'jutaleklistaController#exportLista', 'adminjutaleklistaexport');

    $router->map('GET', '/admin/idoszakipenztarjelenteslista/view', 'idoszakipenztarjelenteslistaController#view', 'adminidoszakipenztarjelenteslistaview');
    $router->map(
        'GET',
        '/admin/idoszakipenztarjelenteslista/get',
        'idoszakipenztarjelenteslistaController#createLista',
        'adminidoszakipenztarjelenteslistaget'
    );
    $router->map(
        'GET',
        '/admin/idoszakipenztarjelenteslista/export',
        'idoszakipenztarjelenteslistaController#exportLista',
        'adminidoszakipenztarjelenteslistaexport'
    );

    $router->map('GET', '/admin/penztarzaras/view', 'penztarbizonylatfejController#zarasView', 'adminpenztarzarasview');
    $router->map('POST', '/admin/penztarzaras/zar', 'penztarbizonylatfejController#zar', 'adminpenztarzaraszar');
}

$router->map('GET', '/admin/getsmallurl', 'adminController#getSmallUrl', 'admingetsmallurl');
$router->map('GET', '/admin/regeneratekarkod', 'adminController#regeneratekarkod', 'adminregeneratekarkod');
$router->map('GET', '/admin/regeneratemenukarkod', 'adminController#regeneratemenukarkod', 'adminregeneratemenukarkod');
$router->map('GET', '/admin/setuitheme', 'adminController#setUITheme', 'adminsetuitheme');
$router->map('GET', '/admin/setgrideditbutton', 'adminController#setGridEditButton', 'adminsetgrideditbutton');
$router->map('GET', '/admin/seteditstyle', 'adminController#setEditStyle', 'adminseteditstyle');
$router->map('GET', '/admin/setvonalkodfromvaltozat', 'adminController#setVonalkodFromValtozat', 'adminsetvonalkodfromvaltozat');

$router->map('GET', '/admin/setup/view', 'setupController#view', 'adminsetupview');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/setup/save', 'setupController#save', 'adminsetupsave');
}

$router->map('GET', '/admin/navadatexport/view', 'navadatexportController#view', 'adminnavadatexportview');
$router->map('GET', '/admin/navadatexport/get', 'navadatexportController#createLista', 'adminnavadatexportget');
$router->map('GET', '/admin/navadatexport/check', 'navadatexportController#check', 'adminnavadatexportcheck');

$router->map('GET', '/admin/bizonylattetel/getar', 'bizonylattetelController#getar', 'adminbizonylattetelgetar');
$router->map('GET', '/admin/bizonylattetel/calcar', 'bizonylattetelController#calcarforclient', 'adminbizonylattetelcalcar');
$router->map('GET', '/admin/bizonylattetel/getemptyrow', 'bizonylattetelController#getemptyrow', 'adminbizonylattetelgetemptyrow');
$router->map('GET', '/admin/bizonylattetel/getquickemptyrow', 'bizonylattetelController#getquickemptyrow', 'adminbizonylattetelgetquickemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/bizonylattetel/save', 'bizonylattetelController#save', 'adminbizonylattetelsave');
}
$router->map('GET', '/admin/bizonylattetel/gettermeklist', 'termekController#getBizonylattetelSelectList', 'adminbizonylattetelgettermeklist');
$router->map('GET', '/admin/bizonylattetel/valtozatlist', 'bizonylattetelController#valtozathtmllist', 'adminvaltozatlist');
$router->map('GET', '/admin/bizonylattetel/quickvaltozatlist', 'bizonylattetelController#quickvaltozathtmllist', 'adminquickvaltozatlist');

$router->map('GET', '/admin/bizonylatfej/checkkelt', 'bizonylatfejController#checkKelt', 'adminbizonylatfejcheckkelt');
$router->map('GET', '/admin/bizonylatfej/calcesedekesseg', 'bizonylatfejController#calcesedekesseg', 'adminbizonylatfejcalcesedekesseg');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/bizonylatfej/setstatusz', 'bizonylatfejController#setStatusz', 'adminbizonylatfejsetstatusz');
    $router->map('POST', '/admin/bizonylatfej/setnyomtatva', 'bizonylatfejController#setNyomtatva', 'adminbizonylatfejsetnyomtatva');
    $router->map('POST', '/admin/bizonylatfej/emailpdf', 'bizonylatfejController#sendPDF', 'adminbizonylatfejemailpdf');
    $router->map('POST', '/admin/bizonylatfej/navonline', 'bizonylatfejController#navonline', 'adminbizonylatfejnavonline');
    $router->map('POST', '/admin/bizonylatfej/navstat', 'bizonylatfejController#requeryNavEredmeny', 'adminbizonylatfejnavstat');
    $router->map('POST', '/admin/bizonylatfej/sendemailsablon', 'bizonylatfejController#sendEmailSablon', 'adminbizonylatfejsendemailsablon');
    $router->map('POST', '/admin/bizonylatfej/sendemailsablonok', 'bizonylatfejController#sendEmailSablonok', 'adminbizonylatfejsendemailsablonok');
    $router->map('POST', '/admin/bizonylatfej/quickadd', 'bizonylatfejController#quickAdd', 'adminbizonylatfejquickadd');
}
$router->map('GET', '/admin/bizonylatfej/getpartnerlist', 'partnerController#getBizonylatfejSelectList', 'adminbizonylatfejgetpartnerlist');
$router->map('GET', '/admin/bizonylatfej/pdf', 'bizonylatfejController#doPDF', 'adminbizonylatfejpdf');
$router->map('GET', '/admin/bizonylatfej/getfolyoszamla', 'bizonylatfejController#getFolyoszamla', 'admingetfolyoszamla');

$router->map('GET', '/admin/megrendelesfej/viewlist', 'megrendelesfejController#viewlist', 'adminmegrendelesfejviewlist');
$router->map('GET', '/admin/megrendelesfej/getlistbody', 'megrendelesfejController#getlistbody', 'adminmegrendelesfejgetlistbody');
$router->map('GET', '/admin/megrendelesfej/getkarb', 'megrendelesfejController#getkarb', 'adminmegrendelesfejgetkarb');
$router->map('GET', '/admin/megrendelesfej/viewkarb', 'megrendelesfejController#viewkarb', 'adminmegrendelesfejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/megrendelesfej/save', 'megrendelesfejController#save', 'adminmegrendelesfejsave');
    $router->map('POST', '/admin/megrendelesfej/sendtofoxpost', 'megrendelesfejController#sendToFoxPost', 'adminmegrendelessendtofoxpost');
    $router->map('POST', '/admin/megrendelesfej/generatefoxpostlabel', 'megrendelesfejController#generateFoxpostLabel', 'admingeneratefoxpostlabel');
    $router->map('POST', '/admin/megrendelesfej/sendtogls', 'megrendelesfejController#sendToGLS', 'adminmegrendelessendtogls');
    $router->map('POST', '/admin/megrendelesfej/delglsparcel', 'megrendelesfejController#delGLSParcel', 'adminmegrendelesdelglsparcel');
    $router->map('POST', '/admin/megrendelesfej/ront', 'megrendelesfejController#ront', 'adminmegrendelesfejront');
    $router->map('POST', '/admin/megrendelesfej/backorder', 'megrendelesfejController#backOrder', 'adminmegrendelesfejbackorder');
    $router->map('POST', '/admin/megrendelesfej/fejexport', 'megrendelesfejController#fejexport', 'adminmegrendelesfejfejexport');
    $router->map('POST', '/admin/megrendelesfej/tetelexport', 'megrendelesfejController#tetelexport', 'adminmegrendelesfejtetelexport');
    $router->map('GET', '/admin/megrendelesfej/getszamlakarb', 'megrendelesfejController#getszamlakarb', 'adminmegrendelesfejgetszamlakarb');
    $router->map('POST', '/admin/megrendelesfej/recalcprice', 'megrendelesfejController#recalcPrice', 'adminmegrendelesrecalcprice');
}
$router->map('GET', '/admin/megrendelesfej/print', 'megrendelesfejController#doPrint', 'adminmegrendelesfejprint');
$router->map('GET', '/admin/megrendelesfej/printelolegbekero', 'megrendelesfejController#doPrintelolegbekero', 'adminmegrendelesfejprintelolegbekero');
$router->map('POST', '/admin/megrendelesfej/concat', 'megrendelesfejController#concat', 'adminmegrendelesfejconcat');

$router->map('GET', '/admin/webshopbizfej/viewlist', 'webshopbizfejController#viewlist', 'adminwebshopbizfejviewlist');
$router->map('GET', '/admin/webshopbizfej/getlistbody', 'webshopbizfejController#getlistbody', 'adminwebshopbizfejgetlistbody');
$router->map('GET', '/admin/webshopbizfej/getkarb', 'webshopbizfejController#getkarb', 'adminwebshopbizfejgetkarb');
$router->map('GET', '/admin/webshopbizfej/viewkarb', 'webshopbizfejController#viewkarb', 'adminwebshopbizfejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/webshopbizfej/save', 'webshopbizfejController#save', 'adminwebshopbizfejsave');
    $router->map('POST', '/admin/webshopbizfej/ront', 'webshopbizfejController#ront', 'adminwebshopbizfejront');
    $router->map('POST', '/admin/webshopbizfej/fejexport', 'webshopbizfejController#fejexport', 'adminwebshopbizfejfejexport');
    $router->map('POST', '/admin/webshopbizfej/tetelexport', 'webshopbizfejController#tetelexport', 'adminwebshopbizfejtetelexport');
}
$router->map('GET', '/admin/webshopbizfej/print', 'webshopbizfejController#doPrint', 'adminwebshopbizfejprint');

$router->map('GET', '/admin/szallitofej/viewlist', 'szallitofejController#viewlist', 'adminszallitofejviewlist');
$router->map('GET', '/admin/szallitofej/getlistbody', 'szallitofejController#getlistbody', 'adminszallitofejgetlistbody');
$router->map('GET', '/admin/szallitofej/getkarb', 'szallitofejController#getkarb', 'adminszallitofejgetkarb');
$router->map('GET', '/admin/szallitofej/viewkarb', 'szallitofejController#viewkarb', 'adminszallitofejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/szallitofej/save', 'szallitofejController#save', 'adminszallitofejsave');
    $router->map('POST', '/admin/szallitofej/ront', 'szallitofejController#ront', 'adminszallitofejront');
    $router->map('POST', '/admin/szallitofej/fejexport', 'szallitofejController#fejexport', 'adminszallitofejfejexport');
    $router->map('POST', '/admin/szallitofej/tetelexport', 'szallitofejController#tetelexport', 'adminszallitofejtetelexport');
}
$router->map('GET', '/admin/szallitofej/print', 'szallitofejController#doPrint', 'adminszallitofejprint');

$router->map('GET', '/admin/leltarhianyfej/viewlist', 'leltarhianyfejController#viewlist', 'adminleltarhianyfejviewlist');
$router->map('GET', '/admin/leltarhianyfej/getlistbody', 'leltarhianyfejController#getlistbody', 'adminleltarhianyfejgetlistbody');
$router->map('GET', '/admin/leltarhianyfej/getkarb', 'leltarhianyfejController#getkarb', 'adminleltarhianyfejgetkarb');
$router->map('GET', '/admin/leltarhianyfej/viewkarb', 'leltarhianyfejController#viewkarb', 'adminleltarhianyfejviewkarb');
//$router->map('POST', '/admin/leltarhianyfej/save', 'leltarhianyfejController#save', 'adminleltarhianyfejsave');
$router->map('GET', '/admin/leltarhianyfej/print', 'leltarhianyfejController#doPrint', 'adminleltarhianyfejprint');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/leltarhianyfej/ront', 'leltarhianyfejController#ront', 'adminleltarhianyfejront');
    $router->map('POST', '/admin/leltarhianyfej/fejexport', 'leltarhianyfejController#fejexport', 'adminleltarhianyfejfejexport');
    $router->map('POST', '/admin/leltarhianyfej/tetelexport', 'leltarhianyfejController#tetelexport', 'adminleltarhianyfejtetelexport');
}

$router->map('GET', '/admin/autokiserofej/viewlist', 'autokiserofejController#viewlist', 'adminautokiserofejviewlist');
$router->map('GET', '/admin/autokiserofej/getlistbody', 'autokiserofejController#getlistbody', 'adminautokiserofejgetlistbody');
$router->map('GET', '/admin/autokiserofej/getkarb', 'autokiserofejController#getkarb', 'adminautokiserofejgetkarb');
$router->map('GET', '/admin/autokiserofej/viewkarb', 'autokiserofejController#viewkarb', 'adminautokiserofejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/autokiserofej/save', 'autokiserofejController#save', 'adminautokiserofejsave');
    $router->map('POST', '/admin/autokiserofej/ront', 'autokiserofejController#ront', 'adminautokiserofejront');
    $router->map('POST', '/admin/autokiserofej/fejexport', 'autokiserofejController#fejexport', 'adminautokiserofejfejexport');
    $router->map('POST', '/admin/autokiserofej/tetelexport', 'autokiserofejController#tetelexport', 'adminautokiserofejtetelexport');
}
$router->map('GET', '/admin/autokiserofej/print', 'autokiserofejController#doPrint', 'adminautokiserofejprint');

$router->map('GET', '/admin/garancialevelfej/viewlist', 'garancialevelfejController#viewlist', 'admingarancialevelfejviewlist');
$router->map('GET', '/admin/garancialevelfej/getlistbody', 'garancialevelfejController#getlistbody', 'admingarancialevelfejgetlistbody');
$router->map('GET', '/admin/garancialevelfej/getkarb', 'garancialevelfejController#getkarb', 'admingarancialevelfejgetkarb');
$router->map('GET', '/admin/garancialevelfej/viewkarb', 'garancialevelfejController#viewkarb', 'admingarancialevelfejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/garancialevelfej/save', 'garancialevelfejController#save', 'admingarancialevelfejsave');
    $router->map('POST', '/admin/garancialevelfej/ront', 'garancialevelfejController#ront', 'admingarancialevelfejront');
    $router->map('POST', '/admin/garancialevelfej/fejexport', 'garancialevelfejController#fejexport', 'admingarancialevelfejfejexport');
    $router->map('POST', '/admin/garancialevelfej/tetelexport', 'garancialevelfejController#tetelexport', 'admingarancialevelfejtetelexport');
}
$router->map('GET', '/admin/garancialevelfej/print', 'garancialevelfejController#doPrint', 'admingarancialevelfejprint');

$router->map('GET', '/admin/szamlafej/viewlist', 'szamlafejController#viewlist', 'adminszamlafejviewlist');
$router->map('GET', '/admin/szamlafej/getlistbody', 'szamlafejController#getlistbody', 'adminszamlafejgetlistbody');
$router->map('GET', '/admin/szamlafej/getkarb', 'szamlafejController#getkarb', 'adminszamlafejgetkarb');
$router->map('GET', '/admin/szamlafej/viewkarb', 'szamlafejController#viewkarb', 'adminszamlafejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/szamlafej/fejexport', 'szamlafejController#fejexport', 'adminszamlafejfejexport');
    $router->map('POST', '/admin/szamlafej/tetelexport', 'szamlafejController#tetelexport', 'adminszamlafejtetelexport');
    $router->map('GET', '/admin/szamlafej/navonline', 'szamlafejController#navonline', 'adminszamlafejnavonline');
    $router->map('POST', '/admin/szamlafej/save', 'szamlafejController#save', 'adminszamlafejsave');
    $router->map('GET', '/admin/szamlafej/storno', 'szamlafejController#storno', 'adminszamlafejstorno');
}
$router->map('GET', '/admin/szamlafej/print', 'szamlafejController#doPrint', 'adminszamlafejprint');

$router->map('GET', '/admin/esetiszamlafej/viewlist', 'esetiszamlafejController#viewlist', 'adminesetiszamlafejviewlist');
$router->map('GET', '/admin/esetiszamlafej/getlistbody', 'esetiszamlafejController#getlistbody', 'adminesetiszamlafejgetlistbody');
$router->map('GET', '/admin/esetiszamlafej/getkarb', 'esetiszamlafejController#getkarb', 'adminesetiszamlafejgetkarb');
$router->map('GET', '/admin/esetiszamlafej/viewkarb', 'esetiszamlafejController#viewkarb', 'adminesetiszamlafejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/esetiszamlafej/save', 'esetiszamlafejController#save', 'adminesetiszamlafejsave');
    $router->map('GET', '/admin/esetiszamlafej/storno', 'esetiszamlafejController#storno', 'adminesetiszamlafejstorno');
    $router->map('POST', '/admin/esetiszamlafej/fejexport', 'esetiszamlafejController#fejexport', 'adminesetiszamlafejfejexport');
    $router->map('POST', '/admin/esetiszamlafej/tetelexport', 'esetiszamlafejController#tetelexport', 'adminesetiszamlafejtetelexport');
}
$router->map('GET', '/admin/esetiszamlafej/print', 'esetiszamlafejController#doPrint', 'adminesetiszamlafejprint');

$router->map('GET', '/admin/egyebfej/viewlist', 'egyebmozgasfejController#viewlist', 'adminegyebfejviewlist');
$router->map('GET', '/admin/egyebfej/getlistbody', 'egyebmozgasfejController#getlistbody', 'adminegyebfejgetlistbody');
$router->map('GET', '/admin/egyebfej/getkarb', 'egyebmozgasfejController#getkarb', 'adminegyebfejgetkarb');
$router->map('GET', '/admin/egyebfej/viewkarb', 'egyebmozgasfejController#viewkarb', 'adminegyebfejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/egyebfej/save', 'egyebmozgasfejController#save', 'adminegyebfejsave');
    $router->map('POST', '/admin/egyebfej/ront', 'egyebmozgasfejController#ront', 'adminegyebfejront');
    $router->map('POST', '/admin/egyebfej/fejexport', 'egyebmozgasfejController#fejexport', 'adminegyebfejfejexport');
    $router->map('POST', '/admin/egyebfej/tetelexport', 'egyebmozgasfejController#tetelexport', 'adminegyebfejtetelexport');
}
$router->map('GET', '/admin/egyebfej/print', 'egyebmozgasfejController#doPrint', 'adminegyebfejprint');

$router->map('GET', '/admin/selejtfej/getlistbody', 'selejtfejController#getlistbody', 'adminselejtfejgetlistbody');
$router->map('GET', '/admin/selejtfej/viewlist', 'selejtfejController#viewlist', 'adminselejtfejviewlist');
$router->map('GET', '/admin/selejtfej/getkarb', 'selejtfejController#getkarb', 'adminselejtfejgetkarb');
$router->map('GET', '/admin/selejtfej/viewkarb', 'selejtfejController#viewkarb', 'adminselejtfejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/selejtfej/save', 'selejtfejController#save', 'adminselejtfejsave');
    $router->map('POST', '/admin/selejtfej/ront', 'selejtfejController#ront', 'adminselejtfejront');
    $router->map('POST', '/admin/selejtfej/fejexport', 'selejtfejController#fejexport', 'adminselejtfejfejexport');
    $router->map('POST', '/admin/selejtfej/', 'selejtfejController#', 'adminselejtfej');
}
$router->map('GET', '/admin/selejtfej/print', 'selejtfejController#doPrint', 'adminselejtfejprint');

$router->map('GET', '/admin/csomagfej/viewlist', 'csomagfejController#viewlist', 'admincsomagfejviewlist');
$router->map('GET', '/admin/csomagfej/getlistbody', 'csomagfejController#getlistbody', 'admincsomagfejgetlistbody');
$router->map('GET', '/admin/csomagfej/getkarb', 'csomagfejController#getkarb', 'admincsomagfejgetkarb');
$router->map('GET', '/admin/csomagfej/viewkarb', 'csomagfejController#viewkarb', 'admincsomagfejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/csomagfej/save', 'csomagfejController#save', 'admincsomagfejsave');
    $router->map('POST', '/admin/csomagfej/ront', 'csomagfejController#ront', 'admincsomagfejront');
    $router->map('POST', '/admin/csomagfej/fejexport', 'csomagfejController#fejexport', 'admincsomagfejfejexport');
    $router->map('POST', '/admin/csomagfej/tetelexport', 'csomagfejController#tetelexport', 'admincsomagfejtetelexport');
}
$router->map('GET', '/admin/csomagfej/print', 'csomagfejController#doPrint', 'admincsomagfejprint');

$router->map('GET', '/admin/keziszamlafej/viewlist', 'keziszamlafejController#viewlist', 'adminkeziszamlafejviewlist');
$router->map('GET', '/admin/keziszamlafej/getlistbody', 'keziszamlafejController#getlistbody', 'adminkeziszamlafejgetlistbody');
$router->map('GET', '/admin/keziszamlafej/getkarb', 'keziszamlafejController#getkarb', 'adminkeziszamlafejgetkarb');
$router->map('GET', '/admin/keziszamlafej/viewkarb', 'keziszamlafejController#viewkarb', 'adminkeziszamlafejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/keziszamlafej/save', 'keziszamlafejController#save', 'adminkeziszamlafejsave');
    $router->map('POST', '/admin/keziszamlafej/ront', 'keziszamlafejController#ront', 'adminkeziszamlafejront');
    $router->map('POST', '/admin/keziszamlafej/fejexport', 'keziszamlafejController#fejexport', 'adminkeziszamlafejfejexport');
    $router->map('POST', '/admin/keziszamlafej/tetelexport', 'keziszamlafejController#tetelexport', 'adminkeziszamlafejtetelexport');
}
$router->map('GET', '/admin/keziszamlafej/print', 'keziszamlafejController#doPrint', 'adminkeziszamlafejprint');

$router->map('GET', '/admin/garanciaugyfej/viewlist', 'garanciaugyfejController#viewlist', 'admingaranciaugyfejviewlist');
$router->map('GET', '/admin/garanciaugyfej/getlistbody', 'garanciaugyfejController#getlistbody', 'admingaranciaugyfejgetlistbody');
$router->map('GET', '/admin/garanciaugyfej/getkarb', 'garanciaugyfejController#getkarb', 'admingaranciaugyfejgetkarb');
$router->map('GET', '/admin/garanciaugyfej/viewkarb', 'garanciaugyfejController#viewkarb', 'admingaranciaugyfejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/garanciaugyfej/save', 'garanciaugyfejController#save', 'admingaranciaugyfejsave');
    $router->map('POST', '/admin/garanciaugyfej/ront', 'garanciaugyfejController#ront', 'admingaranciaugyfejront');
    $router->map('POST', '/admin/garanciaugyfej/fejexport', 'garanciaugyfejController#fejexport', 'admingaranciaugyfejfejexport');
    $router->map('POST', '/admin/garanciaugyfej/tetelexport', 'garanciaugyfejController#tetelexport', 'admingaranciaugyfejtetelexport');
}
$router->map('GET', '/admin/garanciaugyfej/print', 'garanciaugyfejController#doPrint', 'admingaranciaugyfejprint');

$router->map('GET', '/admin/szallmegrfej/viewlist', 'szallmegrfejController#viewlist', 'adminszallmegrfejviewlist');
$router->map('GET', '/admin/szallmegrfej/getlistbody', 'szallmegrfejController#getlistbody', 'adminszallmegrfejgetlistbody');
$router->map('GET', '/admin/szallmegrfej/getkarb', 'szallmegrfejController#getkarb', 'adminszallmegrfejgetkarb');
$router->map('GET', '/admin/szallmegrfej/viewkarb', 'szallmegrfejController#viewkarb', 'adminszallmegrfejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/szallmegrfej/save', 'szallmegrfejController#save', 'adminszallmegrfejsave');
    $router->map('POST', '/admin/szallmegrfej/ront', 'szallmegrfejController#ront', 'adminszallmegrfejront');
    $router->map('POST', '/admin/szallmegrfej/fejexport', 'szallmegrfejController#fejexport', 'adminszallmegrfejfejexport');
    $router->map('POST', '/admin/szallmegrfej/tetelexport', 'szallmegrfejController#tetelexport', 'adminszallmegrfejtetelexport');
}
$router->map('GET', '/admin/szallmegrfej/print', 'szallmegrfejController#doPrint', 'adminszallmegrfejprint');

$router->map('GET', '/admin/bevetfej/viewlist', 'bevetfejController#viewlist', 'adminbevetfejviewlist');
$router->map('GET', '/admin/bevetfej/getlistbody', 'bevetfejController#getlistbody', 'adminbevetfejgetlistbody');
$router->map('GET', '/admin/bevetfej/getkarb', 'bevetfejController#getkarb', 'adminbevetfejgetkarb');
$router->map('GET', '/admin/bevetfej/viewkarb', 'bevetfejController#viewkarb', 'adminbevetfejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/bevetfej/save', 'bevetfejController#save', 'adminbevetfejsave');
    $router->map('POST', '/admin/bevetfej/ront', 'bevetfejController#ront', 'adminbevetfejront');
    $router->map('POST', '/admin/bevetfej/fejexport', 'bevetfejController#fejexport', 'adminbevetfejfejexport');
    $router->map('POST', '/admin/bevetfej/tetelexport', 'bevetfejController#tetelexport', 'adminbevetfejtetelexport');
}
$router->map('GET', '/admin/bevetfej/print', 'bevetfejController#doPrint', 'adminbevetfejprint');

$router->map('GET', '/admin/leltartobbletfej/viewlist', 'leltartobbletfejController#viewlist', 'adminleltartobbletfejviewlist');
$router->map('GET', '/admin/leltartobbletfej/getlistbody', 'leltartobbletfejController#getlistbody', 'adminleltartobbletfejgetlistbody');
$router->map('GET', '/admin/leltartobbletfej/getkarb', 'leltartobbletfejController#getkarb', 'adminleltartobbletfejgetkarb');
$router->map('GET', '/admin/leltartobbletfej/viewkarb', 'leltartobbletfejController#viewkarb', 'adminleltartobbletfejviewkarb');
//$router->map('POST', '/admin/leltartobbletfej/save', 'leltartobbletfejController#save', 'adminleltartobbletfejsave');
$router->map('GET', '/admin/leltartobbletfej/print', 'leltartobbletfejController#doPrint', 'adminleltartobbletfejprint');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/leltartobbletfej/ront', 'leltartobbletfejController#ront', 'adminleltartobbletfejront');
    $router->map('POST', '/admin/leltartobbletfej/fejexport', 'leltartobbletfejController#fejexport', 'adminleltartobbletfejfejexport');
    $router->map('POST', '/admin/leltartobbletfej/tetelexport', 'leltartobbletfejController#tetelexport', 'adminleltartobbletfejtetelexport');
}

$router->map('GET', '/admin/koltsegszamlafej/viewlist', 'koltsegszamlafejController#viewlist', 'adminkoltsegszamlafejviewlist');
$router->map('GET', '/admin/koltsegszamlafej/getlistbody', 'koltsegszamlafejController#getlistbody', 'adminkoltsegszamlafejgetlistbody');
$router->map('GET', '/admin/koltsegszamlafej/getkarb', 'koltsegszamlafejController#getkarb', 'adminkoltsegszamlafejgetkarb');
$router->map('GET', '/admin/koltsegszamlafej/viewkarb', 'koltsegszamlafejController#viewkarb', 'adminkoltsegszamlafejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/koltsegszamlafej/save', 'koltsegszamlafejController#save', 'adminkoltsegszamlafejsave');
    $router->map('POST', '/admin/koltsegszamlafej/ront', 'koltsegszamlafejController#ront', 'adminkoltsegszamlafejront');
    $router->map('POST', '/admin/koltsegszamlafej/fejexport', 'koltsegszamlafejController#fejexport', 'adminkoltsegszamlafejfejexport');
    $router->map('POST', '/admin/koltsegszamlafej/tetelexport', 'koltsegszamlafejController#tetelexport', 'adminkoltsegszamlafejtetelexport');
}
$router->map('GET', '/admin/koltsegszamlafej/print', 'koltsegszamlafejController#doPrint', 'adminkoltsegszamlafejprint');

$router->map('GET', '/admin/kivetfej/viewlist', 'kivetfejController#viewlist', 'adminkivetfejviewlist');
$router->map('GET', '/admin/kivetfej/getlistbody', 'kivetfejController#getlistbody', 'adminkivetfejgetlistbody');
$router->map('GET', '/admin/kivetfej/getkarb', 'kivetfejController#getkarb', 'adminkivetfejgetkarb');
$router->map('GET', '/admin/kivetfej/viewkarb', 'kivetfejController#viewkarb', 'adminkivetfejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/kivetfej/save', 'kivetfejController#save', 'adminkivetfejsave');
    $router->map('POST', '/admin/kivetfej/ront', 'kivetfejController#ront', 'adminkivetfejront');
    $router->map('POST', '/admin/kivetfej/fejexport', 'kivetfejController#fejexport', 'adminkivetfejfejexport');
    $router->map('POST', '/admin/kivetfej/tetelexport', 'kivetfejController#tetelexport', 'adminkivetfejtetelexport');
}
$router->map('GET', '/admin/kivetfej/print', 'kivetfejController#doPrint', 'adminkivetfejprint');

$router->map('GET', '/admin/bizsablonfej/viewlist', 'bizsablonfejController#viewlist', 'adminbizsablonfejviewlist');
$router->map('GET', '/admin/bizsablonfej/getlistbody', 'bizsablonfejController#getlistbody', 'adminbizsablonfejgetlistbody');
$router->map('GET', '/admin/bizsablonfej/getkarb', 'bizsablonfejController#getkarb', 'adminbizsablonfejgetkarb');
$router->map('GET', '/admin/bizsablonfej/viewkarb', 'bizsablonfejController#viewkarb', 'adminbizsablonfejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/bizsablonfej/save', 'bizsablonfejController#save', 'adminbizsablonfejsave');
    $router->map('POST', '/admin/bizsablonfej/ront', 'bizsablonfejController#ront', 'adminbizsablonfejront');
    $router->map('POST', '/admin/bizsablonfej/fejexport', 'bizsablonfejController#fejexport', 'adminbizsablonfejfejexport');
    $router->map('POST', '/admin/bizsablonfej/tetelexport', 'bizsablonfejController#tetelexport', 'adminbizsablonfejtetelexport');
    $router->map('GET', '/admin/bizsablonfej/navonline', 'bizsablonfejController#navonline', 'adminbizsablonfejnavonline');
}
$router->map('GET', '/admin/bizsablonfej/print', 'bizsablonfejController#doPrint', 'adminbizsablonfejprint');

$router->map('GET', '/admin/kolcsonzesfej/viewlist', 'kolcsonzesfejController#viewlist', 'adminkolcsonzesfejviewlist');
$router->map('GET', '/admin/kolcsonzesfej/getlistbody', 'kolcsonzesfejController#getlistbody', 'adminkolcsonzesfejgetlistbody');
$router->map('GET', '/admin/kolcsonzesfej/getkarb', 'kolcsonzesfejController#getkarb', 'adminkolcsonzesfejgetkarb');
$router->map('GET', '/admin/kolcsonzesfej/viewkarb', 'kolcsonzesfejController#viewkarb', 'adminkolcsonzesfejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/kolcsonzesfej/save', 'kolcsonzesfejController#save', 'adminkolcsonzesfejsave');
    $router->map('POST', '/admin/kolcsonzesfej/ront', 'kolcsonzesfejController#ront', 'adminkolcsonzesfejront');
    $router->map('POST', '/admin/kolcsonzesfej/fejexport', 'kolcsonzesfejController#fejexport', 'adminkolcsonzesfejfejexport');
    $router->map('POST', '/admin/kolcsonzesfej/tetelexport', 'kolcsonzesfejController#tetelexport', 'adminkolcsonzesfejtetelexport');
}
$router->map('GET', '/admin/kolcsonzesfej/print', 'kolcsonzesfejController#doPrint', 'adminkolcsonzesfejprint');

$router->map('GET', '/admin/bizonylatdok/getemptyrow', 'bizonylatdokController#getemptyrow', 'adminbizonylatdokgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/bizonylatdok/del', 'bizonylatdokController#del', 'adminbizonylatdokdel');
}

$router->map('GET', '/admin/mnrstatic/viewlist', 'mnrstaticController#viewlist', 'adminmnrstaticviewlist');
$router->map('GET', '/admin/mnrstatic/htmllist', 'mnrstaticController#htmllist', 'adminmnrstatichtmllist');
$router->map('GET', '/admin/mnrstatic/getlistbody', 'mnrstaticController#getlistbody', 'adminmnrstaticgetlistbody');
$router->map('GET', '/admin/mnrstatic/getkarb', 'mnrstaticController#getkarb', 'adminmnrstaticgetkarb');
$router->map('GET', '/admin/mnrstatic/viewkarb', 'mnrstaticController#viewkarb', 'adminmnrstaticviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/mnrstatic/save', 'mnrstaticController#save', 'adminmnrstaticsave');
}

$router->map('GET', '/admin/mnrstaticpagekep/getemptyrow', 'mnrstaticpagekepController#getemptyrow', 'adminmnrstatickepgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/mnrstaticpagekep/del', 'mnrstaticpagekepController#del', 'adminmnrstaticpagekepdel');
}

$router->map('GET', '/admin/mnrstatictranslation/getemptyrow', 'mnrstatictranslationController#getemptyrow', 'adminmnrstatictranslationgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/mnrstatictranslation/save', 'mnrstatictranslationController#save', 'adminmnrstatictranslationsave');
}

$router->map('GET', '/admin/mnrstaticpage/getemptyrow', 'mnrstaticpageController#getemptyrow', 'adminmnrstaticpagegetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/mnrstaticpage/save', 'mnrstaticpageController#save', 'adminmnrstaticpagesave');
    $router->map('POST', '/admin/mnrstaticpage/delall', 'mnrstaticpageController#delall', 'adminmnrstaticpagedelall');
}

$router->map(
    'GET',
    '/admin/mnrstaticpagetranslation/getemptyrow',
    'mnrstaticpagetranslationController#getemptyrow',
    'adminmnrstaticpagetranslationgetemptyrow'
);
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/mnrstaticpagetranslation/save', 'mnrstaticpagetranslationController#save', 'adminmnrstaticpagetranslationsave');
}

$router->map('GET', '/admin/mnrnavigation/viewlist', 'mnrnavigationController#viewlist', 'adminmnrnavigationviewlist');
$router->map('GET', '/admin/mnrnavigation/htmllist', 'mnrnavigationController#htmllist', 'adminmnrnavigationhtmllist');
$router->map('GET', '/admin/mnrnavigation/getlistbody', 'mnrnavigationController#getlistbody', 'adminmnrnavigationgetlistbody');
$router->map('GET', '/admin/mnrnavigation/getkarb', 'mnrnavigationController#getkarb', 'adminmnrnavigationgetkarb');
$router->map('GET', '/admin/mnrnavigation/viewkarb', 'mnrnavigationController#viewkarb', 'adminmnrnavigationviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/mnrnavigation/save', 'mnrnavigationController#save', 'adminmnrnavigationsave');
}
$router->map(
    'GET',
    '/admin/mnrnavigationtranslation/getemptyrow',
    'mnrnavigationtranslationController#getemptyrow',
    'adminmnrnavigationtranslationgetemptyrow'
);
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/mnrnavigationtranslation/save', 'mnrnavigationtranslationController#save', 'adminmnrnavigationtranslationsave');
}

$router->map('GET', '/admin/mnrlanding/viewlist', 'mnrlandingController#viewlist', 'adminmnrlandingviewlist');
$router->map('GET', '/admin/mnrlanding/htmllist', 'mnrlandingController#htmllist', 'adminmnrlandinghtmllist');
$router->map('GET', '/admin/mnrlanding/getlistbody', 'mnrlandingController#getlistbody', 'adminmnrlandinggetlistbody');
$router->map('GET', '/admin/mnrlanding/getkarb', 'mnrlandingController#getkarb', 'adminmnrlandinggetkarb');
$router->map('GET', '/admin/mnrlanding/viewkarb', 'mnrlandingController#viewkarb', 'adminmnrlandingviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/mnrlanding/save', 'mnrlandingController#save', 'adminmnrlandingsave');
}

$router->map('GET', '/admin/mnrlandingtranslation/getemptyrow', 'mnrlandingtranslationController#getemptyrow', 'adminmnrlandingtranslationgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/mnrlandingtranslation/save', 'mnrlandingtranslationController#save', 'adminmnrlandingtranslationsave');
}

$router->map('GET', '/admin/termek/viewlist', 'termekController#viewlist', 'admintermekviewlist');
$router->map('GET', '/admin/termek/htmllist', 'termekController#htmllist', 'admintermekhtmllist');
$router->map('GET', '/admin/termek/getlistbody', 'termekController#getlistbody', 'admintermekgetlistbody');
$router->map('GET', '/admin/termek/getkarb', 'termekController#getkarb', 'admintermekgetkarb');
$router->map('GET', '/admin/termek/viewkarb', 'termekController#viewkarb', 'admintermekviewkarb');
$router->map('GET', '/admin/termek/getnetto', 'termekController#getnetto', 'admintermekgetnetto');
$router->map('GET', '/admin/termek/getbrutto', 'termekController#getbrutto', 'admintermekgetbrutto');
$router->map('GET', '/admin/termek/arexport', 'termekController#arexport', 'admintermekarexport');
$router->map('GET', '/admin/termek/fcmotoexport', 'termekController#fcmotoexport', 'admintermekfcmotoexport');
$router->map('GET', '/admin/termek/gs1export', 'termekController#gs1export', 'admintermekgs1export');
$router->map('GET', '/admin/termek/colorexport', 'termekController#colorexport', 'admintermekcolorexport');
$router->map('GET', '/admin/termek/cikkszamosexport', 'termekController#cikkszamosexport', 'admintermekcikkszamosexport');
$router->map('GET', '/admin/termek/getkeszletbyraktar', 'termekController#getKeszletByRaktar', 'admingetkeszletbyraktar');
$router->map('GET', '/admin/termek/getkapcsolodolist', 'termekController#getKapcsolodoSelectList', 'admingettermekkapcsolodolist');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/termek/save', 'termekController#save', 'admintermeksave');
    $router->map('POST', '/admin/termek/setflag', 'termekController#setflag', 'admintermeksetflag');
    $router->map('POST', '/admin/termek/tcsset', 'termekController#setTermekcsoport', 'admintermektcsset');
    $router->map('POST', '/admin/nepszeruseg/clear', 'termekController#clearNepszeruseg', 'adminclearnepszeruseg');
}

$router->map('GET', '/admin/termekkapcsolodo/getemptyrow', 'termekkapcsolodoController#getemptyrow', 'admintermekkapcsolodogetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/termekkapcsolodo/save', 'termekkapcsolodoController#save', 'admintermekkapcsolodosave');
}

$router->map('GET', '/admin/termekar/getemptyrow', 'termekarController#getemptyrow', 'admintermekargetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/termekar/save', 'termekarController#save', 'admintermekarsave');
}

$router->map(
    'GET',
    '/admin/partnertermekcsoportkedvezmeny/getemptyrow',
    'partnertermekcsoportkedvezmenyController#getemptyrow',
    'adminpartnertermekcsoportkedvezmenygetemptyrow'
);
if (!\mkw\store::isClosed()) {
    $router->map(
        'POST',
        '/admin/partnertermekcsoportkedvezmeny/save',
        'partnertermekcsoportkedvezmenyController#save',
        'adminpartnertermekcsoportkedvezmenysave'
    );
}

$router->map('GET', '/admin/partnertermekkedvezmeny/getemptyrow', 'partnertermekkedvezmenyController#getemptyrow', 'adminpartnertermekkedvezmenygetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/partnertermekkedvezmeny/save', 'partnertermekkedvezmenyController#save', 'adminpartnertermekkedvezmenysave');

    $router->map('GET', '/admin/partnertermekkedvezmenyupload/view', 'partnertermekkedvezmenyuploadController#view', 'adminpartnertermekkedvezmenyuploadview');
    $router->map('POST', '/admin/partnertermekkedvezmenyupload/del', 'partnertermekkedvezmenyuploadController#del', 'adminpartnertermekkedvezmenyuploaddel');
    $router->map(
        'POST',
        '/admin/partnertermekkedvezmenyupload/upload',
        'partnertermekkedvezmenyuploadController#upload',
        'adminpartnertermekkedvezmenyuploadupload'
    );
}

$router->map('GET', '/admin/partnermijszoklevel/getemptyrow', 'partnermijszoklevelController#getemptyrow', 'adminpartnermijszoklevelgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/partnermijszoklevel/save', 'partnermijszoklevelController#save', 'adminpartnermijszoklevelsave');
}

$router->map('GET', '/admin/termektranslation/getemptyrow', 'termektranslationController#getemptyrow', 'admintermektranslationgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/termektranslation/save', 'termektranslationController#save', 'admintermektranslationsave');
}

$router->map('GET', '/admin/termekfatranslation/getemptyrow', 'termekfatranslationController#getemptyrow', 'admintermekfatranslationgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/termekfatranslation/save', 'termekfatranslationController#save', 'admintermekfatranslationsave');
}

$router->map('GET', '/admin/termekmenutranslation/getemptyrow', 'termekmenutranslationController#getemptyrow', 'admintermekmenutranslationgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/termekmenutranslation/save', 'termekmenutranslationController#save', 'admintermekmenutranslationsave');
}

$router->map('GET', '/admin/statlaptranslation/getemptyrow', 'statlaptranslationController#getemptyrow', 'adminstatlaptranslationgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/statlaptranslation/save', 'statlaptranslationController#save', 'adminstatlaptranslationsave');
}

$router->map('GET', '/admin/fizmodtranslation/getemptyrow', 'fizmodtranslationController#getemptyrow', 'adminfizmodtranslationgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/fizmodtranslation/save', 'fizmodtranslationController#save', 'adminfizmodtranslationsave');
}

$router->map(
    'GET',
    '/admin/szallitasimodtranslation/getemptyrow',
    'szallitasimodtranslationController#getemptyrow',
    'adminszallitasimodtranslationgetemptyrow'
);
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/szallitasimodtranslation/save', 'szallitasimodtranslationController#save', 'adminszallitasimodtranslationsave');
}

$router->map('GET', '/admin/termekkep/getemptyrow', 'termekkepController#getemptyrow', 'admintermekkepgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/termekkep/del', 'termekkepController#del', 'admintermekkepdel');
}

$router->map('GET', '/admin/termekrecept/getemptyrow', 'termekreceptController#getemptyrow', 'admintermekreceptgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/termekrecept/save', 'termekreceptController#save', 'admintermekreceptsave');
}

$router->map('GET', '/admin/termekdok/getemptyrow', 'termekdokController#getemptyrow', 'admintermekdokgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/termekdok/del', 'termekdokController#del', 'admintermekdokdel');
}

$router->map('GET', '/admin/jogareszvetel/getemptyrow', 'jogareszvetelController#getemptyrow', 'adminjogareszvetelgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/jogareszvetel/del', 'jogareszvetelController#del', 'adminjogareszveteldel');
    $router->map('POST', '/admin/jogareszvetel/save', 'jogareszvetelController#save', 'adminjogareszvetelsave');
    $router->map('POST', '/admin/jogareszvetel/quicksave', 'jogareszvetelController#quicksave', 'adminjogareszvetelquicksave');
}
$router->map('GET', '/admin/jogareszvetel/getar', 'jogareszvetelController#getar', 'adminjogareszvetelgetar');
$router->map('GET', '/admin/jogareszvetel/viewlist', 'jogareszvetelController#viewlist', 'adminjogareszvetelviewlist');
$router->map('GET', '/admin/jogareszvetel/getlistbody', 'jogareszvetelController#getlistbody', 'adminjogareszvetelgetlistbody');

$router->map('GET', '/admin/termekvaltozat/getemptyrow', 'termekvaltozatController#getemptyrow', 'admintermekvaltozatgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/termekvaltozat/generate', 'termekvaltozatController#generate', 'admintermekvaltozatgenerate');
    $router->map('POST', '/admin/termekvaltozat/save', 'termekvaltozatController#save', 'admintermekvaltozatsave');
    $router->map('POST', '/admin/termekvaltozat/delall', 'termekvaltozatController#delall', 'admintermekvaltozatdelall');
}
$router->map('GET', '/admin/termekvaltozat/getkeszletbyraktar', 'termekvaltozatController#getKeszletByRaktar', 'admingetvaltozatkeszletbyraktar');

$router->map('GET', '/admin/szallitasimod/viewlist', 'szallitasimodController#viewlist', 'adminszallitasimodviewlist');
$router->map('GET', '/admin/szallitasimod/getlistbody', 'szallitasimodController#getlistbody', 'adminszallitasimodgetlistbody');
$router->map('GET', '/admin/szallitasimod/getkarb', 'szallitasimodController#getkarb', 'adminszallitasimodgetkarb');
$router->map('GET', '/admin/szallitasimod/viewkarb', 'szallitasimodController#viewkarb', 'adminszallitasimodviewkarb');
$router->map('GET', '/admin/szallitasimod/htmllist', 'szallitasimodController#htmllist', 'adminszallitasimodhtmllist');
$router->map('GET', '/admin/szallitasimodhatar/getemptyrow', 'szallitasimodhatarController#getemptyrow', 'adminszallitasimodhatargetemptyrow');
$router->map('GET', '/admin/szallitasimodorszag/getemptyrow', 'szallitasimodorszagController#getemptyrow', 'adminszallitasimodorszaggetemptyrow');
$router->map(
    'GET',
    '/admin/szallitasimodfizmodnovelo/getemptyrow',
    'szallitasimodfizmodnoveloController#getemptyrow',
    'adminszallitasimodfizmodnovelogetemptyrow'
);
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/szallitasimod/save', 'szallitasimodController#save', 'adminszallitasimodsave');
    $router->map('POST', '/admin/szallitasimodhatar/save', 'szallitasimodhatarController#save', 'adminszallitasimodhatarsave');
    $router->map('POST', '/admin/szallitasimodorszag/save', 'szallitasimodorszagController#save', 'adminszallitasimodorszagsave');
    $router->map('POST', '/admin/szallitasimodfizmodnovelo/save', 'szallitasimodfizmodnoveloController#save', 'adminszallitasimodfizmodnovelosave');
}

$router->map('GET', '/admin/fizetesimod/viewlist', 'fizmodController#viewlist', 'adminfizetesimodviewlist');
$router->map('GET', '/admin/fizetesimod/getlistbody', 'fizmodController#getlistbody', 'adminfizetesimodgetlistbody');
$router->map('GET', '/admin/fizetesimod/getkarb', 'fizmodController#getkarb', 'adminfizetesimodgetkarb');
$router->map('GET', '/admin/fizetesimod/viewkarb', 'fizmodController#viewkarb', 'adminfizetesimodviewkarb');
$router->map('GET', '/admin/fizetesimod/htmllist', 'fizmodController#htmllist', 'adminfizetesimodhtmllist');
$router->map('GET', '/admin/fizmodhatar/getemptyrow', 'fizmodhatarController#getemptyrow', 'adminfizmodhatargetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/fizetesimod/save', 'fizmodController#save', 'adminfizetesimodsave');
    $router->map('POST', '/admin/fizmodhatar/save', 'fizmodhatarController#save', 'adminfizmodhatarsave');
}

$router->map('GET', '/admin/banktranzakcio/viewlist', 'banktranzakcioController#viewlist', 'adminbanktranzakcioviewlist');
$router->map('GET', '/admin/banktranzakcio/getlistbody', 'banktranzakcioController#getlistbody', 'adminbanktranzakciogetlistbody');
$router->map('GET', '/admin/banktranzakcio/getkarb', 'banktranzakcioController#getkarb', 'adminbanktranzakciogetkarb');
$router->map('GET', '/admin/banktranzakcio/viewkarb', 'banktranzakcioController#viewkarb', 'adminbanktranzakcioviewkarb');
$router->map('GET', '/admin/banktranzakcio/viewupload', 'banktranzakcioController#viewUpload', 'adminbanktranzakcioviewupload');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/banktranzakcio/save', 'banktranzakcioController#save', 'adminbanktranzakciosave');
    $router->map('POST', '/admin/banktranzakcio/upload', 'banktranzakcioController#upload', 'adminbanktranzakcioupload');
    $router->map(
        'POST',
        '/admin/banktranzakcio/generatebankbizonylat',
        'banktranzakcioController#generateBankbizonylat',
        'adminbanktranzakciogeneratebankbizonylat'
    );
}

$router->map('GET', '/admin/emailtemplate/viewlist', 'emailtemplateController#viewlist', 'adminemailtemplateviewlist');
$router->map('GET', '/admin/emailtemplate/getlistbody', 'emailtemplateController#getlistbody', 'adminemailtemplategetlistbody');
$router->map('GET', '/admin/emailtemplate/getkarb', 'emailtemplateController#getkarb', 'adminemailtemplategetkarb');
$router->map('GET', '/admin/emailtemplate/viewkarb', 'emailtemplateController#viewkarb', 'adminemailtemplateviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/emailtemplate/save', 'emailtemplateController#save', 'adminemailtemplatesave');
}

$router->map('GET', '/admin/dolgozo/viewlist', 'dolgozoController#viewlist', 'admindolgozoviewlist');
$router->map('GET', '/admin/dolgozo/getlistbody', 'dolgozoController#getlistbody', 'admindolgozogetlistbody');
$router->map('GET', '/admin/dolgozo/getkarb', 'dolgozoController#getkarb', 'admindolgozogetkarb');
$router->map('GET', '/admin/dolgozo/viewkarb', 'dolgozoController#viewkarb', 'admindolgozoviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/dolgozo/save', 'dolgozoController#save', 'admindolgozosave');
    $router->map('POST', '/admin/dolgozo/sendemailsablonok', 'dolgozoController#sendEmailSablonok', 'admindolgozosendemailsablonok');
}

$router->map('GET', '/admin/esemeny/viewlist', 'esemenyController#viewlist', 'adminesemenyviewlist');
$router->map('GET', '/admin/esemeny/getlistbody', 'esemenyController#getlistbody', 'adminesemenygetlistbody');
$router->map('GET', '/admin/esemeny/getkarb', 'esemenyController#getkarb', 'adminesemenygetkarb');
$router->map('GET', '/admin/esemeny/viewkarb', 'esemenyController#viewkarb', 'adminesemenyviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/esemeny/save', 'esemenyController#save', 'adminesemenysave');
}

$router->map('GET', '/admin/teendo/viewlist', 'teendoController#viewlist', 'adminteendoviewlist');
$router->map('GET', '/admin/teendo/getlistbody', 'teendoController#getlistbody', 'adminteendogetlistbody');
$router->map('GET', '/admin/teendo/getkarb', 'teendoController#getkarb', 'adminteendogetkarb');
$router->map('GET', '/admin/teendo/viewkarb', 'teendoController#viewkarb', 'adminteendoviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/teendo/save', 'teendoController#save', 'adminteendosave');
    $router->map('POST', '/admin/teendo/setflag', 'teendoController#setflag', 'adminteendossetflag');
}

$router->map('GET', '/admin/hir/viewlist', 'hirController#viewlist', 'adminhirviewlist');
$router->map('GET', '/admin/hir/getlistbody', 'hirController#getlistbody', 'adminhirgetlistbody');
$router->map('GET', '/admin/hir/getkarb', 'hirController#getkarb', 'adminhirgetkarb');
$router->map('GET', '/admin/hir/viewkarb', 'hirController#viewkarb', 'adminhirviewkarb');
//$router->map('GET', '/admin/hir/gethirlist', 'hirController#gethirlist', 'adminhirgethirlist');
$router->map('GET', '/admin/hir/getfeedhirlist', 'hirController#getfeedhirlist', 'adminhirgetfeedhirlist');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/hir/save', 'hirController#save', 'adminhirsave');
    $router->map('POST', '/admin/hir/setlathato', 'hirController#setlathato', 'adminhirsetlathato');
}

$router->map('GET', '/admin/jelenletiiv/viewlist', 'jelenletiivController#viewlist', 'adminjelenletiivviewlist');
$router->map('GET', '/admin/jelenletiiv/getlistbody', 'jelenletiivController#getlistbody', 'adminjelenletiivgetlistbody');
$router->map('GET', '/admin/jelenletiiv/getkarb', 'jelenletiivController#getkarb', 'adminjelenletiivgetkarb');
$router->map('GET', '/admin/jelenletiiv/viewkarb', 'jelenletiivController#viewkarb', 'adminjelenletiivviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/jelenletiiv/save', 'jelenletiivController#save', 'adminjelenletiivsave');
    $router->map('POST', '/admin/jelenletiiv/generatenapi', 'jelenletiivController#generatenapi', 'adminjelenletiivgeneratenapi');
}

$router->map('GET', '/admin/keresoszolog/viewlist', 'keresoszologController#viewlist', 'adminkeresoszologviewlist');
$router->map('GET', '/admin/keresoszolog/getlistbody', 'keresoszologController#getlistbody', 'adminkeresoszologgetlistbody');
$router->map('GET', '/admin/keresoszolog/getkarb', 'keresoszologController#getkarb', 'adminkeresoszologgetkarb');
$router->map('GET', '/admin/keresoszolog/viewkarb', 'keresoszologController#viewkarb', 'adminkeresoszologviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/keresoszolog/save', 'keresoszologController#save', 'adminkeresoszologsave');
}

$router->map('GET', '/admin/statlap/viewlist', 'statlapController#viewlist', 'adminstatlapviewlist');
$router->map('GET', '/admin/statlap/getlistbody', 'statlapController#getlistbody', 'adminstatlapgetlistbody');
$router->map('GET', '/admin/statlap/getkarb', 'statlapController#getkarb', 'adminstatlapgetkarb');
$router->map('GET', '/admin/statlap/viewkarb', 'statlapController#viewkarb', 'adminstatlapviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/statlap/save', 'statlapController#save', 'adminstatlapsave');
}

$router->map('GET', '/admin/blogposzt/viewlist', 'blogposztController#viewlist', 'adminblogposztviewlist');
$router->map('GET', '/admin/blogposzt/getlistbody', 'blogposztController#getlistbody', 'adminblogposztgetlistbody');
$router->map('GET', '/admin/blogposzt/getkarb', 'blogposztController#getkarb', 'adminblogposztgetkarb');
$router->map('GET', '/admin/blogposzt/viewkarb', 'blogposztController#viewkarb', 'adminblogposztviewkarb');
$router->map('GET', '/admin/blogposzttermek/getemptyrow', 'blogposztController#getTermekEmptyrow', 'adminblogposzttermekgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/blogposzt/save', 'blogposztController#save', 'adminblogposztsave');
    $router->map('POST', '/admin/blogposzt/setflag', 'blogposztController#setflag', 'adminblogposztsetflag');
    $router->map('POST', '/admin/blogposzttermek/del', 'blogposztController#removeTermek', 'adminblogposzttermekdel');
}

$router->map('GET', '/admin/popup/viewlist', 'popupController#viewlist', 'adminpopupviewlist');
$router->map('GET', '/admin/popup/getlistbody', 'popupController#getlistbody', 'adminpopupgetlistbody');
$router->map('GET', '/admin/popup/getkarb', 'popupController#getkarb', 'adminpopupgetkarb');
$router->map('GET', '/admin/popup/viewkarb', 'popupController#viewkarb', 'adminpopupviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/popup/save', 'popupController#save', 'adminpopupsave');
    $router->map('POST', '/admin/popup/setflag', 'popupController#setflag', 'adminpopupsetflag');
    $router->map('POST', '/admin/popup/regenerateid', 'popupController#regenerateid', 'adminpopupregenerateid');
    $router->map('GET', '/admin/popup/getpopupteszt', 'popupController#getpopupteszt', 'adminpopupgetpopupteszt');
}

$router->map('GET', '/admin/template/viewlist', 'templateController#viewlist', 'admintemplateviewlist');
$router->map('GET', '/admin/template/getlistbody', 'templateController#getlistbody', 'admintemplategetlistbody');
$router->map('GET', '/admin/template/getkarb', 'templateController#getkarb', 'admintemplategetkarb');
$router->map('GET', '/admin/template/viewkarb', 'templateController#viewkarb', 'admintemplateviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/template/save', 'templateController#save', 'admintemplatesave');
}

$router->map('GET', '/admin/feketelista/viewlist', 'feketelistaController#viewlist', 'adminfeketelistaviewlist');
$router->map('GET', '/admin/feketelista/getlistbody', 'feketelistaController#getlistbody', 'adminfeketelistagetlistbody');
$router->map('GET', '/admin/feketelista/getkarb', 'feketelistaController#getkarb', 'adminfeketelistagetkarb');
$router->map('GET', '/admin/feketelista/viewkarb', 'feketelistaController#viewkarb', 'adminfeketelistaviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/feketelista/save', 'feketelistaController#save', 'adminfeketelistasave');
    $router->map('POST', '/admin/feketelista/add', 'feketelistaController#add', 'adminfeketelistaadd');
}

$router->map('GET', '/admin/leltarfej/viewlist', 'leltarfejController#viewlist', 'adminleltarfejviewlist');
$router->map('GET', '/admin/leltarfej/getlistbody', 'leltarfejController#getlistbody', 'adminleltarfejgetlistbody');
$router->map('GET', '/admin/leltarfej/getkarb', 'leltarfejController#getkarb', 'adminleltarfejgetkarb');
$router->map('GET', '/admin/leltarfej/viewkarb', 'leltarfejController#viewkarb', 'adminleltarfejviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/leltarfej/save', 'leltarfejController#save', 'adminleltarfejsave');
    $router->map('GET', '/admin/leltarfej/viewexport', 'leltarfejController#viewExport', 'adminleltarfejviewexport');
    $router->map('GET', '/admin/leltarfej/export', 'leltarfejController#export', 'adminleltarfejexport');
    $router->map('GET', '/admin/leltarfej/viewimport', 'leltarfejController#viewImport', 'adminleltarfejviewimport');
    $router->map('POST', '/admin/leltarfej/import', 'leltarfejController#import', 'adminleltarfejimport');
    $router->map('POST', '/admin/leltarfej/zar', 'leltarfejController#zar', 'adminleltarfejzar');
}

$router->map('GET', '/admin/termekcimke/viewlist', 'termekcimkeController#viewlist', 'admintermekcimkeviewlist');
$router->map('GET', '/admin/termekcimke/getlistbody', 'termekcimkeController#getlistbody', 'admintermekcimkegetlistbody');
$router->map('GET', '/admin/termekcimke/getkarb', 'termekcimkeController#getkarb', 'admintermekcimkegetkarb');
$router->map('GET', '/admin/termekcimke/viewkarb', 'termekcimkeController#viewkarb', 'admintermekcimkeviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/termekcimke/save', 'termekcimkeController#save', 'admintermekcimkesave');
    $router->map('POST', '/admin/termekcimke/setmenulathato', 'termekcimkeController#setmenulathato', 'admintermekcimkesetmenulathato');
    $router->map('POST', '/admin/termekcimke/add', 'termekcimkeController#add', 'admintermekcimkeadd');
}

$router->map('GET', '/admin/partnercimke/viewlist', 'partnercimkeController#viewlist', 'adminpartnercimkeviewlist');
$router->map('GET', '/admin/partnercimke/getlistbody', 'partnercimkeController#getlistbody', 'adminpartnercimkegetlistbody');
$router->map('GET', '/admin/partnercimke/getkarb', 'partnercimkeController#getkarb', 'adminpartnercimkegetkarb');
$router->map('GET', '/admin/partnercimke/viewkarb', 'partnercimkeController#viewkarb', 'adminpartnercimkeviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/partnercimke/save', 'partnercimkeController#save', 'adminpartnercimkesave');
    $router->map('POST', '/admin/partnercimke/setmenulathato', 'partnercimkeController#setmenulathato', 'adminpartnercimkesetmenulathato');
    $router->map('POST', '/admin/partnercimke/add', 'partnercimkeController#add', 'adminpartnercimkeadd');
}

$router->map('GET', '/admin/korhinta/viewlist', 'korhintaController#viewlist', 'adminkorhintaviewlist');
$router->map('GET', '/admin/korhinta/getlistbody', 'korhintaController#getlistbody', 'adminkorhintagetlistbody');
$router->map('GET', '/admin/korhinta/getkarb', 'korhintaController#getkarb', 'adminkorhintagetkarb');
$router->map('GET', '/admin/korhinta/viewkarb', 'korhintaController#viewkarb', 'adminkorhintaviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/korhinta/save', 'korhintaController#save', 'adminkorhintasave');
    $router->map('POST', '/admin/korhinta/setflag', 'korhintaController#setflag', 'adminkorhintasetflag');
}

$router->map('GET', '/admin/partner/viewlist', 'partnerController#viewlist', 'adminpartnerviewlist');
$router->map('GET', '/admin/partner/getlistbody', 'partnerController#getlistbody', 'adminpartnergetlistbody');
$router->map('GET', '/admin/partner/getkarb', 'partnerController#getkarb', 'adminpartnergetkarb');
$router->map('GET', '/admin/partner/viewkarb', 'partnerController#viewkarb', 'adminpartnerviewkarb');
$router->map('GET', '/admin/partner/getdata', 'partnerController#getPartnerData', 'adminpartnergetdata');
$router->map('GET', '/admin/partnerdok/getemptyrow', 'partnerdokController#getemptyrow', 'adminpartnerdokgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/partner/save', 'partnerController#save', 'adminpartnersave');
    $router->map('POST', '/admin/partner/regisztral', 'partnerController#regisztral', 'adminpartnerregisztral');
    $router->map('POST', '/admin/partner/checkemail', 'partnerController#checkemail', 'adminpartnercheckemail');
    $router->map('POST', '/admin/partner/getkiegyenlitetlenbiz', 'partnerController#getKiegyenlitetlenBiz', 'admingetkiegyenlitetlenbiz');
    $router->map('POST', '/admin/partner/mijszexport', 'partnerController#mijszExport', 'adminmijszexport');
    $router->map('POST', '/admin/partner/megjegyzesexport', 'partnerController#megjegyzesExport', 'adminmegjegyzesexport');
    $router->map('POST', '/admin/partner/mptngyszamlazasexport', 'partnerController#mptngyszamlazasExport', 'adminmptngyszamlazasexport');
    $router->map('POST', '/admin/partner/hirlevelexport', 'partnerController#hirlevelExport', 'adminhirlevelexport');
    $router->map('POST', '/admin/partner/roadrecordexport', 'partnerController#roadrecordExport', 'adminroadrecordexport');
    $router->map('POST', '/admin/partnerdok/del', 'partnerdokController#del', 'adminpartnerdokdel');
    $router->map('POST', '/admin/partner/anonym/do', 'partnerController#doAnonym', 'adminpartnerdoanonym');
    $router->map('POST', '/admin/partner/arsavcsere', 'partnerController#arsavcsere', 'adminpartnerarsavcsere');
    $router->map('POST', '/admin/partner/tcskedit', 'partnerController#tcskedit', 'adminpartnertcskedit');
    $router->map('POST', '/admin/partner/setflag', 'partnerController#setflag', 'adminpartnersetflag');
    $router->map('POST', '/admin/partner/sendemailsablonok', 'partnerController#sendEmailSablonok', 'adminpartnersendemailsablonok');
    $router->map('GET', '/admin/partner/querytaxpayer', 'partnerController#querytaxpayer', 'adminquerytaxpayer');
}

$router->map('GET', '/admin/termekfa/getkarb', 'termekfaController#getkarb', 'admintermekfagetkarb');
$router->map('GET', '/admin/termekfa/jsonlist', 'termekfaController#jsonlist', 'admintermekfajsonlist');
$router->map('GET', '/admin/termekfa/isdeletable', 'termekfaController#isdeletable', 'admintermekfaisdeletable');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/termekfa/save', 'termekfaController#save', 'admintermekfasave');
    $router->map('POST', '/admin/termekfa/move', 'termekfaController#move', 'admintermekfamove');
}
$router->map('GET', '/admin/termekfa/viewlist', 'termekfaController#viewlist', 'admintermekfaviewlist');
$router->map('GET', '/admin/termekfa/regenerateslug', 'termekfaController#regenerateSlug', 'admintermekfaregenerateslug');

$router->map('GET', '/admin/termekmenu/getkarb', 'termekmenuController#getkarb', 'admintermekmenugetkarb');
$router->map('GET', '/admin/termekmenu/jsonlist', 'termekmenuController#jsonlist', 'admintermekmenujsonlist');
$router->map('GET', '/admin/termekmenu/isdeletable', 'termekmenuController#isdeletable', 'admintermekmenuisdeletable');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/termekmenu/save', 'termekmenuController#save', 'admintermekmenusave');
    $router->map('POST', '/admin/termekmenu/move', 'termekmenuController#move', 'admintermekmenumove');
}
$router->map('GET', '/admin/termekmenu/viewlist', 'termekmenuController#viewlist', 'admintermekmenuviewlist');
$router->map('GET', '/admin/termekmenu/regenerateslug', 'termekmenuController#regenerateSlug', 'admintermekmenuregenerateslug');

$router->map('GET', '/admin/kosar/viewlist', 'kosarController#viewlist', 'adminkosarviewlist');
$router->map('GET', '/admin/kosar/getlistbody', 'kosarController#getlistbody', 'adminkosargetlistbody');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/kosar/save', 'kosarController#save', 'adminkosarsave');
}

$router->map('GET', '/admin/uzletkoto/viewlist', 'uzletkotoController#viewlist', 'adminuzletkotoviewlist');
$router->map('GET', '/admin/uzletkoto/getlistbody', 'uzletkotoController#getlistbody', 'adminuzletkotogetlistbody');
$router->map('GET', '/admin/uzletkoto/getkarb', 'uzletkotoController#getkarb', 'adminuzletkotogetkarb');
$router->map('GET', '/admin/uzletkoto/viewkarb', 'uzletkotoController#viewkarb', 'adminuzletkotoviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/uzletkoto/save', 'uzletkotoController#save', 'adminuzletkotosave');
}

$router->map('GET', '/admin/csapat/viewlist', 'csapatController#viewlist', 'admincsapatviewlist');
$router->map('GET', '/admin/csapat/getlistbody', 'csapatController#getlistbody', 'admincsapatgetlistbody');
$router->map('GET', '/admin/csapat/getkarb', 'csapatController#getkarb', 'admincsapatgetkarb');
$router->map('GET', '/admin/csapat/viewkarb', 'csapatController#viewkarb', 'admincsapatviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/csapat/save', 'csapatController#save', 'admincsapatsave');
}

$router->map('GET', '/admin/csapatkep/getemptyrow', 'csapatkepController#getemptyrow', 'admincsapatkepgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/csapatkep/del', 'csapatkepController#del', 'admincsapatkepdel');
}

$router->map('GET', '/admin/versenyzo/viewlist', 'versenyzoController#viewlist', 'adminversenyzoviewlist');
$router->map('GET', '/admin/versenyzo/getlistbody', 'versenyzoController#getlistbody', 'adminversenyzogetlistbody');
$router->map('GET', '/admin/versenyzo/getkarb', 'versenyzoController#getkarb', 'adminversenyzogetkarb');
$router->map('GET', '/admin/versenyzo/viewkarb', 'versenyzoController#viewkarb', 'adminversenyzoviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/versenyzo/save', 'versenyzoController#save', 'adminversenyzosave');
}

$router->map('GET', '/admin/kupon/viewlist', 'kuponController#viewlist', 'adminkuponviewlist');
$router->map('GET', '/admin/kupon/getlistbody', 'kuponController#getlistbody', 'adminkupongetlistbody');
$router->map('GET', '/admin/kupon/getkarb', 'kuponController#getkarb', 'adminkupongetkarb');
$router->map('GET', '/admin/kupon/viewkarb', 'kuponController#viewkarb', 'adminkuponviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/kupon/save', 'kuponController#save', 'adminkuponsave');
    $router->map('GET', '/admin/kupon/print', 'kuponController#doPrint', 'adminkuponprint');
}

$router->map('GET', '/admin/sitemap/view', 'sitemapController#view', 'adminsitemapview');
$router->map('GET', '/admin/sitemap/create', 'sitemapController#create', 'adminsitemapcreate');

$router->map('POST', '/admin/import/stop/[:impname]', 'importController#stop', 'adminimportstop');
$router->map('POST', '/admin/import/repair/[:impname]', 'importController#repair', 'adminimportrepair');
if (!\mkw\store::isClosed()) {
    $router->map('GET', '/admin/export/view', 'exportController#view', 'adminexportview');
    $router->map('GET', '/admin/export/grando', 'exportController#GrandoExport', 'admingrandoexport');

    $router->map('GET', '/admin/import/view', 'importController#view', 'adminimportview');
    $router->map('POST', '/admin/import/kreativ', 'importController#kreativpuzzleImport', 'adminkreativpuzzleimport');
    $router->map('POST', '/admin/import/delton', 'importController#deltonImport', 'admindeltonimport');
    $router->map('POST', '/admin/import/nomad', 'importController#nomadImport', 'adminnomadimport');
    $router->map('POST', '/admin/import/nika', 'importController#nikaImport', 'adminnikaimport');
    $router->map('POST', '/admin/import/haffner24', 'importController#haffner24Import', 'adminhaffner24import');
    $router->map('POST', '/admin/import/reintex', 'importController#reintexImport', 'adminreinteximport');
    $router->map('POST', '/admin/import/legavenue', 'importController#legavenueImport', 'adminlegavenueimport');
    $router->map('POST', '/admin/import/legavenueszotar', 'importController#legavenueSzotar', 'adminlegavenueSzotar');
    $router->map('POST', '/admin/import/tutisport', 'importController#tutisportImport', 'admintutisportimport');
    $router->map('POST', '/admin/import/makszutov', 'importController#makszutovImport', 'adminmakszutovimport');
    $router->map('POST', '/admin/import/evona', 'importController#evonaImport', 'adminevonaimport');
    $router->map('POST', '/admin/import/evonaxml', 'importController#evonaxmlImport', 'adminevonaxmlimport');
    $router->map('POST', '/admin/import/gulf', 'importController#gulfImport', 'admingulfimport');
    $router->map('POST', '/admin/import/smileebike', 'importController#smileebikeImport', 'adminsmileebikeimport');
    $router->map('POST', '/admin/import/copydepotermek', 'importController#copydepotermekImport', 'admincopydepotermekimport');
    $router->map('POST', '/admin/import/copydepokeszlet', 'importController#copydepokeszletImport', 'admincopydepokeszletimport');
    $router->map('POST', '/admin/import/vatera', 'importController#vateraImport', 'adminvateraimport');
    $router->map('POST', '/admin/import/szimport', 'importController#szImport', 'adminszimport');
    $router->map('POST', '/admin/import/szcimkeimport', 'importController#szcimkeimport', 'adminszcimkeimport');
    $router->map('POST', '/admin/import/szeanimport', 'importController#szeanimport', 'adminszeanimport');
    $router->map('POST', '/admin/import/szmeretimport', 'importController#szmeretimport', 'adminszmeretimport');
    $router->map('POST', '/admin/import/szcolorimport', 'importController#szcolorimport', 'adminszcolorimport');
    $router->map('POST', '/admin/import/fcmotoorderimport', 'importController#fcmotoorderimport', 'adminfcmotoorderimport');
    $router->map('POST', '/admin/import/foxpostterminal', 'csomagterminalController#downloadFoxpostTerminalList', 'admincsomagterminalfoxpostimport');
    $router->map('POST', '/admin/import/glsterminal', 'csomagterminalController#downloadGLSTerminalList', 'admincsomagterminalglsimport');
    $router->map('POST', '/admin/import/aszfdownload', 'importController#aszfdownload', 'adminaszfdownload');
    $router->map('GET', '/admin/import/siikerpartnerimport', 'importController#SIIKerPartnerImport', 'adminsiikerpartnerimport');
}

$router->map('GET', '/admin/login/show', 'dolgozoController#showlogin', 'adminshowlogin');
$router->map('POST', '/admin/login', 'dolgozoController#login', 'adminlogin');
$router->map('GET', '/admin/logout', 'dolgozoController#logout', 'adminlogout');

$router->map('GET', '/admin/bizonylatstatusz/viewlist', 'bizonylatstatuszController#viewlist', 'adminbizonylatstatuszviewlist');
$router->map('GET', '/admin/bizonylatstatusz/getlistbody', 'bizonylatstatuszController#getlistbody', 'adminbizonylatstatuszgetlistbody');
$router->map('GET', '/admin/bizonylatstatusz/getkarb', 'bizonylatstatuszController#getkarb', 'adminbizonylatstatuszgetkarb');
$router->map('GET', '/admin/bizonylatstatusz/viewkarb', 'bizonylatstatuszController#viewkarb', 'adminbizonylatstatuszviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/bizonylatstatusz/save', 'bizonylatstatuszController#save', 'adminbizonylatstatuszsave');
}

$router->map('GET', '/admin/jogaberlet/viewlist', 'jogaberletController#viewlist', 'adminjogaberletviewlist');
$router->map('GET', '/admin/jogaberlet/getlistbody', 'jogaberletController#getlistbody', 'adminjogaberletgetlistbody');
$router->map('GET', '/admin/jogaberlet/getkarb', 'jogaberletController#getkarb', 'adminjogaberletgetkarb');
$router->map('GET', '/admin/jogaberlet/viewkarb', 'jogaberletController#viewkarb', 'adminjogaberletviewkarb');
$router->map('GET', '/admin/jogaberlet/getselect', 'jogaberletController#getSelectHtml', 'adminjogaberletgetselect');
$router->map('GET', '/admin/jogaberlet/getar', 'jogaberletController#getar', 'adminjogaberletgetar');
$router->map('GET', '/admin/jogaberlet/lejarat', 'jogaberletController#bulkLejarat', 'adminjogaberletbulklejarat');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/jogaberlet/save', 'jogaberletController#save', 'adminjogaberletsave');
    $router->map('POST', '/admin/jogaberlet/setflag', 'jogaberletController#setflag', 'adminjogaberletsetflag');
}

$router->map('GET', '/admin/rlbexport', 'exportController#RLBExport', 'adminrlbexport');
$router->map('GET', '/admin/rlbcsvexport/view', 'rlbexportController#view', 'adminrlbcsvexportview');
$router->map('GET', '/admin/rlbcsvexport/export', 'rlbexportController#RLBCSVExport', 'adminrlbcsvexport');

$router->map('GET', '/admin/pdfszamlaexport/view', 'pdfszamlaexportController#view', 'adminpdfszamlaexportview');
$router->map('POST', '/admin/pdfszamlaexport/sendemail', 'pdfszamlaexportController#sendEmail', 'adminpdfszamlasendemail');
$router->map('GET', '/admin/pdfszamlaexport/download', 'pdfszamlaexportController#download', 'adminpdfszamladownload');

$router->map('GET', '/admin/xmlszamlaexport/view', 'xmlszamlaexportController#view', 'adminxmlszamlaexportview');
$router->map('POST', '/admin/xmlszamlaexport/sendemail', 'xmlszamlaexportController#sendEmail', 'adminxmlszamlasendemail');
$router->map('GET', '/admin/xmlszamlaexport/download', 'xmlszamlaexportController#download', 'adminxmlszamladownload');

$router->map('GET', '/admin/fifoteszt', 'fifoController#teszt', 'adminfifoteszt');
$router->map('GET', '/admin/fifo/view', 'fifoController#view', 'adminfifoview');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/fifo/calc', 'fifoController#calculate', 'adminfifocalc');
}
$router->map('GET', '/admin/fifo/alapadat', 'fifoController#getAlapadat', 'adminfifoalapadat');
$router->map('GET', '/admin/fifo/keszletertek', 'fifoController#getKeszletertek', 'adminkeszletertek');

$router->map('GET', '/admin/lista/boltbannincsmasholvan', 'listaController#boltbannincsmasholvan', 'adminlistaboltbannincsmasholvan');
$router->map('GET', '/admin/lista/nemkaphatoertesito', 'listaController#nemkaphatoertesito', 'adminlistanemkaphatoertesito');
//$router->map('GET', '/admin/fillbiztetelvaltozat', 'adminController#fillBiztetelValtozat', 'adminfillbiztetelvaltozat');
$router->map('POST', '/admin/napijelentes', 'adminController#printNapijelentes', 'adminnapijelentes');
$router->map('POST', '/admin/teljesitmenyjelentes', 'adminController#printTeljesitmenyJelentes', 'adminteljesitmenyjelentes');
//$router->map('GET', '/admin/genfszla', 'adminController#generateFolyoszamla', 'admingeneratefolyoszamla');

$router->map('GET', '/admin/keszletlista/view', 'keszletlistaController#view', 'adminkeszletlistaview');
$router->map('GET', '/admin/keszletlista/get', 'keszletlistaController#createLista', 'adminkeszletlistaget');
$router->map('GET', '/admin/keszletlista/export', 'keszletlistaController#exportLista', 'adminkeszletlistaexport');

$router->map('GET', '/admin/szepkartyakifizetes/view', 'szepkartyakifizetesController#view', 'adminszepkartyakifizetesview');
$router->map('POST', '/admin/szepkartyakifizetes/kifizet', 'szepkartyakifizetesController#kifizet', 'adminszepkartyakifizeteskifizet');

$router->map('GET', '/admin/keresoszolista/view', 'keresoszolistaController#view', 'adminkeresoszolistaview');
$router->map('GET', '/admin/keresoszolista/get', 'keresoszolistaController#createLista', 'adminkeresoszolistaget');

$router->map('GET', '/admin/termekkarton/view', 'termekkartonController#view', 'admintermekkartonview');
$router->map('GET', '/admin/termekkarton/refresh', 'termekkartonController#refresh', 'admintermekkartonrefresh');

$router->map('GET', '/admin/termekforgalmilista/view', 'termekforgalmilistaController#view', 'admintermekforgalmilistaview');
$router->map('GET', '/admin/termekforgalmilista/refresh', 'termekforgalmilistaController#refresh', 'admintermekforgalmilistarefresh');
$router->map('GET', '/admin/termekforgalmilista/export', 'termekforgalmilistaController#export', 'admintermekforgalmilistaexport');

$router->map(
    'GET',
    '/admin/partnermijszoralatogatasosszesitolista/view',
    'partnermijszoralatogatasosszesitolistaController#view',
    'adminpartnermijszoralatogatasosszesitolistaview'
);
$router->map(
    'GET',
    '/admin/partnermijszoralatogatasosszesitolista/refresh',
    'partnermijszoralatogatasosszesitolistaController#refresh',
    'adminpartnermijszoralatogatasosszesitolistarefresh'
);
$router->map(
    'GET',
    '/admin/partnermijszoralatogatasosszesitolista/export',
    'partnermijszoralatogatasosszesitolistaController#export',
    'adminpartnermijszoralatogatasosszesitolistaexport'
);

$router->map('GET', '/admin/bizonylattetellista/view', 'bizonylattetellistaController#view', 'adminbizonylattetellistaview');
$router->map('GET', '/admin/bizonylattetellista/refresh', 'bizonylattetellistaController#refresh', 'adminbizonylattetellistarefresh');
$router->map('GET', '/admin/bizonylattetellista/export', 'bizonylattetellistaController#export', 'adminbizonylattetellistaexport');
$router->map('GET', '/admin/bizonylattetellista/print', 'bizonylattetellistaController#doPrint', 'adminbizonylattetellistaprint');

$router->map('GET', '/admin/tanarelszamolas/view', 'tanarelszamolasController#view', 'admintanarelszamolasview');
$router->map('GET', '/admin/tanarelszamolas/refresh', 'tanarelszamolasController#refresh', 'admintanarelszamolasrefresh');
$router->map('GET', '/admin/tanarelszamolas/export', 'tanarelszamolasController#export', 'admintanarelszamolasexport');
$router->map('GET', '/admin/tanarelszamolas/print', 'tanarelszamolasController#doPrint', 'admintanarelszamolasprint');
$router->map('GET', '/admin/tanarelszamolas/reszletezo', 'tanarelszamolasController#reszletezo', 'admintanarelszamolasreszletezo');
$router->map('GET', '/admin/tanarelszamolas/email', 'tanarelszamolasController#sendEmail', 'admintanarelszamolassendemail');

$router->map('GET', '/admin/teljesitmenyjelentes/view', 'teljesitmenyjelentesController#view', 'adminteljesitmenyjelentesview');
$router->map('GET', '/admin/teljesitmenyjelentes/refresh', 'teljesitmenyjelentesController#refresh', 'adminteljesitmenyjelentesrefresh');

$router->map('GET', '/admin/bizomanyosertekesiteslista/view', 'bizomanyosertekesiteslistaController#view', 'adminbizomanyosertekesiteslistaview');
$router->map('GET', '/admin/bizomanyosertekesiteslista/refresh', 'bizomanyosertekesiteslistaController#refresh', 'adminbizomanyosertekesiteslistarefresh');

$router->map('GET', '/admin/csomagterminal/gethtmllist', 'csomagterminalController#getHTMLList', 'admincsomagterminalgethtmllist');

$router->map('GET', '/admin/minicrm/view', 'minicrmController#view', 'adminminicrmview');
$router->map('POST', '/admin/minicrm/partnerimport', 'minicrmController#partnerImport', 'adminminicrmpartnerimport');

$router->map('POST', '/admin/konyveloexport', 'bankbizonylatfejController#exportKonyvelo', 'adminkonyveloexport');

$router->map('GET', '/admin/helyszin/viewlist', 'helyszinController#viewlist', 'adminhelyszinviewlist');
$router->map('GET', '/admin/helyszin/getlistbody', 'helyszinController#getlistbody', 'adminhelyszingetlistbody');
$router->map('GET', '/admin/helyszin/getkarb', 'helyszinController#getkarb', 'adminhelyszingetkarb');
$router->map('GET', '/admin/helyszin/viewkarb', 'helyszinController#viewkarb', 'adminhelyszinviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/helyszin/save', 'helyszinController#save', 'adminhelyszinsave');
}

$router->map('GET', '/admin/rendezveny/viewlist', 'rendezvenyController#viewlist', 'adminrendezvenyviewlist');
$router->map('GET', '/admin/rendezveny/getlistbody', 'rendezvenyController#getlistbody', 'adminrendezvenygetlistbody');
$router->map('GET', '/admin/rendezveny/getkarb', 'rendezvenyController#getkarb', 'adminrendezvenygetkarb');
$router->map('GET', '/admin/rendezveny/viewkarb', 'rendezvenyController#viewkarb', 'adminrendezvenyviewkarb');
$router->map('GET', '/admin/rendezvenydok/getemptyrow', 'rendezvenydokController#getemptyrow', 'adminrendezvenydokgetemptyrow');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/rendezveny/save', 'rendezvenyController#save', 'adminrendezvenysave');
    $router->map('POST', '/admin/rendezveny/setflag', 'rendezvenyController#setflag', 'adminrendezvenysetflag');
    $router->map('POST', '/admin/rendezveny/email/kezdes', 'rendezvenyController#sendKezdesEmail', 'adminsendrendezvenykezdesemail');
    $router->map('POST', '/admin/rendezvenydok/del', 'rendezvenydokController#del', 'adminrendezvenydokdel');
}

$router->map('GET', '/admin/rendezvenyjelentkezes/viewlist', 'rendezvenyjelentkezesController#viewlist', 'adminrendezvenyjelentkezesviewlist');
$router->map('GET', '/admin/rendezvenyjelentkezes/getlistbody', 'rendezvenyjelentkezesController#getlistbody', 'adminrendezvenyjelentkezesgetlistbody');
$router->map('GET', '/admin/rendezvenyjelentkezes/getkarb', 'rendezvenyjelentkezesController#getkarb', 'adminrendezvenyjelentkezesgetkarb');
$router->map('GET', '/admin/rendezvenyjelentkezes/viewkarb', 'rendezvenyjelentkezesController#viewkarb', 'adminrendezvenyjelentkezesviewkarb');
$router->map('GET', '/admin/rendezvenyjelentkezes/getar', 'rendezvenyjelentkezesController#getar', 'adminrendezvenyjelentkezesgetar');
$router->map(
    'GET',
    '/admin/rendezvenyjelentkezes/getfizetettosszeg',
    'rendezvenyjelentkezesController#getfizetettosszeg',
    'adminrendezvenyjelentkezesgetfizetettosszeg'
);
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/rendezvenyjelentkezes/save', 'rendezvenyjelentkezesController#save', 'adminrendezvenyjelentkezessave');
    $router->map('POST', '/admin/rendezvenyjelentkezes/fizet', 'rendezvenyjelentkezesController#fizet', 'adminrendezvenyjelentkezesfizet');
    $router->map('POST', '/admin/rendezvenyjelentkezes/szamlaz', 'rendezvenyjelentkezesController#szamlaz', 'adminrendezvenyjelentkezesszamlaz');
    $router->map('POST', '/admin/rendezvenyjelentkezes/lemond', 'rendezvenyjelentkezesController#lemond', 'adminrendezvenyjelentkezeslemond');
    $router->map('POST', '/admin/rendezvenyjelentkezes/visszautal', 'rendezvenyjelentkezesController#visszautal', 'adminrendezvenyjelentkezesvisszautal');
    $router->map(
        'POST',
        '/admin/rendezvenyjelentkezes/email/dijbekero',
        'rendezvenyjelentkezesController#sendDijbekeroEmail',
        'adminsendrendezvenyjeldijbekeroemail'
    );
    $router->map('POST', '/admin/rendezvenyjelentkezes/email/kezdes', 'rendezvenyjelentkezesController#sendKezdesEmail', 'adminsendrendezvenyjelkezdesemail');
}

$router->map('GET', '/admin/termekertekeles/viewlist', 'termekertekelesController#viewlist', 'admintermekertekelesviewlist');
$router->map('GET', '/admin/termekertekeles/getlistbody', 'termekertekelesController#getlistbody', 'admintermekertekelesgetlistbody');
$router->map('GET', '/admin/termekertekeles/getkarb', 'termekertekelesController#getkarb', 'admintermekertekelesgetkarb');
$router->map('GET', '/admin/termekertekeles/viewkarb', 'termekertekelesController#viewkarb', 'admintermekertekelesviewkarb');
if (!\mkw\store::isClosed()) {
    $router->map('POST', '/admin/termekertekeles/save', 'termekertekelesController#save', 'admintermekertekelessave');
}

if (haveJog(90)) {
    $router->map('GET', '/admin/bizvissza', 'bizonylatfejController#setNyomtatvaVissza', 'adminbizvissza');
//    $router->map('GET', '/admin/bizpartnerjavit', 'bizonylatfejController#repairPartnerAdat', 'adminbizpartnerjavit');
    $router->map('GET', '/admin/partnermerge/view', 'partnermergeController#view', 'adminpartnermergeview');
    if (!\mkw\store::isClosed()) {
        $router->map('POST', '/admin/partnermerge', 'partnermergeController#doIt', 'adminpartnermerge');
    }
}

if (\mkw\store::isDarshan()) {
    $router->map('GET', '/admin/orarend/viewlist', 'orarendController#viewlist', 'adminorarendviewlist');
    $router->map('GET', '/admin/orarend/htmllist', 'orarendController#htmllist', 'adminorarendhtmllist');
    $router->map('GET', '/admin/orarend/getlistbody', 'orarendController#getlistbody', 'adminorarendgetlistbody');
    $router->map('GET', '/admin/orarend/getkarb', 'orarendController#getkarb', 'adminorarendgetkarb');
    $router->map('GET', '/admin/orarend/viewkarb', 'orarendController#viewkarb', 'adminorarendviewkarb');
    if (!\mkw\store::isClosed()) {
        $router->map('POST', '/admin/orarend/save', 'orarendController#save', 'adminorarendsave');
        $router->map('POST', '/admin/orarend/setflag', 'orarendController#setflag', 'adminorarendsetflag');
    }
    $router->map('GET', '/admin/orarend/getlistforhelyettesites', 'orarendController#getListForHelyettesites', 'adminorarendgetlistforhelyettesites');

    $router->map('GET', '/admin/orarendhelyettesites/viewlist', 'orarendhelyettesitesController#viewlist', 'adminorarendhelyettesitesviewlist');
    $router->map('GET', '/admin/orarendhelyettesites/htmllist', 'orarendhelyettesitesController#htmllist', 'adminorarendhelyettesiteshtmllist');
    $router->map('GET', '/admin/orarendhelyettesites/getlistbody', 'orarendhelyettesitesController#getlistbody', 'adminorarendhelyettesitesgetlistbody');
    $router->map('GET', '/admin/orarendhelyettesites/getkarb', 'orarendhelyettesitesController#getkarb', 'adminorarendhelyettesitesgetkarb');
    $router->map('GET', '/admin/orarendhelyettesites/viewkarb', 'orarendhelyettesitesController#viewkarb', 'adminorarendhelyettesitesviewkarb');
    if (!\mkw\store::isClosed()) {
        $router->map('POST', '/admin/orarendhelyettesites/save', 'orarendhelyettesitesController#save', 'adminorarendhelyettesitessave');
        $router->map('POST', '/admin/orarendhelyettesites/setflag', 'orarendhelyettesitesController#setflag', 'adminorarendhelyettesitessetflag');
    }

    $router->map('GET', '/admin/cimletez', 'adminController#cimletez', 'admincimletez');
    $router->map('POST', '/admin/jelenbe', 'jelenletiivController#createBelepes', 'adminjelenbe');
    $router->map('POST', '/admin/jelenki', 'jelenletiivController#createKilepes', 'adminjelenki');
    $router->map('POST', '/admin/berletervenyessegkalkulator', 'adminController#calcBerletervenyesseg', 'adminberletervenyessegkalkulator');
    $router->map('POST', '/admin/darshanstat', 'adminController#darshanStatisztika', 'admindarshanstat');
}

$router->map('GET', '/admin/wc/termekmenu', 'termekmenuController#uploadToWc', 'admintermekmenuuploadtowc');
$router->map('GET', '/admin/wc/termekvaltozatadattipus', 'termekvaltozatadattipusController#uploadToWc', 'admintermekvaltozatadattipusuploadtowc');
$router->map('GET', '/admin/wc/termekvaltozatertek', 'termekvaltozatertekController#uploadToWc', 'admintermekvaltozatertekuploadtowc');
$router->map('GET', '/admin/wc/termekcimke', 'termekcimkeController#uploadToWc', 'admintermekcimkeuploadtowc');
$router->map('GET', '/admin/wc/termek', 'termekController#uploadToWc', 'admintermekuploadtowc');
$router->map('GET', '/admin/wc/termekar', 'termekarController#uploadToWc', 'admintermekaruploadtowc');

$router->map('POST', '/admin/apierrorlog/close', 'apierrorlogController#close', 'adminapierrorlogclose');

if (\mkw\store::isMailerGmail()) {
    $router->map('GET', '/admin/oauth2/initiate', 'oauth2Controller#initiate', 'adminoauth2initiate');
}

//$router->map('POST', '/admin/minicrmmail', 'adminController#replier', 'adminminicrmmail');

//$router->map('GET', '/admin/t/presta', 'termekmenuController#exportToPrestashop', 'admintermekmenuexportToPrestashop');
//$router->map('GET', '/admin/t/minicrm', 'adminController#minicrm', 'adminminicrm');
//$router->map('GET', '/admin/t/kerriiimport', 'importController#kerriiimport', 'adminkerriiimport');
//$router->map('GET', '/admin/t/genean13', 'adminController#genean13', 'admingenean13');
//$router->map('GET', '/admin/t/emailtemplateconvert', 'emailtemplateController#convertToCKEditor', 'adminemailtemplateconverttockeditor');
//$router->map('GET', '/admin/t/repairfoglalas', 'adminController#repairFoglalas', 'adminrepairfoglalas');
//$router->map('GET', '/admin/t/emailcheck', 'adminController#checkEmail', 'adminemailcheck');
//$router->map('GET', '/admin/t/sptcsp', 'adminController#TermekcsoportPiszkalas', 'admintermekcsoportpiszkalas');
//$router->map('GET', '/admin/t/makszutovidcsere', 'importController#makszutovIdCsere', 'adminmakszutovidcsere');
//$router->map('GET', '/admin/t/ujdivatszamlare', 'adminController#ujdivatszamlare', 'adminujdivatszamlare');
//$router->map('GET', '/admin/t/mpttagimport', 'adminController#MPTPartnerImport', 'adminmptpartnerimport');
//$router->map('GET', '/admin/t/fszlahivdatum', 'adminController#fszlahivdatumJavit', 'adminfszlahivdatumjavit');
