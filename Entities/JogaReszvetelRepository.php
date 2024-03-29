<?php
namespace Entities;

use mkwhelpers\FilterDescriptor;

class JogaReszvetelRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\JogaReszvetel');
        $this->setOrders(array(
                             '1' => array(
                                 'caption' => 'dátum és tanár szerint csökkenő',
                                 'order' => array('_xx.datum' => 'DESC', 'ta.nev' => 'ASC', 'pa.nev' => 'ASC')
                             ),
                             '2' => array(
                                 'caption' => 'dátum és tanár szerint növekvő',
                                 'order' => array('_xx.datum' => 'ASC', 'ta.nev' => 'ASC', 'pa.nev' => 'ASC')
                             ),
                             '3' => array(
                                 'caption' => 'tanár és dátum szerint növekvő',
                                 'order' => array('ta.nev' => 'ASC', '_xx.datum' => 'ASC', 'pa.nev' => 'ASC')
                             )
                         ));
    }

    public function getWithJoins($filter, $order = array(), $offset = 0, $elemcount = 0)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,jt,jot,f,p,ta,pa,t '
            . ' FROM Entities\JogaReszvetel _xx'
            . ' LEFT JOIN _xx.jogaterem jt'
            . ' LEFT JOIN _xx.jogaoratipus jot'
            . ' LEFT JOIN _xx.fizmod f'
            . ' LEFT JOIN _xx.penztar p'
            . ' LEFT JOIN _xx.tanar ta'
            . ' LEFT JOIN _xx.partner pa'
            . ' LEFT JOIN _xx.termek t'
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

    public function getCount($filter)
    {
        $q = $this->_em->createQuery(
            'SELECT COUNT(_xx)'
            . ' FROM Entities\JogaReszvetel _xx'
            . ' LEFT JOIN _xx.jogaterem jt'
            . ' LEFT JOIN _xx.jogaoratipus jot'
            . ' LEFT JOIN _xx.fizmod f'
            . ' LEFT JOIN _xx.penztar p'
            . ' LEFT JOIN _xx.tanar ta'
            . ' LEFT JOIN _xx.partner pa'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getTanarTanitasnap($filter)
    {
        $q = $this->_em->createQuery('SELECT DISTINCT(_xx.datum)'
                                     . ' FROM Entities\JogaReszvetel _xx'
                                     . ' LEFT JOIN _xx.tanar ta'
                                     . ' LEFT JOIN ta.fizmod fm'
                                     . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        $res = $q->getArrayResult();
        return count($res);
    }

    /**
     * @param FilterDescriptor $filter
     * @param $honap
     * @return array
     */
    public function getTanarOsszesito($filter, $honap = 1) {
        $res = [];
        $q = $this->_em->createQuery('SELECT SUM(_xx.jutalek) AS jutalek,ta.nev,ta.id,ta.havilevonas*' . $honap . ' AS havilevonas,fm.nev AS fizmodnev,'
            . ' ta.napilevonas AS napilevonas'
            . ' FROM Entities\JogaReszvetel _xx'
            . ' LEFT JOIN _xx.tanar ta'
            . ' LEFT JOIN ta.fizmod fm'
            . $this->getFilterString($filter)
            . ' GROUP BY ta.id'
            . ' ORDER BY ta.nev');
        $q->setParameters($this->getQueryParameters($filter));
        $tos = $q->getResult();
        $xfilter = new FilterDescriptor();
        foreach ($tos as $to) {
            $xfilter->clear();
            $xfilter->addArray($filter->getArray());
            $xfilter->addFilter('tanar', '=', $to['id']);
            $napilevonas = $this->getTanarTanitasnap($xfilter) * $to['napilevonas'];
            $res[] = [
                'id' => $to['id'],
                'nev' => $to['nev'],
                'fizmodnev' => $to['fizmodnev'],
                'jutalek' => $to['jutalek'],
                'havilevonas' => $to['havilevonas'],
                'napilevonas' => $napilevonas
            ];
        }
        return $res;
    }

    public function getTermekOsszesito($filter, $honap = 1) {
        $q = $this->_em->createQuery('SELECT COUNT(_xx) AS db,t.nev,t.id'
            . ' FROM Entities\JogaReszvetel _xx'
            . ' LEFT JOIN _xx.termek t'
            . $this->getFilterString($filter)
            . ' GROUP BY t.id'
            . ' ORDER BY t.nev');
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getCountByBerlet($berletid) {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($berletid) {
            $filter->addFilter('jogaberlet', '=', $berletid);
        }
        $q = $this->_em->createQuery('SELECT COUNT(_xx)'
            . ' FROM Entities\JogaReszvetel _xx'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getTanarhozJarok($tanarid) {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('tanar', '=', $tanarid);
        $filter->addFilter('datum', '>=', '2020-03-15');
        $q = $this->_em->createQuery('SELECT DISTINCT(_xx.partner)'
            . ' FROM Entities\JogaReszvetel _xx'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return array_map('current', $q->getResult());
    }
}