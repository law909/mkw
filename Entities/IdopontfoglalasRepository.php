<?php

namespace Entities;

class IdopontfoglalasRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Idopontfoglalas::class);
        $this->setOrders([
            '1' => ['caption' => 'foglalás ideje szerint csökkenő', 'order' => ['_xx.foglalasido' => 'DESC']],
            '2' => ['caption' => 'foglalás ideje szerint növekvő', 'order' => ['_xx.foglalasido' => 'ASC']],
            '3' => ['caption' => 'alkalom napja szerint', 'order' => ['_xx.datum' => 'ASC']],
            '4' => ['caption' => 'partner szerint', 'order' => ['partner.nev' => 'ASC']],
            '5' => ['caption' => 'időpont, majd partner szerint', 'order' => ['idopont.kezdet' => 'DESC', 'partner.nev' => 'ASC']],
        ]);
    }

    public function getWithJoins($filter, $order = [], $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,idopont,partner,dolgozo,idoponttema'
            . ' FROM Entities\Idopontfoglalas _xx'
            . ' LEFT JOIN _xx.idopont idopont'
            . ' LEFT JOIN _xx.partner partner'
            . ' LEFT JOIN idopont.dolgozo dolgozo'
            . ' LEFT JOIN idopont.idoponttema idoponttema'
            . $this->getFilterString($filter)
            . $this->getOrderString($order)
        );
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getResult();
    }

    public function findWithJoins($id)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('id', '=', $id);
        $rec = $this->getWithJoins($filter, []);
        if ($rec) {
            return $rec[0];
        }
        return null;
    }

    public function getCount($filter)
    {
        $q = $this->_em->createQuery(
            'SELECT COUNT(_xx)'
            . ' FROM Entities\Idopontfoglalas _xx'
            . ' LEFT JOIN _xx.idopont idopont'
            . ' LEFT JOIN _xx.partner partner'
            . ' LEFT JOIN idopont.dolgozo dolgozo'
            . ' LEFT JOIN idopont.idoponttema idoponttema'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    /**
     * A foglalás mindig egy naptári napra szól, ezért az ismétlődő időpontnál is naponként telik be.
     * A lemondott és a várólistás jelentkezés nem foglal helyet.
     *
     * @param int $idopontid
     * @param \DateTime|string|null $datum
     */
    public function getCountForIdopont($idopontid, $datum = null)
    {
        if (!$idopontid || !$datum) {
            return 0;
        }
        $q = $this->_em->createQuery(
            'SELECT COUNT(_xx) FROM Entities\Idopontfoglalas _xx'
            . ' WHERE _xx.idopont = :idopont AND _xx.datum = :datum'
            . ' AND _xx.lemondva = 0 AND _xx.varolistas = 0'
        );
        $q->setParameter('idopont', $idopontid);
        $q->setParameter('datum', self::toDate($datum), \Doctrine\DBAL\Types\Types::DATE_MUTABLE);
        return $q->getSingleScalarResult();
    }

    /**
     * Egy lekérdezéssel adja vissza az (időpont, nap) párokra a foglalásszámot, hogy a
     * megjelenítő ne alkalmanként külön COUNT-tal dolgozzon.
     *
     * @return array [idopont id][Y-m-d] => foglalásszám
     */
    public function getCountsForIdopontok(array $idopontids, array $datumok)
    {
        if (!$idopontids || !$datumok) {
            return [];
        }
        $q = $this->_em->createQuery(
            'SELECT IDENTITY(_xx.idopont) AS idopontid, _xx.datum AS datum, COUNT(_xx) AS db'
            . ' FROM Entities\Idopontfoglalas _xx'
            . ' WHERE _xx.idopont IN (:idopontok) AND _xx.datum IN (:datumok)'
            . ' AND _xx.lemondva = 0 AND _xx.varolistas = 0'
            . ' GROUP BY _xx.idopont, _xx.datum'
        );
        $q->setParameter('idopontok', $idopontids);
        $q->setParameter('datumok', array_map([self::class, 'toDateString'], $datumok));
        $res = [];
        foreach ($q->getResult() as $sor) {
            $datum = $sor['datum'] instanceof \DateTime
                ? $sor['datum']->format(\mkw\store::$SQLDateFormat)
                : substr((string)$sor['datum'], 0, 10);
            $res[$sor['idopontid']][$datum] = (int)$sor['db'];
        }
        return $res;
    }

    private static function toDate($datum)
    {
        if ($datum instanceof \DateTime) {
            return new \DateTime($datum->format(\mkw\store::$SQLDateFormat));
        }
        return new \DateTime(\mkw\store::convDate((string)$datum));
    }

    /** Az IN listához: DateTime tömböt a DBAL nem tud kötni, dátum stringet igen. */
    private static function toDateString($datum)
    {
        return self::toDate($datum)->format(\mkw\store::$SQLDateFormat);
    }

}
