<?php

namespace Tests;

use Entities\Eppjelszo;
use Entities\Eppnaplo;
use Services\EppService;

class EppServiceTest extends DatabaseTestCase
{

    private const APIKEY = 'test-api-key-0123456789';
    private const PAGE = 123;
    private const OTHERPAGE = 456;

    protected function getEntityClasses(): array
    {
        return [Eppjelszo::class, Eppnaplo::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
        \mkw\store::setConfig([]);
    }

    public function testCorrectPasswordIsValid(): void
    {
        [$eppjelszo, $jelszo] = $this->createPassword(self::PAGE);

        [$status, $payload] = $this->requestValidate(self::PAGE, $jelszo);

        $this->assertSame(200, $status);
        $this->assertSame(
            ['valid' => true, 'password_id' => $eppjelszo->getAzonosito(), 'expires_at' => $eppjelszo->getLejarat()->getTimestamp()],
            $payload
        );
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9_-]{1,64}$/', $payload['password_id']);
    }

    public function testEachActivePasswordOfThePageIsValid(): void
    {
        [$first, $firstJelszo] = $this->createPassword(self::PAGE);
        [$second, $secondJelszo] = $this->createPassword(self::PAGE, '+2 days');

        $this->assertSame($first->getAzonosito(), $this->requestValidate(self::PAGE, $firstJelszo)[1]['password_id']);
        $this->assertSame($second->getAzonosito(), $this->requestValidate(self::PAGE, $secondJelszo)[1]['password_id']);
    }

    public function testWrongPasswordIsInvalid(): void
    {
        $this->createPassword(self::PAGE);

        $this->assertSame([200, ['valid' => false]], $this->requestValidate(self::PAGE, 'wrong-password'));
    }

    public function testExpiredPasswordIsInvalid(): void
    {
        [, $jelszo] = $this->createPassword(self::PAGE, '-1 minute');

        $this->assertSame([200, ['valid' => false]], $this->requestValidate(self::PAGE, $jelszo));
    }

    public function testRevokedPasswordIsInvalid(): void
    {
        [, $jelszo] = $this->createPassword(self::PAGE, '+1 day', true);

        $this->assertSame([200, ['valid' => false]], $this->requestValidate(self::PAGE, $jelszo));
    }

    public function testPasswordOfAnotherPageIsInvalid(): void
    {
        $this->createPassword(self::PAGE);
        [, $otherJelszo] = $this->createPassword(self::OTHERPAGE);

        $this->assertSame([200, ['valid' => false]], $this->requestValidate(self::PAGE, $otherJelszo));
    }

    public function testUnknownPageGetsTheSameAnswer(): void
    {
        $this->assertSame([200, ['valid' => false]], $this->requestValidate(999, 'anything'));
    }

    public function testMissingApiKeyIsUnauthorized(): void
    {
        [, $jelszo] = $this->createPassword(self::PAGE);

        $this->assertSame(401, $this->request('validate', ['page_id' => self::PAGE, 'password' => $jelszo], [])[0]);
        $this->assertSame(401, $this->request('status', ['page_id' => self::PAGE, 'password_id' => 'x'], [])[0]);
    }

    /**
     * @dataProvider wrongAuthorizationProvider
     */
    public function testWrongApiKeyIsUnauthorized(string $authorization): void
    {
        [, $jelszo] = $this->createPassword(self::PAGE);

        [$status, $payload] = $this->request(
            'validate',
            ['page_id' => self::PAGE, 'password' => $jelszo],
            ['HTTP_AUTHORIZATION' => $authorization]
        );

        $this->assertSame(401, $status);
        $this->assertArrayNotHasKey('valid', $payload);
    }

    public static function wrongAuthorizationProvider(): array
    {
        return [
            'other key' => ['Bearer other-key'],
            'key prefix' => ['Bearer ' . substr(self::APIKEY, 0, -1)],
            'basic scheme' => ['Basic ' . self::APIKEY],
            'no scheme' => [self::APIKEY],
            'empty bearer' => ['Bearer '],
        ];
    }

