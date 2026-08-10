<?php

namespace Services\Cron;

/**
 * „Lefutott, de nézz rá.” A feladat elvégezte a dolgát, csak nem hibátlanul – tipikusan
 * néhány API hívás elszállt, vagy a rate limiter megfékezte. A napló `figyelem` állapotot
 * kap, a kilépési kód viszont 0 marad: egy tíz percenként futó feladat nem küldhet levelet
 * minden körben azért, mert egyetlen rendelés hibás.
 */
class CronWarning extends \RuntimeException
{
}
