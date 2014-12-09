<?php

function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function writelog($text, $fname = 'jserrors.log') {
    $handle = fopen($fname, "a");
    $log = "";
    $separator = " ## ";
    $log.=date('Y.m.d. H:i:s') . $separator;
    $log.=get_client_ip() . $separator;
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

