<?php

namespace Services\Cron;

use Services\UnasGetOrderService;
use Services\UnasService;
use Services\UnasSetOrderService;

/**
 * A visszaírás. Webhook nincs (VIP-es funkció), tehát ez a rendelések
 * egyetlen útja – lásd `docs/unas-megrendeles-integracio.md`.
 */
class UnasSetOrderTask implements CronTask
{

    /** egy menetben ennyi kimenő sort próbálunk elküldeni */
    private const OUTBOXLIMIT = 50;

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
        $limit = isset($options['limit']) ? (int)$options['limit'] : self::OUTBOXLIMIT;

        $drain = (new UnasSetOrderService())->drainOutbox($limit);

        // az UNAS állapotlap ezt mutatja „utolsó cron” néven
        \mkw\store::setParameter(\mkw\consts::UnasUtolsoCron, time());

        $uzenet = sprintf(
            ' visszaírás: %d sor (%d kész, %d hiba, %d kihagyva)%s',
            $drain['osszes'],
            $drain['kesz'],
            $drain['hiba'],
            $drain['kihagyva'],
            $drain['fek'] ? ' [fék: a kimenő sor nem ürült ki]' : ''
        );

        if ($drain['hiba'] || $drain['fek']) {
            throw new CronWarning($uzenet);
        }
        return $uzenet;
    }
}
