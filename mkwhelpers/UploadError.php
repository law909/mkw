<?php

namespace mkwhelpers;

/**
 * Érthető hibaüzenetek a fájlfeltöltés tipikus szerveroldali hibáira. A médiatár, a
 * dokumentum fül és a vonalkód hang feltöltése közös: a PHP ezeknél az okot vagy csak
 * figyelmeztetésben adja meg, vagy (post_max_size, nem írható temp mappa) egyáltalán nem.
 */
class UploadError
{

    /** @return string|null a $_FILES[...]['error'] kód magyarázata, null ha nincs hiba */
    public static function uploadMessage($code)
    {
        if ($code === UPLOAD_ERR_OK) {
            return null;
        }
        $msg = [
            UPLOAD_ERR_INI_SIZE => 'A fájl nagyobb, mint a szerveren beállított upload_max_filesize ('
                . ini_get('upload_max_filesize') . ')',
            UPLOAD_ERR_FORM_SIZE => 'A fájl nagyobb a megengedettnél',
            UPLOAD_ERR_PARTIAL => 'A fájl csak részben töltődött fel',
            UPLOAD_ERR_NO_FILE => 'Nem érkezett fájl',
            UPLOAD_ERR_NO_TMP_DIR => 'Hiányzik az ideiglenes könyvtár a szerveren',
            UPLOAD_ERR_CANT_WRITE => 'A fájl nem írható a lemezre (a PHP ideiglenes mappája nem írható vagy betelt)',
            UPLOAD_ERR_EXTENSION => 'Egy PHP kiterjesztés megállította a feltöltést',
        ];
        return $msg[$code] ?? 'Ismeretlen feltöltési hiba';
    }

    /**
     * A post_max_size túllépésekor a PHP üres $_POST-ot ÉS $_FILES-t ad, figyelmeztetés nélkül.
     *
     * @return string|null
     */
    public static function postMaxSizeMessage()
    {
        $len = (int)($_SERVER['CONTENT_LENGTH'] ?? 0);
        $max = \mkw\thumbnail::returnBytes(ini_get('post_max_size'));
        if (empty($_FILES) && $max && $len > $max) {
            return 'A feltöltés mérete (' . \Services\MediatarService::formatSize($len) . ') meghaladja a szerveren '
                . 'beállított post_max_size értéket (' . ini_get('post_max_size') . ')';
        }
        return null;
    }

    /**
     * Ha a fájl nem érkezett meg, pedig a kérésnek volt törzse, a PHP nem tudta átvenni
     * (jellemzően nem írható vagy hiányzó ideiglenes mappa) – ez nem a felhasználó hibája.
     */
    public static function noFileMessage()
    {
        if ((int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
            return 'A szerver nem tudta átvenni a feltöltött fájlt (a PHP ideiglenes mappája hiányzik vagy nem írható). Szólj a rendszergazdának.';
        }
        return 'Nem érkezett fájl';
    }

    /**
     * Miért nem sikerült a mappába írni. A move_uploaded_file()/mkdir() előtt error_clear_last()
     * kell, különben egy korábbi figyelmeztetés szövege jönne vissza.
     *
     * @param string $where a felhasználónak mutatott mappanév (nem a szerveroldali útvonal)
     */
    public static function writeFailure($folder, $size, $where)
    {
        clearstatcache(true, $folder);
        if (!is_writable($folder)) {
            // a teljes útvonal kell: a pool más gyökeret vagy symlinket láthat, mint amit a shellben nézünk
            $real = realpath($folder) ?: $folder;
            $reason = 'a(z) ' . $where . ' mappa (' . $real . ') nem írható a webszerver számára';
            if (function_exists('posix_getpwuid') && function_exists('posix_geteuid')) {
                $owner = @fileowner($real);
                $group = @filegroup($real);
                $perms = @fileperms($real);
                $groups = array_map(
                    static fn($gid) => posix_getgrgid($gid)['name'] ?? $gid,
                    array_unique(array_merge([posix_getegid()], posix_getgroups() ?: []))
                );
                $reason .= ' (tulajdonos: ' . ($owner !== false ? (posix_getpwuid($owner)['name'] ?? $owner) : '?')
                    . ':' . ($group !== false ? (posix_getgrgid($group)['name'] ?? $group) : '?')
                    . ', jogok: ' . ($perms !== false ? substr(sprintf('%o', $perms), -4) : '?')
                    . ', a PHP felhasználója: ' . (posix_getpwuid(posix_geteuid())['name'] ?? posix_geteuid())
                    . ', csoportjai: ' . implode(', ', $groups) . ')';
            }
            return $reason;
        }
        $free = @disk_free_space($folder);
        if ($free !== false && $free < $size + 1048576) {
            return 'nincs elég szabad hely a szerver lemezén (' . \Services\MediatarService::formatSize((int)$free) . ' szabad)';
        }
        $last = error_get_last();
        if ($last) {
            // a PHP üzenet eleje a függvényhívás a teljes szerveroldali útvonallal – csak az ok kell
            $pos = strrpos($last['message'], '): ');
            return $pos !== false ? substr($last['message'], $pos + 3) : $last['message'];
        }
        return 'ismeretlen ok (' . $where . ')';
    }

    /**
     * PHP fatal (elfogyott memória, időtúllépés) esetén is legyen értelmes válasz.
     *
     * @param callable $respond function(string $message): a válasz kiírása
     */
    public static function respondOnFatal(callable $respond)
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
                    'A fájl feldolgozása közben elfogyott a szerver memóriája (memory_limit: %s). Próbáld kisebb felbontású képpel; ha a fájl mégis megjelenik a listában, a kicsinyített változatai hiányozhatnak.',
                    ini_get('memory_limit')
                );
            } elseif (stripos($e['message'], 'Maximum execution time') !== false) {
                $msg = sprintf('A fájl feldolgozása túl sokáig tartott (max_execution_time: %s mp).', ini_get('max_execution_time'));
            } else {
                $msg = 'A szerver a feldolgozás közben hibával leállt: ' . $e['message'];
            }
            $respond($msg);
        });
    }

}
