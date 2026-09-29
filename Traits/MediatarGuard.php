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

    /**
     * Ha a fájl nem érkezett meg, pedig a kérésnek volt törzse, a PHP nem tudta átvenni
     * (jellemzően nem írható vagy hiányzó ideiglenes mappa) – ez nem a felhasználó hibája.
     */
    protected function noFileMessage()
    {
        if ((int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
            return t('A szerver nem tudta átvenni a feltöltött fájlt (a PHP ideiglenes mappája hiányzik vagy nem írható). Szólj a rendszergazdának.');
        }
        return t('Nem érkezett fájl');
    }

    /**
     * PHP fatal (elfogyott memória, időtúllépés) esetén is legyen értelmes válasz: a nagy
     * képek kicsinyítése a GD-vel jellemzően a memory_limit-en bukik el.
     *
     * @param callable $respond function(string $message): a válasz kiírása
     */
    protected function respondOnFatal(callable $respond)
    {
        register_shutdown_function(function () use ($respond) {
            $e = error_get_last();
            if (!$e || !in_array($e['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE], true)) {
                return;
            }
            while (ob_get_level() > 0) {
                @ob_end_clean();
            }
            if (stripos($e['message'], 'Allowed memory size') !== false) {
                $msg = sprintf(
                    t('A fájl feldolgozása közben elfogyott a szerver memóriája (memory_limit: %s). Próbáld kisebb felbontású képpel; ha a fájl mégis megjelenik a listában, a kicsinyített változatai hiányozhatnak.'),
                    ini_get('memory_limit')
                );
            } elseif (stripos($e['message'], 'Maximum execution time') !== false) {
                $msg = sprintf(
                    t('A fájl feldolgozása túl sokáig tartott (max_execution_time: %s mp).'),
                    ini_get('max_execution_time')
                );
            } else {
                $msg = t('A szerver a feldolgozás közben hibával leállt') . ': ' . $e['message'];
            }
            $respond($msg);
        });
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
