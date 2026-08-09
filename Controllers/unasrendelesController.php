<?php

namespace Controllers;

use Entities\Szallitasimod;
use Entities\Unasoutbox;
use Services\UnasGetOrderService;
use Services\UnasService;
use Services\UnasTorzsadatService;
use Services\UnasSetOrderService;

/**
 * UNAS megrendelés-import admin képernyő: állapotlap, leképezés-szerkesztő, kézi import,
 * kimenő sor. A leképezés-szerkesztő az UNAS `getOrderStatus` / `getMethod` válaszából
 * generálódik, mert a bolt státuszai és módjai szabadon konfigurálhatók.
 *
 * Lásd docs/unas-megrendeles-integracio.md.
 */
class unasrendelesController extends \mkwhelpers\Controller
{

    public function view()
    {
        $view = $this->createView('unasrendeles.tpl');
        $view->setVar('pagetitle', t('UNAS megrendelések'));
        $view->setVar('figyelmeztetes', $this->settingsWarning());
        $view->setVar('kurzor', $this->cursorStr());
        $view->setVar('kurzorinput', $this->cursorInput());
        $view->setVar('utolsocron', $this->parameterStr(\mkw\consts::UnasUtolsoCron));
        $view->setVar('ratelimit', $this->rateStatus());
        $view->setVar('visszairas', [
            'statusz' => UnasSetOrderService::isEnabled(Unasoutbox::TIPUSSTATUSZ),
            'szamla' => UnasSetOrderService::isEnabled(Unasoutbox::TIPUSSZAMLA),
            'csomag' => UnasSetOrderService::isEnabled(Unasoutbox::TIPUSCSOMAG),
        ]);
        $view->setVar('kezelesiktgfigyelmeztetes', $this->kezelesiKtgWarning());
        $view->printTemplateResult();
    }

    /** Az UNAS státuszai és módjai + az MKW oldali választék, egy űrlapba renderelve. */
    public function loadMaps()
    {
        $api = (new UnasService())->getApi();

        $statuszXml = $api->getOrderStatus();
        if (!$statuszXml) {
            $this->json(['ok' => false, 'hiba' => $api->getLasterrorsAsString()]);
            return;
        }
        $methodXml = $api->getMethod();
        if (!$methodXml) {
            $this->json(['ok' => false, 'hiba' => $api->getLasterrorsAsString()]);
            return;
        }

        $statuszOptions = $this->collectOptions($statuszXml);
        $methodOptions = $this->collectOptions($methodXml);

        $view = $this->createView('unasrendeles_lekepezes.tpl');
        $view->setVar(
            'statuszok',
            $this->buildRows(
                $statuszOptions,
                \mkw\consts::UnasStatuszMap,
                (new bizonylatstatuszController())->getSelectList()
            )
        );
        $view->setVar(
            'fizmodok',
            $this->buildRows(
                $this->filterByType($methodOptions, 'payment'),
                \mkw\consts::UnasFizmodMap,
                (new fizmodController())->getSelectList()
            )
        );
        $view->setVar(
            'szallmodok',
            $this->buildRows(
                $this->filterByType($methodOptions, 'shipping'),
                \mkw\consts::UnasSzallmodMap,
                // $mind = true: a nem webes szállítási módra is jöhet UNAS rendelés
                (new szallitasimodController())->getSelectList(null, true)
            )
        );
        $this->json([
            'ok' => true,
            'html' => $view->getTemplateResult(),
            'figyelmeztetes' => $this->unknownTypes($methodOptions, ['payment', 'shipping']),
        ]);
    }

    public function saveMaps()
    {
        UnasTorzsadatService::saveMap(\mkw\consts::UnasStatuszMap, $this->mapFromRequest('statusz'));
        UnasTorzsadatService::saveMap(\mkw\consts::UnasFizmodMap, $this->mapFromRequest('fizmod'));
        UnasTorzsadatService::saveMap(\mkw\consts::UnasSzallmodMap, $this->mapFromRequest('szallmod'));
        $this->json(['ok' => true, 'figyelmeztetes' => $this->kezelesiKtgWarning()]);
    }

    /** Kézi (újra)import azonosító alapján – az egyrendeléses getOrder korlátja bőséges. */
    public function importOrder()
    {
        $result = (new UnasGetOrderService())->importOrder($this->params->getStringRequestParam('unaskey'));
        $this->json(['ok' => $result['statusz'] !== 'hiba'] + $result);
    }

