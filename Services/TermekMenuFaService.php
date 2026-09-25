<?php

namespace Services;

use Entities\TermekMenu;
use Entities\TermekMenuFa;
use mkwhelpers\Exceptions\UserMessageException;

/** Creating, copying and deleting a product menu (TermekMenuFa) with its nodes. */
class TermekMenuFaService
{

    /** node columns a copy takes over as they are */
    private const SKIP_COLUMNS = ['id', 'parent_id', 'termekmenufa_id', 'termekmenu2id', 'karkod', 'created', 'lastmod'];

    public function create(string $nev): TermekMenuFa
    {
        $nev = $this->checkNev($nev);
        $em = \mkw\store::getEm();
        $fa = new TermekMenuFa();
        $fa->setNev($nev);
        $fa->setSorrend((int)$em->getConnection()->fetchOne('SELECT COALESCE(MAX(sorrend), 0) + 1 FROM termekmenufa'));
        $root = new TermekMenu();
        $root->setNev($nev);
        $root->setTermekmenufa($fa);
        $em->persist($fa);
        $em->persist($root);
        $em->flush();
        return $fa;
    }

    public function rename(TermekMenuFa $fa, string $nev): void
    {
        $fa->setNev($this->checkNev($nev));
        \mkw\store::getEm()->persist($fa);
        \mkw\store::getEm()->flush();
    }

    /**
     * A new menu with the same nodes (slugs kept: they are unique per menu) and, with $termekekkel, the same product
     * placements.
     */
    public function copy(TermekMenuFa $forras, string $nev, bool $termekekkel): TermekMenuFa
    {
        $nev = $this->checkNev($nev);
        $conn = \mkw\store::getEm()->getConnection();
        $oszlopok = array_values(array_diff(
            array_map(fn($c) => $c->getName(), $conn->createSchemaManager()->listTableColumns('termekmenu')),
            self::SKIP_COLUMNS
        ));
        $mezok = implode(', ', $oszlopok);
        $ujid = $conn->transactional(function ($conn) use ($forras, $nev, $termekekkel, $mezok) {
            $conn->executeStatement(
                'INSERT INTO termekmenufa (nev, sorrend, created, lastmod) SELECT ?, COALESCE(MAX(sorrend), 0) + 1, NOW(), NOW() FROM termekmenufa',
                [$nev]
            );
            $faid = (int)$conn->lastInsertId();
            $sorok = $conn->fetchAllAssociative(
                'SELECT id, parent_id FROM termekmenu WHERE termekmenufa_id = ? ORDER BY id',
                [$forras->getId()]
            );
            $gyerekek = [];
            foreach ($sorok as $sor) {
                $gyerekek[(int)$sor['parent_id']][] = (int)$sor['id'];
            }
            // parents before their children, so the new parent id is known
            $uj = [];
            $sor = $gyerekek[0] ?? [];
            while ($sor) {
                $id = array_shift($sor);
                $parent = $conn->fetchOne('SELECT parent_id FROM termekmenu WHERE id = ?', [$id]);
                $conn->executeStatement(
                    'INSERT INTO termekmenu (' . $mezok . ', termekmenufa_id, parent_id, created, lastmod)'
                    . ' SELECT ' . $mezok . ', ?, ?, NOW(), NOW() FROM termekmenu WHERE id = ?',
                    [$faid, $parent ? $uj[(int)$parent] : null, $id]
                );
                $uj[$id] = (int)$conn->lastInsertId();
                array_push($sor, ...($gyerekek[$id] ?? []));
            }
            if ($termekekkel) {
                foreach ($conn->fetchAllAssociative(
                    'SELECT termek_id, termekmenu_id FROM termekmenutermek WHERE termekmenufa_id = ?',
                    [$forras->getId()]
                ) as $elhelyezes) {
                    $conn->executeStatement(
                        'INSERT INTO termekmenutermek (termek_id, termekmenu_id, termekmenufa_id, created, lastmod) VALUES (?, ?, ?, NOW(), NOW())',
                        [$elhelyezes['termek_id'], $uj[(int)$elhelyezes['termekmenu_id']], $faid]
                    );
                }
            }
            return $faid;
        });
        return \mkw\store::getEm()->getRepository(TermekMenuFa::class)->find($ujid);
    }

    /** The menu with its nodes and placements; refused while a webshop shows it. */
    public function delete(TermekMenuFa $fa): void
    {
        $webshopok = $this->getWebshopsUsing($fa);
        if ($webshopok) {
            throw new UserMessageException(sprintf(
                t('A menüt használja: %s. Előbb a Beállításokban válasszon nekik másik menüt.'),
                implode(', ', array_map(fn($n) => (string)\mkw\store::getParameter('webshop' . $n . 'name', '') ?: 'Webshop ' . $n, $webshopok))
            ));
        }
        $conn = \mkw\store::getEm()->getConnection();
        $conn->transactional(function ($conn) use ($fa) {
            $conn->executeStatement('DELETE FROM termekmenutermek WHERE termekmenufa_id = ?', [$fa->getId()]);
            // the old per-product column still references menu 1 nodes until the next release drops it
            $conn->executeStatement(
                'UPDATE termek SET termekmenu1_id = NULL WHERE termekmenu1_id IN (SELECT id FROM termekmenu WHERE termekmenufa_id = ?)',
                [$fa->getId()]
            );
            $conn->executeStatement('UPDATE termekmenu SET parent_id = NULL WHERE termekmenufa_id = ?', [$fa->getId()]);
            $conn->executeStatement('DELETE FROM termekmenu WHERE termekmenufa_id = ?', [$fa->getId()]);
            $conn->executeStatement('DELETE FROM termekmenufa WHERE id = ?', [$fa->getId()]);
        });
        \mkw\store::getEm()->clear();
    }

    /**
     * @return int[] the webshop numbers whose setup shows the menu - read from the parameters, not the webshop list of
     * this deployment: another deployment on the same database may show it
     */
    public function getWebshopsUsing(TermekMenuFa $fa): array
    {
        $ret = [];
        $sorok = \mkw\store::getEm()->getConnection()->fetchFirstColumn(
            'SELECT id FROM parameterek WHERE id REGEXP ? AND ertek = ?',
            ['^' . \mkw\consts::TermekMenuFa . '[0-9]*$', (string)$fa->getId()]
        );
        foreach ($sorok as $id) {
            $ret[] = (int)substr($id, strlen(\mkw\consts::TermekMenuFa)) ?: 1;
        }
        sort($ret);
        return $ret;
    }

    private function checkNev(string $nev): string
    {
        $nev = trim($nev);
        if ($nev === '' || mb_strlen($nev) > 255) {
            throw new UserMessageException(t('A menü neve 1–255 karakter lehet.'));
        }
        return $nev;
    }
}
