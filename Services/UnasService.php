<?php

namespace Services;

use Entities\Apierrorlog;

/**
 * Az UNAS integráció közös belépési pontja: API kliens, beállítások, hibanaplózás.
 * Lásd docs/unas-integracio.md.
 */
class UnasService
{

    public const ERRORTYPE = 'unas';

    /** @var \mkwhelpers\UnasAPI|null */
    private $api;

    private $settings;

    /**
     * @param array $settings apiurl/apikey felülírás. A setup képernyő tesztje a MÉG NEM MENTETT
     *                        értékeket adja át – az UNAS óránként csak 5 sikertelen logint enged.
     */
    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    public static function isEnabled()
    {
        return (bool)\mkw\store::isUnas()
            && trim((string)\mkw\store::getParameter(\mkw\consts::UnasApiKey, '')) !== '';
    }

    /**
     * Példányonként egy kliens, hogy a token ne vesszen el. Teszt-varrat: felülírható.
     *
     * @return \mkwhelpers\UnasAPI
     */
    public function getApi()
    {
        if (!$this->api) {
            $this->api = new \mkwhelpers\UnasAPI([
                'apiurl' => $this->settings['apiurl'] ?? \mkw\store::getParameter(\mkw\consts::UnasApiUrl, ''),
                'apikey' => $this->settings['apikey'] ?? \mkw\store::getParameter(\mkw\consts::UnasApiKey, ''),
            ]);
        }
        return $this->api;
    }

    /**
     * Friss login: a jogosultságlista csak a login válaszában van, ezért dobjuk el a tokent.
     *
     * @return array{ok: bool, shopid: string, subscription: string, permissions: array,
     *               hianyzo: array, hiba: string}
     */
    public function testConnection()
    {
        $api = $this->getApi();
        if (!$api->isConfigured()) {
            return $this->testResult(false, [], t('Nincs beállítva az UNAS API URL vagy API kulcs.'));
        }

        $api->clearToken();
        $info = $api->login();
        if (!$info) {
            return $this->testResult(false, [], $api->getLasterrorsAsString());
        }

        // az 1. fázishoz ez a két végpont kell
        $required = ['getProductDB', 'getProduct'];
        $lowerCased = array_map('strtolower', $info['permissions']);
        $missing = [];
        foreach ($required as $k) {
            if (!in_array(strtolower($k), $lowerCased, true)) {
                $missing[] = $k;
            }
        }

        return [
            'ok' => true,
            'shopid' => $info['shopid'],
            'subscription' => $info['subscription'],
            'permissions' => $info['permissions'],
            'hianyzo' => $missing,
            'hiba' => '',
        ];
    }

    private function testResult($ok, array $permissions, $error)
    {
        return [
            'ok' => $ok,
            'shopid' => '',
            'subscription' => '',
            'permissions' => $permissions,
            'hianyzo' => [],
            'hiba' => $error,
        ];
    }

    /**
     * Összevont hibasorokra való: importonként egy bejegyzés, nem termékenként.
     * Flush-ol, ezért csak élő EntityManagerrel hívható.
     */
    public function logApiError($message, $objectid = null, $type = self::ERRORTYPE)
    {
        \mkw\store::writelog($message, 'unas_api_error.txt');

        $em = \mkw\store::getEm();
        if (!$em->isOpen()) {
            return false;
        }
        try {
            $log = new Apierrorlog();
            $log->setType($type);
            $log->setObjectid($objectid);
            $log->setMessage($message);
            $log->setClosed(false);
            $em->persist($log);
            $em->flush($log);
            return $log;
        } catch (\Exception $e) {
            \mkw\store::writelog('Az Apierrorlog sor nem menthető: ' . $e->getMessage(), 'unas_api_error.txt');
            return false;
        }
    }

    // ------------------------------------------------------------------
    // Beállítások
    // ------------------------------------------------------------------

    public static function getLang()
    {
        return trim((string)\mkw\store::getParameter(\mkw\consts::UnasNyelv, '')) ?: 'hu';
    }

    /** A `_l1` az MKW-ban fixen az `en_us` locale, innen az `en` alapérték. */
    public static function getLangL1()
    {
        return trim((string)\mkw\store::getParameter(\mkw\consts::UnasNyelvL1, '')) ?: 'en';
    }

    /** Saját almappa, hogy az UNAS-ból jött képek elkülöníthetők és újratölthetők legyenek. */
    public static function getKepPath()
    {
        $p = trim((string)\mkw\store::getParameter(\mkw\consts::UnasKepPath, ''));
        if ($p === '') {
            $p = \mkw\store::getConfigValue('path.termekkep', 'kepek/termek/') . 'unas/';
        }
        return rtrim($p, '/') . '/';
    }

    public static function getKepUrlPrefix()
    {
        $p = trim((string)\mkw\store::getParameter(\mkw\consts::UnasKepUrlPrefix, ''));
        if ($p === '') {
            $p = self::getKepPath();
        }
        return rtrim($p, '/') . '/';
    }

}
