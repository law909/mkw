<?php
$ret=array();
$x=0;
chdir('/var/www/mattkft.hu/mindentkapni.mattkft.hu');
exec('git pull',$ret,$x);
print_r($ret);
echo $x;