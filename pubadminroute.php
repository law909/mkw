<?php

$router->map('GET', '/pubadmin', 'pubadminController#view', 'pubadminview');
$router->map('GET', '/pubadmin/view', 'pubadminController#view', 'pubadminview2');

$router->map('GET', '/pubadmin/login/show', 'dolgozoController#showpubadminlogin', 'pubadminshowlogin');
$router->map('POST', '/pubadmin/login', 'dolgozoController#pubadminlogin', 'pubadminlogin');
$router->map('GET', '/pubadmin/logout', 'dolgozoController#pubadminlogout', 'pubadminlogout');

$router->map('GET', '/pubadmin/resztvevolist', 'pubadminController#getResztvevolist', 'pubadmingetresztvevolist');
$router->map('POST', '/pubadmin/resztvevomegjelent', 'pubadminController#setResztvevoMegjelent', 'pubadminsetresztvevomegjelent');
$router->map('POST', '/pubadmin/resztvevoorajegy', 'pubadminController#setResztvevoOrajegy', 'pubadminsetresztvevoorajegy');

$router->map('GET', '/pubadmin/oralist', 'pubadminController#getOralist', 'pubadmingetoralist');
$router->map('GET', '/pubadmin/partnerdata', 'pubadminController#getPartnerData', 'pubadmingetpartnerdata');
$router->map('POST', '/pubadmin/newbejelentkezes', 'pubadminController#newBejelentkezes', 'pubadminnewbejelentkezes');
$router->map('POST', '/pubadmin/newpartnernewbejelentkezes', 'pubadminController#newBejelentkezesWNewPartner', 'pubadminnewpartnernewbejelentkezes');

$router->map('GET', '/pubadmin/megjegyzes', 'pubadminController#getMegjegyzes', 'pubadmingetmegjegyzes');
$router->map('POST', '/pubadmin/megjegyzes', 'pubadminController#postMegjegyzes', 'pubadminpostmegjegyzes');

$router->map('GET', '/pubadmin/partner', 'pubadminController#getPartner', 'pubadmingetpartner');
$router->map('POST', '/pubadmin/partner', 'pubadminController#postPartner', 'pubadminpostpartner');

$router->map('POST', '/pubadmin/lemond', 'pubadminController#lemondOra', 'pubadminlemondora');

$router->map('GET', '/pubadmin/mptngysetup', 'dolgozoController#mptngysetupView', 'pubadminmptngysetupview');
$router->map('GET', '/pubadmin/mptngyme', 'dolgozoController#getmptngyme', 'pubadminmptngyme');
$router->map('POST', '/pubadmin/mptngysetup/ment', 'dolgozoController#savemptngysetup', 'pubadminsavemptngysetup');

$router->map('GET', '/pubadmin/mptngybiralas', 'dolgozoController#mptngybiralasView', 'pubadminmptngybiralas');
$router->map('GET', '/pubadmin/biralandoanyaglist', 'mptngyszakmaianyagController#getBiralandoAnyagList', 'pubadminmptngygetbiralandoanyaglist');
$router->map('GET', '/pubadmin/szempontlist', 'setupController#getMPTNGYSzempontList', 'pubadminmptngygetszempontlist');
$router->map('POST', '/pubadmin/mptngybiralas/ment', 'mptngyszakmaianyagController#biralatSave', 'pubadminmptngybiralasment');
