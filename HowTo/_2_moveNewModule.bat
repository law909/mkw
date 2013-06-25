@echo off
copy js\*.* ..\js\admin\default\     
copy Entities\*.* ..\Entities\     
copy Controllers\*.* ..\Controllers\     
copy tpl\default\*.* ..\tpl\admin\default\

del js\*.* /Q
del Entities\*.* /Q
del Controllers\*.* /Q
del tpl\default\*.* /Q