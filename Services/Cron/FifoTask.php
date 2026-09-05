<?php

namespace Services\Cron;

use Services\FifoService;

/**
 * FIFO készletértékelés újraszámolása. Alapból csak azokat a készletcsoportokat, amiket a
 * bizonylatmentés megjelölt (`fifovaltozas` tábla) – ez néhány sor, ezredmásodperces munka.
 *
 * A `--teljes` az egész készlettörténetet újraszámolja. Ez a hálónk arra, amit a jelölés
 * nem foghat meg (kézi SQL, elmaradt jelölés); heti egy menet elég belőle. Nagy telepítésen
 * is másodperces nagyságrend.
 *
 * Ha éppen fut egy másik menet (kézi indítás vagy másik cron), a feladat figyelmeztetéssel
 * kilép – a jelölések megmaradnak, a következő menet elviszi őket.
 */
class FifoTask implements CronTask
{

    public function getDescription(): string
    {
        return 'FIFO készletérték újraszámolása (--teljes = a teljes készlettörténet)';
    }

    public function isEnabled(): bool
    {
        return (bool)\mkw\store::isFifo();
    }

    public function run(array $options = []): string
    {
        $service = new FifoService();
        $teljes = !empty($options['teljes']);

        try {
            $r = $teljes ? $service->recalculateAll() : $service->recalculateDirty();
        } catch (\Exception $e) {
            throw new CronWarning($e->getMessage());
        }

        if (!$teljes && !$r['csoport']) {
            return 'nem változott készletcsoport';
        }

        $uzenet = sprintf(
            '%s: %d csoport, %d nyitott réteg, %s Ft',
            $teljes ? 'teljes újraszámolás' : 'változott csoportok',
            $r['szamolt'],
            $r['reteg'],
            number_format($r['ertek'], 0, ',', ' ')
        );
        if ($r['fedezetlen'] || $r['becsult']) {
            // nem hiba, de a könyvelőnek dolga van vele
            throw new CronWarning($uzenet . sprintf(' – %d fedezetlen, %d becsült árat tartalmazó csoport', $r['fedezetlen'], $r['becsult']));
        }
        return $uzenet;
    }

}
