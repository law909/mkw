<?php

namespace Entities;

class EppnaploRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Eppnaplo::class);
    }

    /**
     * A /validate hibás próbálkozásai egy oldalra egy IP-ről. A rate limit miatt elutasított
     * kérések nem számítanak bele, különben a zárolás a próbálkozással együtt nyúlna.
     */
    public function countFailures(int $oldalid, string $ip, \DateTimeInterface $since): int
    {
        return (int)$this->_em->createQuery(
            'SELECT COUNT(_xx.id) FROM Entities\Eppnaplo _xx'
            . ' WHERE _xx.oldalid = :oldalid AND _xx.ip = :ip AND _xx.created >= :since'
            . ' AND _xx.vegpont = :vegpont AND _xx.eredmeny = :eredmeny'
        )
            ->setParameters([
                'oldalid' => $oldalid,
                'ip' => $ip,
                'since' => $since,
                'vegpont' => Eppnaplo::VEGPONTVALIDATE,
                'eredmeny' => Eppnaplo::EREDMENYHIBAS,
            ])
            ->getSingleScalarResult();
    }

    /**
     * @param int $napok ennél régebbi sorokat töröl
     *
     * @return int ahány sort elvitt
     */
    public function deleteOlderThan($napok)
    {
        $q = $this->_em->createQuery('DELETE FROM Entities\Eppnaplo _xx WHERE _xx.created < :hatar');
        $q->setParameter('hatar', new \DateTime('-' . (int)$napok . ' days'));
        return (int)$q->execute();
    }

}
