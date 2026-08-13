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
        http_response_code(403);
        if ($json) {
            $this->jsonError(t('Nincs bejelentkezve'));
        } else {
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
        http_response_code(403);
        $this->jsonError(t('A rendszer zárolva van'));
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
            http_response_code(403);
            $this->jsonError(t('Érvénytelen kérés eredete'));
            exit;
        }
    }

    /**
     * A post_max_size túllépésekor a PHP üres $_POST-ot ÉS $_FILES-t ad, figyelmeztetés
     * nélkül. Explicit ellenőrzés nélkül ez rejtélyes hiba a felületen.
     */
    protected function checkPostMaxSize()
    {
        $len = (int)($_SERVER['CONTENT_LENGTH'] ?? 0);
        $max = \mkw\thumbnail::returnBytes(ini_get('post_max_size'));
        if (empty($_FILES) && $max && $len > $max) {
            throw new \RuntimeException(
                'A feltöltés mérete (' . \Services\MediatarService::formatSize($len) . ') meghaladja a szerveren '
                . 'beállított post_max_size értéket (' . ini_get('post_max_size') . ')'
            );
        }
    }

    protected function json($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    protected function jsonError($msg)
    {
        $this->json(['ok' => false, 'error' => $msg]);
    }

}
