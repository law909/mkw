<?php

namespace mkwhelpers;

use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\DBAL\Exception\RetryableException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use mkwhelpers\Exceptions\UserMessageException;

/**
 * Kivétel -> a felhasználónak megmutatható üzenet és HTTP státuszkód.
 *
 * A flush közben elszállt kivétel után a Doctrine lezárja az EntityManagert, ezért innen
 * semmi nem nyúlhat az ORM-hez: az üzenet csak a kivétel szövegéből és a munkamenetből épül.
 */
class ErrorMessage
{

    const LOGFILE = 'hiba.log';

    /**
     * A `fields` (mezőnév => üzenet) a form mezőjelölését hajtja: a kulcsnak a beviteli mező
     * name attribútumával kell egyeznie. A MySQL hibaüzenete oszlopnevet ad, ami a legtöbb
     * mezőnél ugyanaz – a join oszlop (dolgozo_id) mellé a reláció neve (dolgozo) is bekerül.
     *
     * @return array{message: string, status: int, fields: array}
     */
    public static function toUserMessage(\Throwable $e)
    {
        $id = strtoupper(substr(md5(uniqid('', true)), 0, 6));
        self::log($id, $e);
        if ($e instanceof UserMessageException) {
            return ['message' => $e->getMessage(), 'status' => 400, 'fields' => $e->getFields()];
        }
        if ($e instanceof UniqueConstraintViolationException) {
            $ertek = self::getDuplicateValue($e);
            return [
                'fields' => [],
                'message' => $ertek === ''
                    ? t('Ez az érték már szerepel egy másik rekordon.')
                    : sprintf(t('Ez az érték már szerepel egy másik rekordon: "%s".'), $ertek),
                'status' => 409,
            ];
        }
        if ($e instanceof ForeignKeyConstraintViolationException) {
            return self::foreignKey($e);
        }
        if ($e instanceof NotNullConstraintViolationException) {
            $names = self::fieldNames(self::match($e, "/Column '([^']+)' cannot be null/"));
            return [
                'message' => $names
                    ? sprintf(t('Kötelező mező maradt üresen: "%s".'), end($names))
                    : t('Kötelező mező maradt üresen.'),
                'status' => 400,
                'fields' => array_fill_keys($names, t('Kötelező mező.')),
            ];
        }
        if ($e instanceof RetryableException) {
            return [
                'message' => t('Az adatbázis pillanatnyilag foglalt volt, kérem próbálja meg újra.'),
                'status' => 409,
                'fields' => [],
            ];
        }
        if ($e instanceof DriverException) {
            $handled = self::driverError($e);
            if ($handled) {
                return $handled;
            }
        }
        return ['message' => self::unexpected($e, $id), 'status' => 500, 'fields' => []];
    }

    /**
     * A DBAL nem ad saját kivételosztályt a túl hosszú (1406), a mező tartományán kívüli
     * (1264) és a rossz formátumú (1292) értékre, pedig mind a formon javítható – a MySQL
     * kódjából ismerjük fel. A többi kód marad ismeretlen hibának.
     */
    private static function driverError(DriverException $e)
    {
        switch ($e->getCode()) {
            case 1406:
                $withColumn = t('Túl hosszú érték került a(z) "%s" mezőbe.');
                $general = t('Az egyik mezőbe túl hosszú érték került.');
                $fieldMessage = t('Túl hosszú érték.');
                break;
            case 1264:
                $withColumn = t('A(z) "%s" mezőbe írt érték kívül esik a megengedett tartományon.');
                $general = t('Az egyik mezőbe írt érték kívül esik a megengedett tartományon.');
                $fieldMessage = t('Az érték kívül esik a megengedett tartományon.');
                break;
            case 1292:
                $withColumn = t('A(z) "%s" mezőbe írt érték formátuma nem megfelelő.');
                $general = t('Az egyik mezőbe írt érték formátuma nem megfelelő.');
                $fieldMessage = t('Nem megfelelő formátumú érték.');
                break;
            default:
                return null;
        }
        $column = self::match($e, "/for column '([^']+)'/");
        return [
            'message' => $column === '' ? $general : sprintf($withColumn, $column),
            'status' => 400,
            'fields' => array_fill_keys(self::fieldNames($column), $fieldMessage),
        ];
    }

