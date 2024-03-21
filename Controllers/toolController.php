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

    public function copydepotermekDownload()
    {
        @\unlink(\mkw\store::storagePath('copydepotermek.xml'));
        $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlCopydepoTermek));
        $fh = fopen(\mkw\store::storagePath('copydepotermek.xml'), 'w');
        \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        \curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        \curl_setopt($ch, CURLOPT_FILE, $fh);
        \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $retval = \curl_exec($ch);
        if ($retval !== false && (!\curl_errno($ch))) {
            $http_code = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code !== 200) {
                \mkw\store::writelog($http_code);
            }
        } else {
            \mkw\store::writelog(\curl_errno($ch));
            \mkw\store::writelog(\curl_error($ch));
        }
        fclose($fh);
        \curl_close($ch);

        header("Content-type: application/xml");
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile(\mkw\store::storagePath('copydepotermek.xml'));
    }

    public function copydepokeszletDownload()
    {
        @\unlink(\mkw\store::storagePath('copydepokeszlet.xml'));
        $ch = \curl_init(\mkw\store::getParameter(\mkw\consts::UrlCopydepoKeszlet));
        $fh = fopen(\mkw\store::storagePath('copydepokeszlet.xml'), 'w');
        \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        \curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        \curl_setopt($ch, CURLOPT_FILE, $fh);
        \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $retval = \curl_exec($ch);
        if ($retval !== false && (!\curl_errno($ch))) {
            $http_code = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code !== 200) {
                \mkw\store::writelog($http_code);
            }
        } else {
            \mkw\store::writelog(\curl_errno($ch));
            \mkw\store::writelog(\curl_error($ch));
        }
        
        fclose($fh);
        \curl_close($ch);

        header("Content-type: application/xml");
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile(\mkw\store::storagePath('copydepokeszlet.xml'));
    }

}