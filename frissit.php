<?php
$ret=array();
$x=0;
chdir('eleresi ut');
exec('git pull',$ret,$x);
foreach($ret as $l) {
	echo $l.'<br>';
}