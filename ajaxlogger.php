<?php

function get_client_ip() {
    $ipaddress = '';
    if (array_key_exists('HTTP_CLIENT_IP', $_SERVER) && $_SERVER['HTTP_CLIENT_IP']) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    }
    else {
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            if (array_key_exists('HTTP_X_FORWARDED', $_SERVER) && $_SERVER['HTTP_X_FORWARDED']) {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            }
            else {
                if (array_key_exists('HTTP_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_FORWARDED_FOR']) {
                    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                }
                else {
                    if (array_key_exists('HTTP_FORWARDED', $_SERVER) && $_SERVER['HTTP_FORWARDED']) {
                        $ipaddress = $_SERVER['HTTP_FORWARDED'];
                    }
                    else {
                        if (array_key_exists('REMOTE_ADDR', $_SERVER) && $_SERVER['REMOTE_ADDR']) {
                            $ipaddress = $_SERVER['REMOTE_ADDR'];
                        }
                        else {
                            $ipaddress = 'UNKNOWN';
                        }
                    }
                }
            }
        }
    }
    return $ipaddress;
}

function writelog($text, $fname = 'checkoutajaxlog.log') {
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
        //writelog($_POST['data']);
        break;
}
