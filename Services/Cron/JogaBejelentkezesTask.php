<?php

namespace Services\Cron;

use Carbon\Carbon;
use Entities\Emailtemplate;
use Entities\JogaBejelentkezes;
use Entities\Orarend;
use mkwhelpers\FilterDescriptor;

/**
 * Darshan: a mai órák előtt ~2 órával értesítő a tanárnak (és ha kevesen vannak, a
 * jelentkezőknek is). A `cronController`-ből átemelve, a logikája változatlan.
 *
 * A ±5 perces ablak miatt óránként kell futnia; ritkábban futtatva órák maradnak ki.
 */
class JogaBejelentkezesTask implements CronTask
{

    public function getDescription(): string
    {
        return 'Jóga: értesítő a mai órák bejelentkezéseiről (óránként futtatandó)';
    }

    public function isEnabled(): bool
    {
        return (bool)\mkw\store::isDarshan();
    }

    public function run(array $options = []): string
    {
        $ma = Carbon::now();
        $filter = new FilterDescriptor();
        $filter->addFilter('nap', '=', $ma->format('N'));
        $filter->addFilter('inaktiv', '=', false);
        $filter->addFilter('bejelentkezeskell', '=', true);

        $maiorak = $this->getRepo(Orarend::class)->getAll($filter);
        $erintett = 0;
        $level = 0;

        /** @var Orarend $ora */
        foreach ($maiorak as $ora) {
            $oradatetime = Carbon::createFromTimeString($ora->getKezdetStr());
            $kul = $ma->floatDiffInMinutes($oradatetime, false);
            if ($kul <= 115 || $kul >= 125) {
                continue;
            }
            $erintett++;

            $bejfilter = new FilterDescriptor();
            $bejfilter->addFilter('orarend', '=', $ora);
            $bejfilter->addFilter('datum', '=', $ma);
            $bejcnt = $this->getRepo(JogaBejelentkezes::class)->getCount($bejfilter);

            $vars = [
                'megszolitas' => $ora->getDolgozoNev(),
                'oranev' => $ora->getNev(),
                'orakezdet' => $ora->getKezdetStr(),
                'oradatum' => $ma->format(\mkw\store::$DateFormat)
            ];

            if ($bejcnt == 0) {
                $level += (int)$this->sendMail(\mkw\consts::JogaNemjonsenkiSablon, $ora->getDolgozoEmail(), $vars);
                continue;
            }

            $jelentkezesek = $this->getRepo(JogaBejelentkezes::class)->getAll($bejfilter);
            $vars['jelentkezesek'] = [];
            /** @var JogaBejelentkezes $jelentkezes */
            foreach ($jelentkezesek as $jelentkezes) {
                $vars['jelentkezesek'][] = ['nev' => $jelentkezes->getPartnernev()];
            }

            if ($ora->getMinbejelentkezes() > 0 && $bejcnt < $ora->getMinbejelentkezes()) {
                $level += (int)$this->sendMail(
                    \mkw\consts::JogaNemjelenteztekelegenTanarnakSablon,
                    $ora->getDolgozoEmail(),
                    $vars
                );
                foreach ($jelentkezesek as $jelentkezes) {
                    $level += (int)$this->sendMail(
                        \mkw\consts::JogaNemjelentkeztekelegenGyakorlonakSablon,
                        $jelentkezes->getPartneremail(),
                        array_merge($vars, ['megszolitas' => $jelentkezes->getPartnernev()])
                    );
                }
            } else {
                $level += (int)$this->sendMail(
                    \mkw\consts::JogaElegenjelentkeztekTanarnakSablon,
                    $ora->getDolgozoEmail(),
                    $vars
                );
            }
        }

        return sprintf(
            'mai óra: %d, ebből most esedékes: %d, kiküldött levél: %d',
            count($maiorak),
            $erintett,
            $level
        );
    }

    /**
     * @return bool ment-e el levél
     */
    private function sendMail($sablonParameter, $email, array $vars)
    {
        if (!$email) {
            return false;
        }
        /** @var Emailtemplate $sablon */
        $sablon = $this->getRepo(Emailtemplate::class)->find(\mkw\store::getParameter($sablonParameter));
        if (!$sablon) {
            return false;
        }
        $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $sablon->getTargy());
        $body = \mkw\store::getTemplateFactory()->createMainView(
            'string:' . str_replace('&#39;', '\'', html_entity_decode($sablon->getHTMLSzoveg()))
        );
        foreach ($vars as $nev => $ertek) {
            $body->setVar($nev, $ertek);
        }
        $mailer = \mkw\store::getMailer();
        $mailer->addTo($email);
        $mailer->setSubject($subject->getTemplateResult());
        $mailer->setMessage($body->getTemplateResult());
        $mailer->send();
        return true;
    }

    private function getRepo($entityName)
    {
        return \mkw\store::getEm()->getRepository($entityName);
    }
}