    public function testEmptyConfiguredApiKeyRejectsEveryRequest(): void
    {
        $service = new EppService($this->em, '');

        [$status] = $service->handle('validate', ['HTTP_AUTHORIZATION' => 'Bearer '], json_encode(['page_id' => 1, 'password' => 'x']));

        $this->assertSame(401, $status);
    }

    public function testAuthorizationPassedByApacheRewriteIsAccepted(): void
    {
        [, $jelszo] = $this->createPassword(self::PAGE);

        [$status, $payload] = $this->request(
            'validate',
            ['page_id' => self::PAGE, 'password' => $jelszo],
            ['REDIRECT_HTTP_AUTHORIZATION' => 'Bearer ' . self::APIKEY]
        );

        $this->assertSame(200, $status);
        $this->assertTrue($payload['valid']);
    }

    /**
     * @dataProvider malformedBodyProvider
     */
    public function testMalformedRequestIsBadRequest(string $vegpont, string $body): void
    {
        $service = new EppService($this->em, self::APIKEY);

        [$status] = $service->handle($vegpont, ['HTTP_AUTHORIZATION' => 'Bearer ' . self::APIKEY], $body);

        $this->assertSame(400, $status);
    }

    public static function malformedBodyProvider(): array
    {
        return [
            'not json' => ['validate', 'page_id=1&password=x'],
            'json scalar' => ['validate', '"x"'],
            'page_id missing' => ['validate', '{"password":"x"}'],
            'page_id not integer' => ['validate', '{"page_id":"12a","password":"x"}'],
            'password missing' => ['validate', '{"page_id":1}'],
            'password not string' => ['validate', '{"page_id":1,"password":123}'],
            'password_id missing' => ['status', '{"page_id":1}'],
        ];
    }

    public function testStatusOfActivePasswordIsValid(): void
    {
        [$eppjelszo] = $this->createPassword(self::PAGE);

        [$status, $payload] = $this->requestStatus(self::PAGE, $eppjelszo->getAzonosito());

        $this->assertSame(200, $status);
        $this->assertSame(
            ['valid' => true, 'password_id' => $eppjelszo->getAzonosito(), 'expires_at' => $eppjelszo->getLejarat()->getTimestamp()],
            $payload
        );
    }

    public function testStatusWithOtherPageIdIsInvalid(): void
    {
        [$eppjelszo] = $this->createPassword(self::PAGE);

        $this->assertSame([200, ['valid' => false]], $this->requestStatus(self::OTHERPAGE, $eppjelszo->getAzonosito()));
        $this->assertSame(Eppnaplo::EREDMENYMASOLDAL, $this->getLogRows()[0]['eredmeny']);
    }

    public function testStatusOfExpiredPasswordIsInvalid(): void
    {
        [$eppjelszo] = $this->createPassword(self::PAGE, '-1 minute');

        $this->assertSame([200, ['valid' => false]], $this->requestStatus(self::PAGE, $eppjelszo->getAzonosito()));
        $this->assertSame(Eppnaplo::EREDMENYLEJART, $this->getLogRows()[0]['eredmeny']);
    }

    public function testStatusOfRevokedPasswordIsInvalid(): void
    {
        [$eppjelszo] = $this->createPassword(self::PAGE, '+1 day', true);

        $this->assertSame([200, ['valid' => false]], $this->requestStatus(self::PAGE, $eppjelszo->getAzonosito()));
        $this->assertSame(Eppnaplo::EREDMENYVISSZAVONVA, $this->getLogRows()[0]['eredmeny']);
    }

    /**
     * @dataProvider unknownPasswordIdProvider
     */
    public function testStatusOfUnknownPasswordIdIsInvalid(string $azonosito): void
    {
        $this->createPassword(self::PAGE);

        $this->assertSame([200, ['valid' => false]], $this->requestStatus(self::PAGE, $azonosito));
    }

    public static function unknownPasswordIdProvider(): array
    {
        return [
            'well formed' => [str_repeat('a', 32)],
            'empty' => [''],
            'bad characters' => ["x' OR 1=1"],
            'too long' => [str_repeat('a', 65)],
        ];
    }

