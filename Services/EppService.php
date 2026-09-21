<?php

namespace Services;

use Doctrine\ORM\EntityManagerInterface;
use Entities\Eppjelszo;
use Entities\Eppnaplo;

/**
 * A WordPress "External Page Passwords" plugin jelszóvalidáló API-ja (/epp/validate, /epp/status).
 *
 * A szerződést a plugin rögzíti: hitelesített kérésre mindig 200 és {"valid": false|true, ...};
 * a plugin minden más választ "szolgáltatás nem elérhető"-nek mutat. A valid:false nem árulhatja
 * el, hogy az oldal létezik-e, vagy hogy a jelszó lejárt, visszavont vagy hibás — ez csak a naplóba
 * kerül.
 *
 * config.ini:
 *   epp.apikey       = a plugin EPP_API_KEY-je; üresen minden kérés 401
 *   epp.ratelimit    = 10   ; ennyi hibás jelszó után zár az oldal + látogató IP pár
 *   epp.ratewindow   = 15   ; percben
 *   epp.logretention = 90   ; napban, a CleanupTask törli a régebbi naplósorokat
 */
class EppService
{

    public const RATELIMIT = 10;
    public const RATEWINDOW = 15;
    public const LOGRETENTION = 90;

    private $em;
    private $apiKey;
    private $rateLimit;
    private $rateWindow;

    public function __construct(?EntityManagerInterface $em = null, ?string $apiKey = null)
    {
        $this->em = $em ?? \mkw\store::getEm();
        $this->apiKey = $apiKey ?? (string)\mkw\store::getConfigValue('epp.apikey', '');
        $this->rateLimit = (int)\mkw\store::getConfigValue('epp.ratelimit', self::RATELIMIT);
        $this->rateWindow = (int)\mkw\store::getConfigValue('epp.ratewindow', self::RATEWINDOW);
    }

    /**
     * @param string $vegpont Eppnaplo::VEGPONTVALIDATE | Eppnaplo::VEGPONTSTATUS
     * @param array $server a $_SERVER
     * @param string $body a nyers JSON kérés
     *
     * @return array [HTTP státusz, a válasz JSON tömbje]
     */
    public function handle(string $vegpont, array $server, string $body): array
    {
        if (!$this->isAuthorized($this->getAuthorizationHeader($server))) {
            return [401, ['error' => 'unauthorized']];
        }
        $data = json_decode($body, true);
        $oldalid = is_array($data) ? $this->toOldalid($data['page_id'] ?? null) : null;
        if ($oldalid === null) {
            return [400, ['error' => 'bad_request']];
        }
        $ip = $this->getVisitorIp($server);
        switch ($vegpont) {
            case Eppnaplo::VEGPONTVALIDATE:
                if (!is_string($data['password'] ?? null)) {
                    return [400, ['error' => 'bad_request']];
                }
                return [200, $this->validate($oldalid, $data['password'], $ip)];
            case Eppnaplo::VEGPONTSTATUS:
                if (!is_string($data['password_id'] ?? null)) {
                    return [400, ['error' => 'bad_request']];
                }
                return [200, $this->status($oldalid, $data['password_id'], $ip)];
            default:
                return [404, ['error' => 'not_found']];
        }
    }

    public function validate(int $oldalid, string $jelszo, string $ip): array
    {
        $naplorepo = $this->em->getRepository(Eppnaplo::class);
        $since = new \DateTime('-' . $this->rateWindow . ' minutes');
        if ($naplorepo->countFailures($oldalid, $ip, $since) >= $this->rateLimit) {
            $this->log(Eppnaplo::VEGPONTVALIDATE, $oldalid, Eppnaplo::EREDMENYKORLAT, $ip);
            return $this->invalid();
        }

        $eppjelszo = $this->em->getRepository(Eppjelszo::class)
            ->findAktivByHash($oldalid, Eppjelszo::hashJelszo($jelszo), new \DateTime());
        if ($eppjelszo) {
            return $this->valid($eppjelszo);
        }
        $this->log(Eppnaplo::VEGPONTVALIDATE, $oldalid, Eppnaplo::EREDMENYHIBAS, $ip);
        return $this->invalid();
    }

    public function status(int $oldalid, string $azonosito, string $ip): array
    {
        $eppjelszo = preg_match('/^[A-Za-z0-9_-]{1,64}$/', $azonosito)
            ? $this->em->getRepository(Eppjelszo::class)->findOneByAzonosito($azonosito)
            : null;
        switch (true) {
            case !$eppjelszo:
                $eredmeny = Eppnaplo::EREDMENYNINCS;
                break;
            case $eppjelszo->getOldalid() !== $oldalid:
                $eredmeny = Eppnaplo::EREDMENYMASOLDAL;
                break;
            case $eppjelszo->isVisszavonva():
                $eredmeny = Eppnaplo::EREDMENYVISSZAVONVA;
                break;
            case $eppjelszo->isLejart():
                $eredmeny = Eppnaplo::EREDMENYLEJART;
                break;
            default:
                return $this->valid($eppjelszo);
        }
        $this->log(Eppnaplo::VEGPONTSTATUS, $oldalid, $eredmeny, $ip, $azonosito);
        return $this->invalid();
    }

    public function purgeLog(): int
    {
        $napok = (int)\mkw\store::getConfigValue('epp.logretention', self::LOGRETENTION);
        if ($napok <= 0) {
            return 0;
        }
        return $this->em->getRepository(Eppnaplo::class)->deleteOlderThan($napok);
    }

    private function isAuthorized(?string $authorization): bool
    {
        if ($this->apiKey === '' || !$authorization || !preg_match('/^Bearer\s+(\S+)$/i', trim($authorization), $m)) {
            return false;
        }
        return hash_equals($this->apiKey, $m[1]);
    }

    private function getAuthorizationHeader(array $server): ?string
    {
        // Apache + php-fpm alatt csak a .htaccess RewriteRule-ja adja át, és a belső átírás után REDIRECT_ előtaggal
        return $server['HTTP_AUTHORIZATION'] ?? $server['REDIRECT_HTTP_AUTHORIZATION'] ?? null;
    }

    /**
     * A plugin szerverről hív, a REMOTE_ADDR a WP szerveré; a látogatóét X-Visitor-IP-ben küldi.
     * A hívó kulccsal hitelesített, ezért a fejlécnek hihetünk.
     */
    private function getVisitorIp(array $server): string
    {
        $ip = trim((string)($server['HTTP_X_VISITOR_IP'] ?? ''));
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
        return (string)($server['REMOTE_ADDR'] ?? 'unknown');
    }

    private function toOldalid($value): ?int
    {
        if (is_int($value)) {
            return $value;
        }
        if (is_string($value) && ctype_digit($value)) {
            return (int)$value;
        }
        return null;
    }

    private function valid(Eppjelszo $eppjelszo): array
    {
        return [
            'valid' => true,
            'password_id' => $eppjelszo->getAzonosito(),
            'expires_at' => $eppjelszo->getLejaratTimestamp(),
        ];
    }

    private function invalid(): array
    {
        return ['valid' => false];
    }

    private function log(string $vegpont, int $oldalid, string $eredmeny, string $ip, ?string $azonosito = null)
    {
        $this->em->persist(new Eppnaplo($vegpont, $oldalid, $eredmeny, $ip, $azonosito));
        $this->em->flush();
    }

}
