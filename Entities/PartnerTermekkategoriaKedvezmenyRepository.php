<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class PartnerTermekkategoriaKedvezmenyRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(PartnerTermekkategoriaKedvezmeny::class);
    }

    /**
     * A termék kategóriáinak karkod-jaira (termekfa1-3) illeszkedő kedvezmény. Ha több ág is kap kedvezményt, a
     * legszűkebb, vagyis a leghosszabb karkod-ú számít.
     *
     * @param Partner|null $partner
     * @param string[] $karkodok
     */
    public function getKedvezmenyForKarkodok($partner, array $karkodok)
    {
        $karkodok = array_values(array_filter($karkodok));
        if (!$partner || !$partner->getId() || !$karkodok) {
            return 0;
        }
        $kedvezmeny = $this->_em->getConnection()->fetchOne(
            'SELECT kdv.kedvezmeny FROM partnertermekkategoriakedvezmeny kdv'
            . ' INNER JOIN termekfa fa ON fa.id = kdv.termekfa_id'
            . ' WHERE kdv.partner_id = ? AND (' . implode(' OR ', array_fill(0, count($karkodok), '? LIKE CONCAT(fa.karkod, "%")')) . ')'
            . ' ORDER BY LENGTH(fa.karkod) DESC, kdv.id DESC LIMIT 1',
            array_merge([$partner->getId()], $karkodok)
        );
        return $kedvezmeny === false ? 0 : $kedvezmeny * 1;
    }

    /**
     * A b2b fiók kedvezménylistája: a b2b menü ágai, a partner meglévő kedvezményével.
     *
     * @param Partner|null $partner
     */
    public function getForFiok($partner = null)
    {
        $rsm = new ResultSetMapping();
        foreach (['id', 'nev', 'kedvezmeny', 'termekfaid'] as $field) {
            $rsm->addScalarResult($field, $field);
        }
        $q = $this->_em->createNativeQuery(
            'SELECT kdv.id, ' . \mkw\store::getLocalizedFieldName('fa.nev') . ' AS nev, kdv.kedvezmeny, fa.id AS termekfaid'
            . ' FROM termekfa fa'
            . ' LEFT JOIN partnertermekkategoriakedvezmeny kdv ON (kdv.termekfa_id = fa.id) AND (kdv.partner_id = :p)'
            . ' WHERE fa.menu1lathato = 1 AND fa.lathato = 1'
            . ' ORDER BY fa.sorrend, nev',
            $rsm
        );
        $q->setParameter('p', $partner ? $partner->getId() : 0);
        return $q->getScalarResult();
    }

}
