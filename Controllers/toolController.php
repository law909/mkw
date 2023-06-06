<?php


namespace Controllers;


class toolController extends \mkwhelpers\Controller
{

    public function reintexDownload()
    {
        @\unlink(\mkw\store::storagePath('reintex.csv'));
        $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlReintex));
        $fh = fopen(\mkw\store::storagePath('reintex.csv'), 'w');
        \curl_setopt($ch, CURLOPT_FILE, $fh);
        \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        \curl_exec($ch);
        fclose($fh);
        \curl_close($ch);

        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile(\mkw\store::storagePath('reintex.csv'));
    }

    public function makszutovDownload()
    {
        @\unlink(\mkw\store::storagePath('makszutov.txt'));
        $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlMaxutov));
        $fh = fopen(\mkw\store::storagePath('makszutov.txt'), 'w');
        \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        \curl_setopt($ch, CURLOPT_FILE, $fh);
        \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        \curl_exec($ch);
        fclose($fh);
        \curl_close($ch);

        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile(\mkw\store::storagePath('makszutov.txt'));
    }

}