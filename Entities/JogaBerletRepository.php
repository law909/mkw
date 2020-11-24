<?php
namespace Entities;

use mkwhelpers\FilterDescriptor;

class JogaBerletRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname(JogaBerlet::class);
        $this->setOrders(array(
            '1' => array('caption' => 'vásárlás dátum csökkenő', 'order' => array('_xx.nincsfizetve' => 'DESC', '_xx.vasarlasnapja' => 'DESC')),
            '2' => array('caption' => 'név, lejárat dátum csökkenő', 'order' => array('p.nev' => 'ASC', '_xx.lejaratdatum' => 'DESC'))
        ));
    }

    public function getWithJoins($filter, $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,t,p'
            . ' FROM Entities\JogaBerlet _xx'
            . ' LEFT JOIN _xx.termek t'
            . ' LEFT JOIN _xx.partner p'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getResult();
    }

    public function getCountWithJoins($filter) {
        $q = $this->_em->createQuery('SELECT COUNT(_xx)'
            . ' FROM Entities\JogaBerlet _xx'
            . ' LEFT JOIN _xx.termek t'
            . ' LEFT JOIN _xx.partner p'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getAktualisBerlet($partner) {
	    $filter = new FilterDescriptor();
	    $filter->addFilter('partner', '=', $partner);
	    $filter->addFilter('lejart', '=', false);
	    $res = $this->getAll($filter, array('vasarlasnapja' => 'DESC'));
	    if ($res) {
	        return $res[0];
        }
	    return null;
    }

    public function calcMegFelhasznalhato() {
	    $filter = new FilterDescriptor();
	    $filter->addFilter('lejart', '=', false);
	    $filter->addFilter('lejaratdatum', '>', date(\mkw\store::$SQLDateFormat));
        $q = $this->_em->createQuery('SELECT _xx,t'
            . ' FROM Entities\JogaBerlet _xx'
            . ' LEFT JOIN _xx.termek t'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        $ret = [
            'mennyiseg' => 0,
            'ertek' => 0
        ];
        $r = $q->getResult();
        /** @var \Entities\JogaBerlet $berlet */
        foreach($r as $berlet) {
            $ret['mennyiseg'] += $berlet->getAlkalom() - ($berlet->getElfogyottalkalom() + $berlet->getOfflineelfogyottalkalom());
            if ($berlet->getBruttoegysar() != 0) {
                $ret['ertek'] += $berlet->getBruttoegysar() / $berlet->getAlkalom()
                    * ($berlet->getAlkalom() - ($berlet->getElfogyottalkalom() + $berlet->getOfflineelfogyottalkalom()));
            }
            else {
                $ret['ertek'] += $berlet->getTermek()->getBruttoAr() / $berlet->getTermek()->getJogaalkalom()
                    * ($berlet->getAlkalom() - ($berlet->getElfogyottalkalom() + $berlet->getOfflineelfogyottalkalom()));
            }
        }
        return $ret;
    }

}