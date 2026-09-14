<?php

namespace Controllers;

use Entities\Emailtemplate;
use Entities\Partner;
use Services\PartnerResolveService;

/**
 * A partner a saját adatait egy emailben kapott, aláírt linkkel látja és javítja. Az aláírás állapotmentes (HMAC a
 * config.ini `so` kulcsával), ezért nincs hozzá tokenoszlop, és még ismeretlen emailcímre is működik.
 */
class adategyeztetoController extends \mkwhelpers\Controller
{
    private const LINK_ERVENYESSEG = 86400;

    public function view()
    {
        $view = $this->getTemplateFactory()->createMainView('adategyezteto.tpl');
        $link = $this->getLinkParams();
        if ($link['e'] !== '' || $link['h'] !== '') {
            if ($this->isValidLink($link)) {
                $partner = (new PartnerResolveService())->findByEmail($link['e']);
                $view->setVar('link', $link);
                $view->setVar('adat', [
                    'vezeteknev' => $partner ? (string)$partner->getVezeteknev() : '',
                    'keresztnev' => $partner ? (string)$partner->getKeresztnev() : '',
                    'irszam' => $partner ? (string)$partner->getIrszam() : '',
                    'varos' => $partner ? (string)$partner->getVaros() : '',
                    'utca' => $partner ? (string)$partner->getUtca() : '',
                    'hazszam' => $partner ? (string)$partner->getHazszam() : '',
                    'hirlevelkell' => $partner && $partner->getUjdonsaghirlevelkell(),
                ]);
                $view->setVar('msg', $partner
                    ? t('Kérjük, ellenőrizd az adataidat, és nyomd meg a Mentés gombot!')
                    : t('Még nincs adatlapunk ezzel az emailcímmel. Add meg az adataidat, és nyomd meg a Mentés gombot!'));
            } else {
                $view->setVar('hiba', t('A link lejárt vagy hibás. Kérj újat az emailcímed megadásával.'));
            }
        }
        $view->printTemplateResult();
    }

