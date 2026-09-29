<?php

namespace Services\Cron;

use Services\GLSService;

/**
 * GLS csomagpont lista letöltése – ugyanaz, amit az admin „GLS csomagpont letöltés" képernyője
 * csinál (\Services\GLSService), csak ütemezve. Sikertelen vagy üres letöltésnél a meglévő
 * csomagpontok változatlanok maradnak.
 */
class GLSCsomagpontTask implements CronTask
{

    public function getDescription(): string
    {
        return 'GLS csomagpont lista letöltése a csomagpont törzsbe';
    }

    public function isEnabled(): bool
    {
        return trim((string)\mkw\store::getParameter(\mkw\consts::GLSTerminalURL)) !== '';
    }

    public function run(array $options = []): string
    {
        $service = new GLSService();
        $eredmeny = $service->downloadGLSTerminalList();
        $stat = $service->getTerminalStat();
        return sprintf(
            '%d csomagpont a listában, %d inaktiválva; a törzsben %d aktív, %d inaktív',
            $eredmeny['letoltve'],
            $eredmeny['inaktivalt'],
            $stat['aktiv'],
            $stat['inaktiv']
        );
    }
}
