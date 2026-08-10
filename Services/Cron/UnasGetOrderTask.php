<?php

namespace Services\Cron;

use Services\UnasGetOrderService;
use Services\UnasService;

/**
 * A rendelés-poller és a visszaírás. Webhook nincs (VIP-es funkció), tehát ez a rendelések
 * egyetlen útja – lásd `docs/unas-megrendeles-integracio.md`.
 */
class UnasGetOrderTask implements CronTask
{

    public function getDescription(): string
    {
        return 'UNAS rendelések lehúzása és a visszaírási sor ürítése';
    }

    public function isEnabled(): bool
    {
        return UnasService::isEnabled();
    }

    public function run(array $options = []): string
    {
        $poll = (new UnasGetOrderService())->pollOrders();

        // az UNAS állapotlap ezt mutatja „utolsó cron” néven
        \mkw\store::setParameter(\mkw\consts::UnasUtolsoCron, time());

        $uzenet = sprintf(
            'rendelés: %d feldolgozva (%d új, %d meglévő, %d hiba, %d lap), kurzor: %s',
            $poll['feldolgozva'],
            $poll['uj'],
            $poll['letezo'],
            $poll['hiba'],
            $poll['lapok'],
            $poll['kurzor'] ? date(\mkw\store::$DateTimeFormat, $poll['kurzor']) : '-',
        );

        if ($poll['hiba']) {
            throw new CronWarning($uzenet);
        }
        return $uzenet;
    }
}
