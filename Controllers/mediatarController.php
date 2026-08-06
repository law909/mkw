<?php

namespace Controllers;

/**
 * Médiatár – HTTP réteg. Minden fájlrendszer- és validációs logika a \Services\MediatarService-ban van.
 *
 * A route-nevek "admin"-nal kezdődnek, tehát az index.php már a session-hez kötötte a
 * kérést; a metódusonkénti guard azért kell, mert az index.php egy nem hitelesített
 * XHR-re 302-vel válaszol a login HTML-oldalra, amit a választó értelmezhetetlen
 * parse-hibaként renderelne. Hangosan bukjunk.
 */
class mediatarController extends \mkwhelpers\Controller
{

    /**
     * A médiatár böngésző oldala. Kétféleképpen nyílik:
     *  - a CKFinder-shim iframe-jében (?cb=N)
     *  - a CKEditor 3.6.1 valódi popup ablakában (?CKEditorFuncNum=N)
     * Ezért önálló HTML oldal, nem a base.tpl leszármazottja.
     */
    public function browse()
    {
        $this->requireAdmin(false);

        $type = $this->getType();
        $path = '/';
        $sel = $this->getOrig('sel', '');
        try {
            $mediatar = new \Services\MediatarService($type);
            // A path lehet mappa ('/termek/') vagy teljes fájl-útvonal
            // ('/termek/foo.jpg') is; az utóbbiból a fájlnév kijelölés lesz.
            // Érvénytelen vagy időközben törölt útvonal a gyökérben nyílik, nem hibázik.
            list($path, $derived) = $mediatar->resolveStartPath($this->getOrig('path', '/'));
            if ($sel === '' && $derived !== '') {
                $sel = $derived;
            }
        } catch (\Exception $e) {
            // Ismeretlen típus vagy hiányzó/rosszul konfigurált képgyökér.
            header('Content-Type: text/plain; charset=utf-8');
            echo t('A médiatár nem érhető el') . ': ' . $e->getMessage();
            return;
        }

        $view = $this->createView('mediatarbrowse.tpl');
        $view->setVar('pagetitle', t('Médiatár'));
        $view->setVar('mtype', $type);
        $view->setVar('mpath', $path);
        $view->setVar('msel', $sel);
        $view->setVar('mcb', (int)$this->params->getRequestParam('cb', 0));
        $view->setVar('mfuncnum', (int)$this->params->getRequestParam('CKEditorFuncNum', 0));
        $view->setVar('mmaxsize', $mediatar->getEffectiveMaxSize());
        $view->setVar('mmaxsizetext', \Services\MediatarService::formatSize($mediatar->getEffectiveMaxSize()));
        $view->setVar('mextensions', implode(', ', $mediatar->getAllowedExtensions()));
        $view->setVar('mwritable', !\mkw\store::isClosed());
        $view->setVar('mimgpostwarnings', \Services\MediatarService::checkImgPostParams());
        $view->printTemplateResult();
    }

    /**
     * Egy mappa tartalma JSON-ban.
     */
    public function jsonlist()
    {
        $this->requireAdmin();
        try {
            $mediatar = new \Services\MediatarService($this->getType());
            $res = $mediatar->listFolder($this->getOrig('path', '/'));
            $res['ok'] = true;
            $this->json($res);
        } catch (\Exception $e) {
            $this->jsonError($e->getMessage());
        }
    }

    /**
     * Lusta, igény szerinti bélyegkép. Csak akkor hívódik, ha a list végpont
     * fallback-lánca (_250 → _150 → maga a kép) nem talált semmit.
     */
    public function thumb()
    {
        $this->requireAdmin();
        try {
            $mediatar = new \Services\MediatarService($this->getType());
            $file = $mediatar->ensureThumb($this->getOrig('path', '/'), $this->getOrig('name', ''));
            if (!$file) {
                http_response_code(404);
                return;
            }
            $etag = '"' . md5($file . '|' . filemtime($file) . '|' . filesize($file)) . '"';
            if (($_SERVER['HTTP_IF_NONE_MATCH'] ?? '') === $etag) {
                http_response_code(304);
                return;
            }
            $info = @getimagesize($file);
            header('Content-Type: ' . ($info['mime'] ?? 'application/octet-stream'));
            header('Content-Length: ' . filesize($file));
            header('Cache-Control: private, max-age=86400');
            header('ETag: ' . $etag);
            readfile($file);
        } catch (\Exception $e) {
            http_response_code(404);
        }
    }

