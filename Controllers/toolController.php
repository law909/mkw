<?php


namespace Controllers;


class toolController extends \mkwhelpers\Controller {

    public function reintexDownload() {
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
}