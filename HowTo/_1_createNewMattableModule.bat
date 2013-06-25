@echo off
: 1. parameter a kisbetus 2. Nagybetus

del js\*.* /Q
del Entities\*.* /Q
del Controllers\*.* /Q
del tpl\default\*.* /Q

copy howto.js js\%1.js
copy Howto.php Entities\%2.php
copy howtoMattableController.php Controllers\%1Controller.php
copy howtokarb.tpl tpl\default\%1karb.tpl
copy howtokarbform.tpl tpl\default\%1karbform.tpl
copy howtolista.tpl tpl\default\%1lista.tpl
copy howtolista_tbody.tpl tpl\admin\default\%1lista_tbody.tpl
copy howtolista_tbody_tr.tpl tpl\admin\default\%1lista_tbody_tr.tpl
copy HowtoRepository.php Entities\%2Repository.php