    /** Ismert és ismeretlen emailre ugyanazt válaszolja, és adatot soha nem ad vissza: a linket emailben küldi. */
    public function check()
    {
        header('Content-Type: application/json; charset=utf-8');
        $email = trim($this->params->getStringRequestParam('email'));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['ok' => false, 'msg' => t('Kérjük, ellenőrizd az emailcímed.')]);
            return;
        }
        if (!$this->sendLinkEmail($email)) {
            echo json_encode(['ok' => false, 'msg' => t('Az adategyeztetés most nem érhető el. Kérjük, próbáld később.')]);
            return;
        }
        echo json_encode([
            'ok' => true,
            'msg' => sprintf(
                t('Küldtünk egy levelet a(z) %s címre. A benne lévő linkre kattintva látod és javíthatod az adataidat. A link 24 óráig érvényes.'),
                $email
            ),
        ]);
    }

    /** Csak érvényes linkkel ír, és az emailcím is a linkből jön: az email tulajdonosa igazolta magát, ezért felülírhat. */
    public function save()
    {
        header('Content-Type: application/json; charset=utf-8');
        $link = $this->getLinkParams();
        if (!$this->isValidLink($link)) {
            echo json_encode(['ok' => false, 'msg' => t('A link lejárt vagy hibás. Kérj újat az emailcímed megadásával.')]);
            return;
        }
        $vezeteknev = trim($this->params->getStringRequestParam('vezeteknev'));
        $keresztnev = trim($this->params->getStringRequestParam('keresztnev'));
        if ($vezeteknev === '' || $keresztnev === '') {
            echo json_encode(['ok' => false, 'msg' => t('Kérjük, add meg a vezeték- és a keresztneved.')]);
            return;
        }
        $partner = (new PartnerResolveService())->findByEmail($link['e']);
        if (!$partner) {
            $partner = new Partner();
            $partner->setEmail($link['e']);
            $partner->setSzamlatipus(0);
            $partner->setVatstatus(2);
        }
        $partner->setVezeteknev($vezeteknev);
        $partner->setKeresztnev($keresztnev);
        $partner->setNev($vezeteknev . ' ' . $keresztnev);
        $partner->setIrszam(substr(trim($this->params->getStringRequestParam('irszam')), 0, 10));
        $partner->setVaros(trim($this->params->getStringRequestParam('varos')));
        $partner->setUtca(trim($this->params->getStringRequestParam('utca')));
        $partner->setHazszam(trim($this->params->getStringRequestParam('hazszam')));
        // leiratkozni a hírlevél linkjével lehet, itt csak feliratkozni – az űrlap is ezt írja
        if ($this->params->getBoolRequestParam('hirlevelkell')) {
            $partner->setUjdonsaghirlevelkell(true);
        }
        $this->getEm()->persist($partner);
        $this->getEm()->flush();
        echo json_encode(['ok' => true, 'msg' => t('Köszönjük, az adataidat elmentettük.')]);
    }

    /** @return array{e: string, l: int, h: string} */
    private function getLinkParams(): array
    {
        return [
            'e' => trim($this->params->getStringRequestParam('e')),
            'l' => $this->params->getIntRequestParam('l'),
            'h' => trim($this->params->getStringRequestParam('h')),
        ];
    }

    private function sign(string $email, int $lejarat): string
    {
        // előtaggal, hogy más célú aláírással ne legyen összecserélhető
        return hash_hmac('sha256', 'adategyezteto|' . $email . '|' . $lejarat, (string)\mkw\store::getSalt());
    }

    private function isValidLink(array $link): bool
    {
        // üres kulccsal az aláírást bárki elő tudná állítani
        if ((string)\mkw\store::getSalt() === '' || $link['e'] === '' || $link['h'] === '' || $link['l'] < time()) {
            return false;
        }
        return hash_equals($this->sign($link['e'], $link['l']), $link['h']);
    }

    private function sendLinkEmail(string $email): bool
    {
        if ((string)\mkw\store::getSalt() === '') {
            \mkw\store::writelog('Az adategyeztető linkje nem készült el: üres a config.ini so kulcsa.', 'adategyezteto.log');
            return false;
        }
        $sablonid = \mkw\store::getParameter(\mkw\consts::AdategyeztetoSablon);
        /** @var Emailtemplate|null $emailtpl */
        $emailtpl = $sablonid ? $this->getRepo(Emailtemplate::class)->find($sablonid) : null;
        if (!$emailtpl) {
            \mkw\store::writelog('Az adategyeztető linkje nem ment ki: nincs beállítva a levélsablonja.', 'adategyezteto.log');
            return false;
        }
        $lejarat = time() + self::LINK_ERVENYESSEG;
        // nem a router query paraméterével: az nem kódol, és a „nev+x@…” cím pluszjele szóközzé válna
        $url = \mkw\store::getRouter()->generate('adategyeztetoview', true) . '?' . http_build_query(
            ['e' => $email, 'l' => $lejarat, 'h' => $this->sign($email, $lejarat)],
            '',
            '&',
            PHP_QUERY_RFC3986
        );
        $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
        $body = \mkw\store::getTemplateFactory()->createMainView(
            'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
        );
        foreach ([$subject, $body] as $v) {
            $v->setVar('url', $url);
            $v->setVar('lejarat', date(\mkw\store::$DateTimeFormat, $lejarat));
        }
        if (\mkw\store::getConfigValue('developer')) {
            \mkw\store::writelog($subject->getTemplateResult(), 'adategyeztetoemail.html');
            \mkw\store::writelog($body->getTemplateResult(), 'adategyeztetoemail.html');
        } else {
            $mailer = \mkw\store::getMailer();
            $mailer->addTo($email);
            $mailer->setSubject($subject->getTemplateResult());
            $mailer->setMessage($body->getTemplateResult());
            $mailer->send();
        }
        return true;
    }

}
