<?php

namespace mkwhelpers;

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
     * A `fields` (mezőnév => üzenet) csak akkor van kitöltve, ha a hiba forrása ismerte a
     * mezőt – az adatbázis felől érkező hibákból nem lehet visszafejteni.
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
            return [
                'message' => t('A rekord nem törölhető, mert máshol hivatkozás mutat rá.'),
                'status' => 409,
                'fields' => [],
            ];
        }
        if ($e instanceof NotNullConstraintViolationException) {
            return ['message' => t('Kötelező mező maradt üresen.'), 'status' => 400, 'fields' => []];
        }
        if ($e instanceof RetryableException) {
            return [
                'message' => t('Az adatbázis pillanatnyilag foglalt volt, kérem próbálja meg újra.'),
                'status' => 409,
                'fields' => [],
            ];
        }
        return ['message' => self::unexpected($e, $id), 'status' => 500, 'fields' => []];
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
        if (preg_match("/Duplicate entry '(.*)' for key/", $e->getMessage(), $m)) {
            return $m[1];
        }
        return '';
    }

}