    public function testRateLimitRejectsEvenTheCorrectPassword(): void
    {
        \mkw\store::setConfig(['epp.ratelimit' => 3]);
        [, $jelszo] = $this->createPassword(self::PAGE);
        $visitor = ['HTTP_X_VISITOR_IP' => '203.0.113.5'];

        for ($i = 0; $i < 3; $i++) {
            $this->requestValidate(self::PAGE, 'wrong', $visitor);
        }

        $this->assertSame([200, ['valid' => false]], $this->requestValidate(self::PAGE, $jelszo, $visitor));
        $this->assertTrue($this->requestValidate(self::PAGE, $jelszo, ['HTTP_X_VISITOR_IP' => '203.0.113.6'])[1]['valid']);
        $this->assertSame(Eppnaplo::EREDMENYKORLAT, $this->getLogRows()[3]['eredmeny']);
    }

    public function testRateLimitIsPerPage(): void
    {
        \mkw\store::setConfig(['epp.ratelimit' => 3]);
        [, $otherJelszo] = $this->createPassword(self::OTHERPAGE);
        $visitor = ['HTTP_X_VISITOR_IP' => '203.0.113.5'];

        for ($i = 0; $i < 3; $i++) {
            $this->requestValidate(self::PAGE, 'wrong', $visitor);
        }

        $this->assertTrue($this->requestValidate(self::OTHERPAGE, $otherJelszo, $visitor)[1]['valid']);
    }

    public function testRateLimitFallsBackToCallerIpWithoutValidVisitorIp(): void
    {
        \mkw\store::setConfig(['epp.ratelimit' => 2]);
        [, $jelszo] = $this->createPassword(self::PAGE);
        $wpServer = ['REMOTE_ADDR' => '192.0.2.10', 'HTTP_X_VISITOR_IP' => 'not-an-ip'];

        $this->requestValidate(self::PAGE, 'wrong', $wpServer);
        $this->requestValidate(self::PAGE, 'wrong', $wpServer);

        $this->assertFalse($this->requestValidate(self::PAGE, $jelszo, $wpServer)[1]['valid']);
        $this->assertSame('192.0.2.10', $this->getLogRows()[0]['ip']);
    }

    public function testFailuresOutsideTheWindowDoNotCount(): void
    {
        \mkw\store::setConfig(['epp.ratelimit' => 2, 'epp.ratewindow' => 15]);
        [, $jelszo] = $this->createPassword(self::PAGE);
        $visitor = ['HTTP_X_VISITOR_IP' => '203.0.113.5'];

        $this->requestValidate(self::PAGE, 'wrong', $visitor);
        $this->requestValidate(self::PAGE, 'wrong', $visitor);
        $this->em->getConnection()->executeStatement(
            'UPDATE eppnaplo SET created = ?',
            [(new \DateTime('-16 minutes'))->format('Y-m-d H:i:s')]
        );

        $this->assertTrue($this->requestValidate(self::PAGE, $jelszo, $visitor)[1]['valid']);
    }

    public function testFailedAttemptIsLoggedWithoutThePassword(): void
    {
        $this->createPassword(self::PAGE);

        $this->requestValidate(self::PAGE, 'secret-attempt-42', ['HTTP_X_VISITOR_IP' => '203.0.113.5']);

        $rows = $this->getLogRows();
        $this->assertCount(1, $rows);
        $this->assertSame(Eppnaplo::VEGPONTVALIDATE, $rows[0]['vegpont']);
        $this->assertSame(self::PAGE, (int)$rows[0]['oldalid']);
        $this->assertSame('203.0.113.5', $rows[0]['ip']);
        $this->assertSame(Eppnaplo::EREDMENYHIBAS, $rows[0]['eredmeny']);
        $this->assertStringNotContainsString('secret-attempt-42', json_encode($rows));
    }

    public function testSuccessIsNotLogged(): void
    {
        [$eppjelszo, $jelszo] = $this->createPassword(self::PAGE);

        $this->requestValidate(self::PAGE, $jelszo);
        $this->requestStatus(self::PAGE, $eppjelszo->getAzonosito());

        $this->assertSame([], $this->getLogRows());
    }

