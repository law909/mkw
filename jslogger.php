<?php

function writelog($text, $fname = 'jserrors.log') {
    $handle = fopen($fname, "a");
    $log = "";
    $separator = " ## ";
    $log.=date('Y.m.d. H:i:s') . $separator;
    $log.=$text;
    $log.="\n";
    fwrite($handle, $log);
    fclose($handle);
}

require 'vendor/autoload.php';

if (isset($_POST["req"])) {
	$req = $_POST["req"];
}

switch ($req) {
    case 'write':
        writelog($_POST['data']);
        break;
}

