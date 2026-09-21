<?php

namespace Tests;

use Entities\Eppjelszo;
use Entities\Eppnaplo;
use Services\EppjelszoService;

class EppjelszoServiceTest extends DatabaseTestCase
{

    private const PAGE = 123;

    protected function getEntityClasses(): array
    {
        return [Eppjelszo::class, Eppnaplo::class];
    }

    /**
     * @dataProvider lejaratProvider
     */
    public function testCalcLejarat(string $most, int $honap, string $expected): void
    {
        $this->assertSame($expected, EppjelszoService::calcLejarat($honap, new \DateTime($most))->format('Y-m-d H:i:s'));
    }

    public static function lejaratProvider(): array
    {
        return [
            'plain' => ['2026-09-22 10:15', 3, '2026-12-22 23:59:59'],
            'year boundary' => ['2026-11-05 08:00', 2, '2027-01-05 23:59:59'],
            'month end clamps' => ['2026-01-31 12:00', 1, '2026-02-28 23:59:59'],
            'leap year' => ['2028-01-31 12:00', 1, '2028-02-29 23:59:59'],
            'long' => ['2026-09-22 10:15', 24, '2028-09-22 23:59:59'],
        ];
    }

    public function testIssueStoresRecipientAndMonths(): void
    {
        [$eppjelszo, $jelszo] = $this->createService()->issue(self::PAGE, 'Kovács Anna', ' Anna@Example.COM ', 6);

        $this->em->clear();
        $saved = $this->em->getRepository(Eppjelszo::class)->find($eppjelszo->getId());
        $this->assertSame(self::PAGE, $saved->getOldalid());
        $this->assertSame('Kovács Anna', $saved->getNev());
        $this->assertSame('anna@example.com', $saved->getEmail());
        $this->assertSame(6, $saved->getHonap());
        $this->assertEquals(EppjelszoService::calcLejarat(6), $saved->getLejarat());
        $this->assertTrue($saved->checkJelszo($jelszo));
        $this->assertTrue($saved->isAktiv());
    }

    public function testIssueRevokesThePreviousPasswordOfTheSameRecipientAndPage(): void
    {
        $service = $this->createService();
        [$regi] = $service->issue(self::PAGE, 'Anna', 'anna@example.com', 3);
        [$masikOldal] = $service->issue(456, 'Anna', 'anna@example.com', 3);
        [$masikEmber] = $service->issue(self::PAGE, 'Béla', 'bela@example.com', 3);

        [$uj] = $service->issue(self::PAGE, 'Anna', 'ANNA@example.com', 12);

        $this->assertTrue($regi->isVisszavonva());
        $this->assertFalse($masikOldal->isVisszavonva());
        $this->assertFalse($masikEmber->isVisszavonva());
        $this->assertSame([$uj->getId()], array_map(fn($j) => $j->getId(), $service->findAktiv(self::PAGE, 'anna@example.com')));
    }

    public function testRevokedOldPasswordNoLongerValidates(): void
    {
        $service = $this->createService();
        [, $regiJelszo] = $service->issue(self::PAGE, 'Anna', 'anna@example.com', 3);
        [, $ujJelszo] = $service->issue(self::PAGE, 'Anna', 'anna@example.com', 3);

        $api = new \Services\EppService($this->em, 'key');
        $server = ['HTTP_AUTHORIZATION' => 'Bearer key', 'REMOTE_ADDR' => '192.0.2.1'];
        $this->assertSame(['valid' => false], $api->handle('validate', $server, json_encode(['page_id' => self::PAGE, 'password' => $regiJelszo]))[1]);
        $this->assertTrue($api->handle('validate', $server, json_encode(['page_id' => self::PAGE, 'password' => $ujJelszo]))[1]['valid']);
    }

    public function testFindAktivIgnoresExpiredPasswords(): void
    {
        [$eppjelszo] = $this->createService()->issue(self::PAGE, 'Anna', 'anna@example.com', 3);
        $eppjelszo->setLejarat(new \DateTime('-1 minute'));
        $this->em->flush();

        $this->assertSame([], $this->createService()->findAktiv(self::PAGE, 'anna@example.com'));
    }

    /**
     * @dataProvider invalidIssueProvider
     */
    public function testIssueRejectsInvalidInput(int $oldalid, string $email, int $honap): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->createService()->issue($oldalid, 'Anna', $email, $honap);
    }

    public static function invalidIssueProvider(): array
    {
        return [
            'no page' => [0, 'anna@example.com', 3],
            'no email' => [self::PAGE, ' ', 3],
            'zero months' => [self::PAGE, 'anna@example.com', 0],
            'too many months' => [self::PAGE, 'anna@example.com', EppjelszoService::MAXHONAP + 1],
        ];
    }

    private function createService(): EppjelszoService
    {
        return new EppjelszoService($this->em);
    }

}