    /**
     * Két különböző eset ugyanazzal a kivétellel: a törlést a rá hivatkozó tábla akadályozza
     * ("parent row"), a mentést pedig a nem létező hivatkozott rekord ("child row").
     */
    private static function foreignKey(\Throwable $e)
    {
        if (strpos($e->getMessage(), 'child row') !== false) {
            $names = self::fieldNames(self::match($e, '/FOREIGN KEY \(`([^`]+)`\)/'));
            $tabla = self::match($e, '/REFERENCES `([^`]+)`/');
            return [
                'message' => $tabla === ''
                    ? t('A megadott hivatkozás nem létező rekordra mutat.')
                    : sprintf(t('A megadott hivatkozás nem létező rekordra mutat: "%s".'), $tabla),
                'status' => 400,
                'fields' => array_fill_keys($names, t('Nem létező rekordra hivatkozik.')),
            ];
        }
        $tabla = self::match($e, '/constraint fails \((?:`[^`]+`\.)?`([^`]+)`/');
        return [
            'message' => $tabla === ''
                ? t('A rekord nem törölhető, mert máshol hivatkozás mutat rá.')
                : sprintf(t('A rekord nem törölhető, mert hivatkozás mutat rá innen: "%s".'), $tabla),
            'status' => 409,
            'fields' => [],
        ];
    }

    /**
     * A form a relációt a saját nevén ismeri (dolgozo), a hibaüzenet viszont a join oszlopot
     * mondja (dolgozo_id) – mindkettő bekerül, a nem létező mezőnevet a jelölő JS kihagyja.
     */
    private static function fieldNames($column)
    {
        if ($column === '') {
            return [];
        }
        $names = [$column];
        if (substr($column, -3) === '_id') {
            $names[] = substr($column, 0, -3);
        }
        return $names;
    }

    private static function match(\Throwable $e, $pattern)
    {
        if (preg_match($pattern, $e->getMessage(), $m)) {
            return $m[1];
        }
        return '';
    }

    /**
     * Az ismeretlen hiba után a felhasználó csak egy azonosítót lát, amit be tud diktálni –
     * a naplóban ugyanez az azonosító áll. Fejlesztői módban a valódi üzenet is odakerül.
     */
    private static function unexpected(\Throwable $e, $id)
    {
        $message = sprintf(t('A művelet nem sikerült. Hibaazonosító: %s'), $id);
        if (\mkw\store::isDeveloper()) {
            $message .= ' - ' . get_class($e) . ': ' . $e->getMessage();
        }
        return $message;
    }

    /**
     * A kezelt (felhasználónak fordított) hibák is naplóba mennek, mert a fordítás elrejti,
     * mi történt valójában. A kivétellánc végigjárva kerül bele: a Doctrine üzenete mögött
     * a driver eredeti hibája (SQL, paraméterek) áll.
     */
    private static function log($id, \Throwable $e)
    {
        $parts = [
            $id,
            $_SERVER['REQUEST_URI'] ?? '',
            self::getUserName(),
        ];
        for ($t = $e; $t !== null; $t = $t->getPrevious()) {
            $parts[] = get_class($t) . ': ' . self::oneLine($t->getMessage());
            $parts[] = $t->getFile() . ':' . $t->getLine();
        }
        \mkw\store::writelog(implode(' ## ', $parts), self::LOGFILE);
    }

    /** A napló soralapú, a DBAL üzenetében viszont több soros SQL is lehet. */
    private static function oneLine($text)
    {
        return trim(preg_replace('/\s+/', ' ', $text));
    }

    /**
     * Nem a getLoggedInDolgozoNev(): az lekérdez, ez pedig lezárt EntityManager mellett is fut.
     * A hibakezelő maga nem dobhat, ezért a munkamenet elérése is védve van (CLI-ből pl.
     * "headers already sent"-tel elszállna).
     */
    private static function getUserName()
    {
        try {
            $lu = \mkw\store::getAdminSession()->loggedinuser;
            return (is_array($lu) && !empty($lu['name'])) ? (string)$lu['name'] : '';
        } catch (\Throwable $e) {
            return '';
        }
    }

    /** "Duplicate entry 'XL' for key 'meret.UNIQ_...'" -> XL */
    private static function getDuplicateValue(\Throwable $e)
    {
        return self::match($e, "/Duplicate entry '(.*)' for key/");
    }

}
