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
