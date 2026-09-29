<?php

namespace Traits;

/**
 * A médiatár HTTP végpontjainak közös őrei. Azért trait, mert a dokumentumok
 * „Azonnali feltöltés” végpontja (dokumentumtarController) ugyanezt a védelmet
 * igényli, de nem médiatár-művelet, és a `mediatar` kapcsolótól függetlenül él.
 */
trait MediatarGuard
{

    /**
     * @param bool $json 403 esetén JSON-t adjunk-e (XHR), vagy sima szöveget (oldal)
     */
    protected function requireAdmin($json = true)
    {
        if (\mkw\store::getAdminSession()->pk) {
            return;
        }
        if ($json) {
            $this->jsonError(t('Nincs bejelentkezve'), 403);
        } else {
            http_response_code(403);
            header('Content-Type: text/plain; charset=utf-8');
            echo t('Nincs bejelentkezve');
        }
        exit;
    }

    protected function requireWritable()
    {
        if (!\mkw\store::isClosed()) {
            return;
        }
        $this->jsonError(t('A rendszer zárolva van'), 403);
        exit;
    }

    /**
     * Olcsó, helyi CSRF-védelem a mutáló végpontokon: az Origin/Referer hosztjának
     * egyeznie kell a kérés hosztjával. Az alkalmazásban sehol nincs CSRF-token,
     * ezt globálisan javítani nem fér a hatókörbe.
     *
     * Alapból CSAK NAPLÓZ (setup.ini: mediatarstrictorigin = 1 élesíti) – így egy
     * proxy vagy egy fejlécet szűrő böngésző nem töri el a bevezetést.
     */
    protected function requireSameOrigin()
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        $referer = $_SERVER['HTTP_REFERER'] ?? '';

        $src = $origin ?: $referer;
        if ($src === '') {
            $ok = false;
        } else {
            $srchost = parse_url($src, PHP_URL_HOST);
            $port = parse_url($src, PHP_URL_PORT);
            if ($port) {
                $srchost .= ':' . $port;
            }
            $ok = ($srchost !== null && strcasecmp($srchost, $host) === 0);
        }
        if ($ok) {
            return;
        }
        $msg = date('Y-m-d H:i:s') . " mediatar idegen eredet: host=$host origin=$origin referer=$referer"
            . ' uri=' . ($_SERVER['REQUEST_URI'] ?? '') . "\n";
        @file_put_contents(\mkw\store::logsPath('mediatar.log'), $msg, FILE_APPEND);

        if (\mkw\store::getSetupValue('mediatarstrictorigin')) {
            $this->jsonError(t('Érvénytelen kérés eredete'), 403);
            exit;
        }
    }

    /** @throws \RuntimeException */
    protected function checkPostMaxSize()
    {
        $msg = \mkwhelpers\UploadError::postMaxSizeMessage();
        if ($msg !== null) {
            throw new \RuntimeException($msg);
        }
    }

    protected function noFileMessage()
    {
        return \mkwhelpers\UploadError::noFileMessage();
    }

    protected function respondOnFatal(callable $respond)
    {
        \mkwhelpers\UploadError::respondOnFatal($respond);
    }

    /**
     * Sikeres válasz. A művelet nem sikerült ágán \mkwhelpers\Controller::jsonFail() jár:
     * a választó a 200-as válasz `ok`/`error` kulcsait olvassa, a jsonError() 4xx-e ott
     * néhány gombnál (mappa létrehozás, átnevezés, törlés) néma hibába futna.
     */
    protected function json($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

}