    public function testPurgeLogDeletesOnlyOldRows(): void
    {
        $this->requestValidate(self::PAGE, 'wrong');
        $this->requestValidate(self::PAGE, 'wrong');
        $this->em->getConnection()->executeStatement(
            'UPDATE eppnaplo SET created = ? WHERE id = 1',
            [(new \DateTime('-91 days'))->format('Y-m-d H:i:s')]
        );

        $this->assertSame(1, $this->createService()->purgeLog());
        $this->assertCount(1, $this->getLogRows());
    }

    public function testCorrectPasswordIsFoundAmongManyOnThePage(): void
    {
        [$eppjelszo, $jelszo] = $this->createPassword(self::PAGE);
        $conn = $this->em->getConnection();
        for ($i = 0; $i < 300; $i++) {
            $conn->insert('eppjelszo', [
                'azonosito' => bin2hex(random_bytes(16)),
                'oldalid' => self::PAGE,
                'jelszohash' => Eppjelszo::hashJelszo('other' . $i),
                'lejarat' => (new \DateTime('+1 day'))->format('Y-m-d H:i:s'),
            ]);
        }

        $this->assertSame($eppjelszo->getAzonosito(), $this->requestValidate(self::PAGE, $jelszo)[1]['password_id']);
        $this->assertSame(['valid' => false], $this->requestValidate(self::PAGE, 'wrong-password')[1]);
    }

    public function testGeneratedPasswordHasNoAmbiguousCharacters(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $jelszo = (new Eppjelszo())->generateJelszo();
            $this->assertMatchesRegularExpression('/^[A-HJ-NP-Za-hjkmnp-z2-9]{12}$/', $jelszo);
        }
    }

    public function testPasswordIsStoredOnlyAsHash(): void
    {
        [$eppjelszo, $jelszo] = $this->createPassword(self::PAGE);

        $row = $this->em->getConnection()->fetchAssociative('SELECT * FROM eppjelszo WHERE id = ?', [$eppjelszo->getId()]);

        $this->assertStringNotContainsString($jelszo, json_encode($row));
        $this->assertSame(Eppjelszo::hashJelszo($jelszo), $row['jelszohash']);
    }

    /**
     * @return array{0: Eppjelszo, 1: string} az entitás és a nyers jelszó
     */
    private function createPassword(int $oldalid, string $lejarat = '+1 day', bool $revoked = false): array
    {
        $eppjelszo = new Eppjelszo();
        $eppjelszo->setOldalid($oldalid);
        $eppjelszo->setLejarat(new \DateTime($lejarat));
        $jelszo = $eppjelszo->generateJelszo();
        if ($revoked) {
            $eppjelszo->revoke();
        }
        $this->em->persist($eppjelszo);
        $this->em->flush();
        return [$eppjelszo, $jelszo];
    }

    private function createService(): EppService
    {
        return new EppService($this->em, self::APIKEY);
    }

    private function request(string $vegpont, array $body, array $server): array
    {
        return $this->createService()->handle($vegpont, $server, json_encode($body));
    }

    private function requestValidate(int $oldalid, string $jelszo, array $server = []): array
    {
        return $this->request(
            Eppnaplo::VEGPONTVALIDATE,
            ['page_id' => $oldalid, 'password' => $jelszo],
            $server + ['HTTP_AUTHORIZATION' => 'Bearer ' . self::APIKEY, 'REMOTE_ADDR' => '192.0.2.10']
        );
    }

    private function requestStatus(int $oldalid, string $azonosito): array
    {
        return $this->request(
            Eppnaplo::VEGPONTSTATUS,
            ['page_id' => $oldalid, 'password_id' => $azonosito],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . self::APIKEY, 'REMOTE_ADDR' => '192.0.2.10']
        );
    }

    private function getLogRows(): array
    {
        return $this->em->getConnection()->fetchAllAssociative('SELECT * FROM eppnaplo ORDER BY id');
    }

}
