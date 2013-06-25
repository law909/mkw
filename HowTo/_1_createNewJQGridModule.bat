@echo off
: 1. paramter a kisbetus 2. Nagybetus

del js\*.* /Q
del Entities\*.* /Q
del Controllers\*.* /Q
del tpl\default\*.* /Q

copy Howto.php Entities\%2.php
copy howtoJQGridController.php Controllers\%1Controller.php
rem copy howtokarb.tpl tpl\default\%1karb.tpl
rem copy howtokarbform.tpl tpl\default\%1karbform.tpl
rem copy howtolista.tpl tpl\default\%1lista.tpl
rem copy howtolista_tbody.tpl tpl\default\%1lista_tbody.tpl
rem copy howtolista_tbody_tr.tpl tpl\default\%1lista_tbody_tr.tpl
copy HowtoRepository.php Entities\%2Repository.php