    public function poll()
    {
        try {
            $summary = (new UnasGetOrderService())->pollOrders(
                $this->params->getBoolRequestParam('csakletoltes', false)
            );
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'hiba' => $e->getMessage()]);
            return;
        }
        $view = $this->createView('unasrendeles_eredmeny.tpl');
        $view->setVar('osszesito', $summary);
        $view->setVar('eredmenyek', array_slice($summary['eredmenyek'], 0, 200));
        $this->json([
            'ok' => true,
            'html' => $view->getTemplateResult(),
            'kurzor' => $this->cursorStr(),
        ]);
    }

    /**
     * A kurzor átállítása: elgépelt vagy elszaladt kurzor mellett ezzel lehet visszamenni.
     * Üres dátum = nulláról (az első futás az utolsó napokra néz vissza).
     */
    public function saveCursor()
    {
        $datum = trim($this->params->getStringRequestParam('kurzor'));
        $ts = 0;
        if ($datum !== '') {
            $ts = strtotime(\mkw\store::convDate($datum));
            if (!$ts) {
                $this->json(['ok' => false, 'hiba' => t('Értelmezhetetlen dátum.')]);
                return;
            }
        }
        \mkw\store::setParameter(\mkw\consts::UnasImportCursor, $ts);
        $this->json(['ok' => true, 'kurzor' => $this->cursorStr(), 'kurzorinput' => $this->cursorInput()]);
    }

    public function outbox()
    {
        $view = $this->createView('unasrendeles_outbox.tpl');
        $view->setVar('sorok', $this->outboxRows());
        $view->setVar('osszesito', $this->getRepo(Unasoutbox::class)->getCountByAllapot());
        echo $view->getTemplateResult();
    }

    public function drainOutbox()
    {
        try {
            $summary = (new UnasSetOrderService())->drainOutbox(
                $this->params->getIntRequestParam('limit', 50) ?: 50
            );
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'hiba' => $e->getMessage()]);
            return;
        }
        $this->json(['ok' => true] + $summary);
    }

    public function retryOutbox()
    {
        $ok = (new UnasSetOrderService())->retry($this->params->getIntRequestParam('id', 0));
        $this->json(['ok' => $ok, 'hiba' => $ok ? '' : t('A sor nem található.')]);
    }

    // ------------------------------------------------------------------

    /**
     * A getOrderStatus / getMethod válaszának KÖZVETLEN gyerekei (`OrderStatuses/OrderStatus`,
     * illetve `Methods/Method`). A fát szándékosan NEM járjuk be: a
     * `Method/Limitations/CustomerGroups/CustomerGroup` is `Id` + `Name` pár, és bekerülne a
     * választható elemek közé.
     *
     * @return array<int,array{id:string,name:string,type:string,subtype:string,aktiv:bool,sorrend:int}>
     */
    private function collectOptions(\SimpleXMLElement $xml)
    {
        $result = [];
        foreach ($xml->children() as $child) {
            if (!isset($child->Id) || !isset($child->Name)) {
                continue;
            }
            $result[] = [
                'id' => trim((string)$child->Id),
                'name' => trim((string)$child->Name),
                'type' => isset($child->Type) ? trim((string)$child->Type) : '',
                'subtype' => isset($child->SubType) ? trim((string)$child->SubType) : '',
                // csak a getMethod ad `Active`-ot; ahol nincs, ott aktívnak vesszük
                'aktiv' => !isset($child->Active) || strtolower(trim((string)$child->Active)) !== 'no',
                'sorrend' => isset($child->Order) ? (int)$child->Order : 0,
            ];
        }
        usort($result, static fn($a, $b) => $a['sorrend'] <=> $b['sorrend']);
        return $result;
    }

    /**
     * A getMethod EGYETLEN `Method` listát ad, a fizetési és a szállítási módot a `Type`
     * (`payment` / `shipping`) választja szét – nem a burkolóelem neve.
     *
     * @return array<int,array>
     */
    private function filterByType(array $options, $type)
    {
        return array_values(array_filter($options, static function ($option) use ($type) {
            return $option['type'] === $type;
        }));
    }

    /**
     * Ha a `Type` egyetlen elemnél sem az általunk ismert érték, a két lista üresen maradna, és a
     * kezelő nem tudná, miért. A tényleges típusokat visszaadjuk, hogy látszódjon.
     */
    private function unknownTypes(array $options, array $ismert)
    {
        $tipusok = [];
        foreach ($options as $option) {
            if (!in_array($option['type'], $ismert, true)) {
                $tipusok[$option['type']] = true;
            }
        }
        if (!$tipusok) {
            return null;
        }
        return sprintf(
            t('Az UNAS ismeretlen `Type` értékeket is adott a módokra: %s. Ezek egyik listába sem kerültek be.'),
            implode(', ', array_map(static fn($t) => $t === '' ? '(üres)' : $t, array_keys($tipusok)))
        );
    }

    /**
     * Egy leképezés-blokk sorai: az UNAS elem + a hozzá tartozó MKW választék, a mentett
     * értékkel kiválasztva.
     */
    private function buildRows(array $unasOptions, $par, array $mkwList)
    {
        $map = UnasTorzsadatService::getMap($par);
        $rows = [];
        foreach ($unasOptions as $option) {
            $selected = $map[$option['id']] ?? '';
            $lista = [];
            foreach ($mkwList as $item) {
                $lista[] = [
                    'id' => $item['id'],
                    'caption' => $item['caption'],
                    'selected' => (string)$item['id'] === (string)$selected,
                ];
            }
            $rows[] = [
                'unasid' => $option['id'],
                'unasnev' => $option['name'],
                // a SubType (cash, transfer, …) segít eltalálni a megfelelő MKW fizetési módot
                'unastipus' => trim($option['type'] . ' ' . $option['subtype']),
                'aktiv' => $option['aktiv'],
                'lista' => $lista,
            ];
        }
        return $rows;
    }

    /** @return array<string,string> UNAS azonosító => MKW azonosító */
    private function mapFromRequest($prefix)
    {
        $map = [];
        foreach ($this->params->getArrayRequestParam($prefix . 'unasid') as $index => $unasid) {
            $map[trim((string)$unasid)] = (string)$this->params->getIntRequestParam($prefix . 'mkwid_' . $index, 0);
        }
        return $map;
    }

    private function outboxRows()
    {
        $rows = [];
        $sorok = $this->getRepo(Unasoutbox::class)->getAll([], ['_xx.id' => 'DESC'], 0, 100);
        foreach ($sorok as $sor) {
            $rows[] = $sor->toLista();
        }
        return $rows;
    }

    /**
     * A `BizonylatfejListener::createKezelesiKoltseg()` NINCS a
     * `isKellszallitasikoltsegetszamolni()` mögé zárva: a szállítási módra tett termékből a
     * listener minden mentéskor képez egy kezelési díj tételt, akkor is, ha az UNAS rendelés
     * nem tartalmaz `handel-cost` tételt.
     */
    private function kezelesiKtgWarning()
    {
        $ids = array_values(UnasTorzsadatService::getMap(\mkw\consts::UnasSzallmodMap));
        if (!$ids) {
            return null;
        }
        $nevek = [];
        foreach ($ids as $id) {
            $szallmod = $this->getRepo(Szallitasimod::class)->find($id);
            if ($szallmod && $szallmod->getTermek()) {
                $nevek[] = $szallmod->getNev() . ' (' . $szallmod->getTermek()->getNev() . ')';
            }
        }
        if (!$nevek) {
            return null;
        }
        return sprintf(
            t(
                'Ezekre a leképezett szállítási módokra kezelési költség termék van állítva: %s. '
                . 'Az MKW minden mentéskor felveszi ezt a tételt, az UNAS `handel-cost` összegét pedig a '
                . 'termék árára írja át – ha a kettő nem egyezik, a bizonylat végösszege eltér a rendelésétől.'
            ),
            implode(', ', $nevek)
        );
    }

    private function rateStatus()
    {
        $api = (new UnasService())->getApi();
        $result = [];
        foreach (['getOrder', 'setOrder', 'getOrderStatus', 'getMethod'] as $endpoint) {
            $result[$endpoint] = $api->rateCount($endpoint);
        }
        return $result;
    }

    private function cursorStr()
    {
        $ts = (int)\mkw\store::getParameter(\mkw\consts::UnasImportCursor, 0);
        return $ts ? date(\mkw\store::$DateTimeFormat, $ts) : t('még nem futott');
    }

    private function cursorInput()
    {
        $ts = (int)\mkw\store::getParameter(\mkw\consts::UnasImportCursor, 0);
        return $ts ? date(\mkw\store::$DateFormat, $ts) : '';
    }

    private function parameterStr($par)
    {
        $ts = (int)\mkw\store::getParameter($par, 0);
        return $ts ? date(\mkw\store::$DateTimeFormat, $ts) : '-';
    }

    private function settingsWarning()
    {
        if (!\mkw\store::isUnas()) {
            return t('Az UNAS integráció nincs bekapcsolva (setup.ini: unas = 1).');
        }
        if (!UnasService::isEnabled()) {
            return t('Nincs beállítva az UNAS API kulcs. Beállítások → UNAS fül.');
        }
        if (!\mkw\store::getParameter(\mkw\consts::UnasStatuszMap)) {
            return t('A rendelésstátuszok nincsenek összerendelve. Töltsd be a leképezéseket, és állítsd be őket.');
        }
        return null;
    }

    private function json(array $data)
    {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

}