    /**
     * "Hol használják ezt a képet?" – csak olvasó, paraméterezett DQL. Törlés és
     * átnevezés előtt hívjuk, hogy a megerősítő kérdés őszinte legyen.
     */
    public function usage()
    {
        $this->requireAdmin();
        $url = $this->getOrig('url', '');
        if ($url === '') {
            $this->json(['count' => 0, 'where' => []]);
            return;
        }
        // A tárolt értékek hol vezető perjellel, hol anélkül szerepelnek.
        $variants = array_unique([$url, ltrim($url, '/')]);

        $sources = [
            [\Entities\TermekKep::class, 'url', t('Termékkép')],
            [\Entities\Termek::class, 'kepurl', t('Termék')],
            [\Entities\TermekFa::class, 'kepurl', t('Termékcsoport')],
            [\Entities\Cimketorzs::class, 'kepurl', t('Címke')],
        ];

        $count = 0;
        $where = [];
        foreach ($sources as $src) {
            list($entity, $field, $label) = $src;
            try {
                $n = (int)$this->getEm()->createQuery(
                    'SELECT COUNT(e.id) FROM ' . $entity . ' e WHERE e.' . $field . ' IN (:urls)'
                )
                    ->setParameter('urls', $variants)
                    ->getSingleScalarResult();
            } catch (\Exception $e) {
                $n = 0;
            }
            if ($n) {
                $count += $n;
                $where[] = $label . ': ' . $n;
            }
        }
        $this->json(['count' => $count, 'where' => $where]);
    }

    /**
     * Feltöltés a választóból. Kérésenként egy fájl – feltöltésenként hat GD-átméretezés,
     * egy 10 fájlos batch szétverné a max_execution_time-ot.
     */
    public function upload()
    {
        $this->requireAdmin();
        $this->requireWritable();
        $this->requireSameOrigin();
        try {
            $this->checkPostMaxSize();
            $mediatar = new \Services\MediatarService($this->getType());
            $file = $_FILES['file'] ?? null;
            if (!$file) {
                throw new \RuntimeException(t('Nem érkezett fájl'));
            }
            $res = $mediatar->upload($file, $this->getOrig('path', '/'));
            $res['ok'] = true;
            $this->json($res);
        } catch (\Exception $e) {
            $this->jsonError($e->getMessage());
        }
    }

    /**
     * A CKEditor 3.6.1 "Feltöltés" fülének végpontja. A mezőnév a CKEditor-ban
     * fixen "upload" (plugins/image/dialogs/image.js, plugins/link/dialogs/link.js),
     * a válasz pedig text/html, egy callFunction hívással.
     *
     * A form a CKEditor dialógusán belüli, azonos eredetű iframe-ben él, ezért
     * window.parent a helyes – NEM window.opener.
     */
    public function quickUpload()
    {
        $funcnum = (int)$this->params->getRequestParam('CKEditorFuncNum', 0);
        header('Content-Type: text/html; charset=utf-8');

        if (!\mkw\store::getAdminSession()->pk) {
            $this->ckCallback($funcnum, '', t('Nincs bejelentkezve'));
            return;
        }
        if (\mkw\store::isClosed()) {
            $this->ckCallback($funcnum, '', t('A rendszer zárolva van'));
            return;
        }
        try {
            $this->checkPostMaxSize();
            $mediatar = new \Services\MediatarService($this->getType());
            $file = $_FILES['upload'] ?? ($_FILES['file'] ?? null);
            if (!$file) {
                throw new \RuntimeException(t('Nem érkezett fájl'));
            }
            $res = $mediatar->upload($file, $this->getOrig('path', '/'));
            $this->ckCallback($funcnum, $res['url'], '');
        } catch (\Exception $e) {
            $this->ckCallback($funcnum, '', $e->getMessage());
        }
    }

