<?php
$ret=array();
$x=0;
chdir('/var/www/mattkft.hu/mindentkapni.mattkft.hu');
exec('git pull',$ret,$x);
foreach($ret as $l) {
	echo $l.'<br>';
}