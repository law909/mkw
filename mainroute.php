<?php
$router->map('GET','','mainController#view','home');
$router->map('GET','/login','partnerController#showLoginForm','showlogin');
$router->map('POST','/login/ment','partnerController#doLogin','dologin');
$router->map('GET','/logout','partnerController#doLogout','dologout');
$router->map('GET','/regisztracio','partnerController#showRegistrationForm','showregistration');
$router->map('POST','/regisztracio/ment','partnerController#saveRegistration','saveregistration');
$router->map('GET','/fiok','partnerController#showAccount','showaccount');
$router->map('POST','/fiok/ment','partnerController#saveAccount','saveaccount');
$router->map('POST','/checkemail','partnerController#checkemail','partnercheckemail');

$router->map('GET','/statlap/[:lap]','statlapController#show','showstatlap');
$router->map('GET','/hir/[:hir]','hirController#show','showhir');
$router->map('GET','/feed/hir','hirController#feed','hirfeed');
$router->map('GET','/feed/termek','termekController#feed','termekfeed');
$router->map('GET','/kapcsolat','mainController#kapcsolat','showkapcsolat');
$router->map('POST','/kapcsolat/[ment:todo]','mainController#kapcsolat','savekapcsolat');

$router->map('GET','/termekfa/[:slug]','mainController#termekfa','termekfa');
$router->map('GET','/termek/[:slug]','mainController#termek','showtermek');
$router->map('GET','/valtozatar','mainController#valtozatar','valtozatar');
$router->map('GET','/valtozat','mainController#valtozat','valtozat');
$router->map('GET','/kereses','mainController#kereses','kereses');
