<?php

$router->map('GET', '/pubadmin', 'pubadminController#view', 'pubadminview');
$router->map('GET', '/pubadmin/view', 'pubadminController#view', 'pubadminview2');

$router->map('GET', '/pubadmin/login/show', 'dolgozoController#showpubadminlogin', 'pubadminshowlogin');
$router->map('POST', '/pubadmin/login', 'dolgozoController#pubadminlogin', 'pubadminlogin');
$router->map('GET', '/pubadmin/logout', 'dolgozoController#pubadminlogout', 'pubadminlogout');

$router->map('GET', '/pubadmin/resztvevolist', 'pubadminController#getResztvevolist', 'pubadmingetresztvevolist');
$router->map('POST', '/pubadmin/resztvevomegjelent', 'pubadminController#setResztvevoMegjelent', 'pubadminsetresztvevomegjelent');