    public function createFolder()
    {
        $this->requireAdmin();
        $this->requireWritable();
        $this->requireSameOrigin();
        try {
            $mediatar = new \Services\MediatarService($this->getType());
            $path = $mediatar->createFolder($this->getOrig('path', '/'), $this->getOrig('name', ''));
            $this->json(['ok' => true, 'path' => $path]);
        } catch (\Exception $e) {
            $this->jsonError($e->getMessage());
        }
    }

    /**
     * Átnevezés a teljes származék-családdal együtt. A CKFinder csak az egy fájlt
     * mozgatta, hat származékot árván hagyva.
     */
    public function rename()
    {
        $this->requireAdmin();
        $this->requireWritable();
        $this->requireSameOrigin();
        try {
            $mediatar = new \Services\MediatarService($this->getType());
            $newname = $mediatar->rename(
                $this->getOrig('path', '/'),
                $this->getOrig('name', ''),
                $this->getOrig('newname', '')
            );
            $this->json(['ok' => true, 'name' => $newname]);
        } catch (\Exception $e) {
            $this->jsonError($e->getMessage());
        }
    }

    /**
     * Törlés a teljes származék-családdal együtt.
     */
    public function delete()
    {
        $this->requireAdmin();
        $this->requireWritable();
        $this->requireSameOrigin();
        try {
            $mediatar = new \Services\MediatarService($this->getType());
            $cnt = $mediatar->delete($this->getOrig('path', '/'), $this->getOrigArray('names'));
            $this->json(['ok' => true, 'deleted' => $cnt]);
        } catch (\Exception $e) {
            $this->jsonError($e->getMessage());
        }
    }

    public function deleteFolder()
    {
        $this->requireAdmin();
        $this->requireWritable();
        $this->requireSameOrigin();
        try {
            $mediatar = new \Services\MediatarService($this->getType());
            $mediatar->deleteFolder($this->getOrig('path', '/'), $this->getOrig('name', ''));
            $this->json(['ok' => true]);
        } catch (\Exception $e) {
            $this->jsonError($e->getMessage());
        }
    }

    // ------------------------------------------------------------------
    // Segédek
    // ------------------------------------------------------------------

    /**
     * Nyers (HTMLPurifier nélküli) sztring-paraméter. A getStringRequestParam()
     * az &-et &amp;-re rontaná egy fájlrendszer-útvonalban.
     */
    private function getOrig($key, $default = '')
    {
        $v = $this->params->getOriginalStringRequestParam($key, $default);
        return is_string($v) ? $v : $default;
    }

    /**
     * Nyers tömb-paraméter (names[]). Ugyanaz az ok, mint a getOrig()-nál.
     */
    private function getOrigArray($key)
    {
        $all = $this->params->asArray();
        $v = $all['requestparams'][$key] ?? [];
        if (!is_array($v)) {
            $v = [$v];
        }
        $out = [];
        foreach ($v as $item) {
            if (is_string($item) && $item !== '') {
                $out[] = trim($item);
            }
        }
        return $out;
    }

    private function getType()
    {
        $t = $this->getOrig('type', 'Images');
        return $t === '' ? 'Images' : $t;
    }

    /**
     * @param bool $json 403 esetén JSON-t adjunk-e (XHR), vagy sima szöveget (oldal)
     */
    private function requireAdmin($json = true)
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

    private function requireWritable()
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
    private function requireSameOrigin()
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
    private function checkPostMaxSize()
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

    private function json($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    private function jsonError($msg)
    {
        $this->json(['ok' => false, 'error' => $msg]);
    }

    /**
     * A CKEditor 3.6.1 QuickUpload válasza. A funcNum (int)-tel castolva, az URL és az
     * üzenet json_encode()-dal kiírva – string-konkatenációval ez reflected XSS sink lenne.
     */
    private function ckCallback($funcnum, $url, $error)
    {
        echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('
            . (int)$funcnum . ', '
            . json_encode((string)$url, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ', '
            . json_encode((string)$error, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)
            . ');</script>';
    }
}
