<?php
namespace Entities;

use mkwhelpers\FilterDescriptor;
use Doctrine\ORM\Query\ResultSetMapping;


class TermekValtozatRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\TermekValtozat');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint növekvő', 'order' => array('_xx.nev' => 'ASC'))
        ));
    }

    public function getByProperties($termekid, $adattipusok, $ertekek) {
        $filter = new FilterDescriptor();
        $filter->addFilter('termek', '=', $termekid);

        if (count($adattipusok) == 1) {
            if ($ertekek[0]) {
                $filter->addSql('((_xx.adattipus1=' . $adattipusok[0] . ') AND (_xx.ertek1=\'' . $ertekek[0] . '\') AND (_xx.adattipus2 IS NULL)) OR '
                    . '((_xx.adattipus2=' . $adattipusok[0] . ') AND (_xx.ertek2=\'' . $ertekek[0] . '\') AND (_xx.adattipus1 IS NULL))');
            }
        }
        elseif (count($adattipusok) > 1) {
            if ($ertekek[0] || $ertekek[1]) {
                $stra = $strb = '(1=1)';
                if ($ertekek[0]) {
                    $stra = '((_xx.adattipus1=' . $adattipusok[0] . ') AND (_xx.ertek1=\'' . $ertekek[0] . '\')) OR ((_xx.adattipus2=' . $adattipusok[0] . ') AND (_xx.ertek2=\'' . $ertekek[0] . '\'))';
                }
                if ($ertekek[1]) {
                    $strb = '((_xx.adattipus2=' . $adattipusok[1] . ') AND (_xx.ertek2=\'' . $ertekek[1] . '\')) OR ((_xx.adattipus1=' . $adattipusok[1] . ') AND (_xx.ertek1=\'' . $ertekek[1] . '\'))';
                }
                $filter->addSql('((' . $stra . ') AND (' . $strb . '))');
            }
        }
        $res = $this->getAll($filter, array());
        return $res[0];
    }

    public function getOtherProperties($termekid, $adattipusok, $ertekek) {
        $filter = new FilterDescriptor();
        $filter->addFilter('termek', '=', $termekid);

        if (count($adattipusok) == 1) {
            if ($ertekek[0]) {
                $filter->addSql('((_xx.adattipus1=' . $adattipusok[0] . ') AND (_xx.ertek1=\'' . $ertekek[0] . '\')) OR '
                    . '((_xx.adattipus2=' . $adattipusok[0] . ') AND (_xx.ertek2=\'' . $ertekek[0] . '\'))');
            }
        }
        return $this->getAll($filter, array());
    }

    public function getDistinctErtek1() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('ertek1', 'ertek');
        $q = $this->_em->createNativeQuery('SELECT DISTINCT ertek1 FROM termekvaltozat', $rsm);
        return $q->getScalarResult();
    }

    public function getDistinctErtek2() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('ertek2', 'ertek');
        $q = $this->_em->createNativeQuery('SELECT DISTINCT ertek2 FROM termekvaltozat', $rsm);
        return $q->getScalarResult();
    }

}