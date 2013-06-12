<?php
namespace Entities;

use matt, \Doctrine\ORM, Doctrine\ORM\Query\ResultSetMapping, SIIKerES\Store;
class TermekRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Termek');
		$this->setOrders(array(
			'1'=>array('caption'=>'név szerint','order'=>array('nev'=>'ASC')),
			'2'=>array('caption'=>'cikkszám szerint','order'=>array('cikkszam'=>'ASC'))
		));
		$this->setBatches(array(
			'1'=>'címke hozzáadás',
			'2'=>'címke törlés',
			'3'=>'áthelyezés másik termékcsoportba',
			'4'=>'másik VTSZ hozzárendelés',
			'5'=>'másik ÁFA kulcs hozzárendelés'
		));
	}

	public function getAllForSelectList($filter,$order,$offset=0,$elemcount=0) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a.'.id,'.$a.'.nev '
			.' FROM '.$this->entityname.' '.$a
			.$this->getFilterString($filter)
			.$this->getOrderString($order));
		$q->setParameters($this->getQueryParameters($filter));
		if ($offset>0) {
			$q->setFirstResult($offset);
		}
		if ($elemcount>0) {
			$q->setMaxResults($elemcount);
		}
		return $q->getScalarResult();
	}

	public function getAllForBizonylattetel() {
		$rsm=new ResultSetMapping();
		$rsm->addScalarResult('id','id');
		$rsm->addScalarResult('nev','nev');
		$rsm->addScalarResult('cikkszam','cikkszam');
		$rsm->addScalarResult('me','me');
		$rsm->addScalarResult('vtsz_id','vtsz');
		$rsm->addScalarResult('afa_id','afa');
		$rsm->addScalarResult('kiszereles','kiszereles');
		$q=$this->_em->createNativeQuery('SELECT id,nev,cikkszam,me,vtsz_id,afa_id,kiszereles FROM termek WHERE inaktiv=0 ORDER BY nev',$rsm);
		return $q->getScalarResult();
	}

	public function getWithJoins($filter,$order,$offset=0,$elemcount=0) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a.',vtsz,afa,fa1,fa2,fa3'
			.' FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.vtsz vtsz'
			.' LEFT JOIN '.$a.'.afa afa'
			.' LEFT JOIN '.$a.'.termekfa1 fa1'
			.' LEFT JOIN '.$a.'.termekfa2 fa2'
			.' LEFT JOIN '.$a.'.termekfa3 fa3'
			.$this->getFilterString($filter)
			.$this->getOrderString($order));
		$q->setParameters($this->getQueryParameters($filter));
		if ($offset>0) {
			$q->setFirstResult($offset);
		}
		if ($elemcount>0) {
			$q->setMaxResults($elemcount);
		}
		return $q->getResult();
	}

	public function getIdsWithJoins($filter,$order,$offset=0,$elemcount=0) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a.'.id'
			.' FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.vtsz vtsz'
			.' LEFT JOIN '.$a.'.afa afa'
			.' LEFT JOIN '.$a.'.termekfa1 fa1'
			.' LEFT JOIN '.$a.'.termekfa2 fa2'
			.' LEFT JOIN '.$a.'.termekfa3 fa3'
			.$this->getFilterString($filter)
			.$this->getOrderString($order));
		$q->setParameters($this->getQueryParameters($filter));
		if ($offset>0) {
			$q->setFirstResult($offset);
		}
		if ($elemcount>0) {
			$q->setMaxResults($elemcount);
		}
		return $q->getScalarResult();
	}

	public function getCount($filter) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT COUNT('.$a.') FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.vtsz vtsz'
			.' LEFT JOIN '.$a.'.afa afa'
			.' LEFT JOIN '.$a.'.termekfa1 fa1'
			.' LEFT JOIN '.$a.'.termekfa2 fa2'
			.' LEFT JOIN '.$a.'.termekfa3 fa3'
			.$this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}

	public function getTermekLista($filter,$order,$offset,$elemcount) {
		$a=$this->alias;
 		$q=$this->_em->createQuery('SELECT '.$a.'.id,v.id AS valtozatid'
			.' FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.termekfa1 fa1'
			.' LEFT JOIN '.$a.'.termekfa2 fa2'
			.' LEFT JOIN '.$a.'.termekfa3 fa3'
			.' LEFT JOIN '.$a.'.valtozatok v WITH v.lathato=true AND v.elerheto=true'
			.$this->getFilterString($filter)
			.$this->getOrderString($order));
		$q->setParameters($this->getQueryParameters($filter));
		if ($offset>0) {
			$q->setFirstResult($offset);
		}
		if ($elemcount>0) {
			$q->setMaxResults($elemcount);
		}
		return $q->getScalarResult();
	}

	public function getTermekListaCount($filter) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT COUNT('.$a.') FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.termekfa1 fa1'
			.' LEFT JOIN '.$a.'.termekfa2 fa2'
			.' LEFT JOIN '.$a.'.termekfa3 fa3'
			.' LEFT JOIN '.$a.'.valtozatok v WITH v.lathato=true AND v.elerheto=true'
			.$this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}

	public function getTermekListaMaxAr($filter) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT MAX('.$a.'.brutto+IF(v.brutto IS NULL,0,v.brutto)) FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.termekfa1 fa1'
			.' LEFT JOIN '.$a.'.termekfa2 fa2'
			.' LEFT JOIN '.$a.'.termekfa3 fa3'
			.' LEFT JOIN '.$a.'.valtozatok v WITH v.lathato=true AND v.elerheto=true'
			.$this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}

	public function getForSitemapXml() {
		$rsm=new ResultSetMapping();
		$rsm->addScalarResult('id', 'id');
		$rsm->addScalarResult('slug','slug');
		$rsm->addScalarResult('lastmod','lastmod');
		$q=$this->_em->createNativeQuery('SELECT id,slug,lastmod'
			.' FROM termek '
			.' WHERE inaktiv=0'
			.' ORDER BY id',$rsm);
		return $q->getScalarResult();
	}

	public function getTermekIds($filter,$order) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT DISTINCT '.$a.'.id '
			.' FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.termekfa1 fa1'
			.' LEFT JOIN '.$a.'.termekfa2 fa2'
			.' LEFT JOIN '.$a.'.termekfa3 fa3'
			.' LEFT JOIN '.$a.'.valtozatok v WITH v.lathato=true AND v.elerheto=true'
			.$this->getFilterString($filter)
			.$this->getOrderString($order));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getScalarResult();
	}

	public function getFeedTermek() {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a
			.' FROM '.$this->entityname.' '.$a
			.' ORDER BY '.$a.'.id DESC');
		$q->setFirstResult(0);
		$q->setMaxResults(store::getParameter('feedtermekdb',30));
		return $q->getResult();
	}

	public function getAjanlottTermekek($db) {
		$filter=array();
		$filter['fields'][]='inaktiv';
		$filter['clauses'][]='=';
		$filter['values'][]=false;
		$filter['fields'][]='lathato';
		$filter['clauses'][]='=';
		$filter['values'][]=true;
		$filter['fields'][]='ajanlott';
		$filter['clauses'][]='=';
		$filter['values'][]=true;

		$ids=$this->getIdsWithJoins($filter,array());
		$r=array();
		foreach($ids as $id) {
			$r[]=$id['id'];
		}
		if (count($r)>0) {
			shuffle($r);
			$filter=array();
			$rand=array_rand($r,min($db,count($r)));
			$v=array();
			foreach((array)$rand as $kulcs) {
				$v[]=$r[$kulcs];
			}
			$filter['fields'][]='id';
			$filter['clauses'][]='IN';
			$filter['values'][]=$v;
			return $this->getWithJoins($filter,array(),0,$db);
		}
		else {
			return array();
		}
	}

	public function getKiemeltTermekek($filter,$db) {
		$kiemeltfilter=array();
		$kiemeltfilter['fields'][]='kiemelt';
		$kiemeltfilter['clauses'][]='=';
		$kiemeltfilter['values'][]=1;
		$kiemeltret=$this->getIdsWithJoins(array_merge_recursive($filter,$kiemeltfilter),array());
		$r=array();
		foreach($kiemeltret as $kiemeltr) {
			$r[]=$kiemeltr['id'];
		}
		if (count($r)>0) {
			shuffle($r);

			$kiemeltfilter=array();
			$rand=array_rand($r,min($db,count($r)));
			$v=array();
			foreach((array)$rand as $kulcs) {
				$v[]=$r[$kulcs];
			}
			$kiemeltfilter['fields'][]='id';
			$kiemeltfilter['clauses'][]='IN';
			$kiemeltfilter['values'][]=$v;
			return $this->getWithJoins($kiemeltfilter,array(),0,$db);
		}
		else {
			return array();
		}
	}

	public function getLegnepszerubbTermekek($db) {
		$filter=array();
		$filter['fields'][]='inaktiv';
		$filter['clauses'][]='=';
		$filter['values'][]=false;
		$filter['fields'][]='lathato';
		$filter['clauses'][]='=';
		$filter['values'][]=true;
		$order=array('_xx.megvasarlasdb'=>'DESC');

		return $this->getWithJoins($filter,$order,0,$db);
	}

	public function getNevek($keresett) {
		$a=$this->alias;
		$filter=array();
		$filter['fields'][]='_xx.nev';
		$filter['clauses'][]='LIKE';
		$filter['values'][]='%'.$keresett.'%';
		$order=array('_xx.nev'=>'ASC');
		$q=$this->_em->createQuery('SELECT '.$a.'.nev'
			.' FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.vtsz vtsz'
			.' LEFT JOIN '.$a.'.afa afa'
			.' LEFT JOIN '.$a.'.termekfa1 fa1'
			.' LEFT JOIN '.$a.'.termekfa2 fa2'
			.' LEFT JOIN '.$a.'.termekfa3 fa3'
			.$this->getFilterString($filter)
			.$this->getOrderString($order));
		$q->setParameters($this->getQueryParameters($filter));
		$res=$q->getScalarResult();
		$ret=array();
		foreach($res as $sor) {
			$ret[]=$sor['nev'];
		}
		return $ret;
	}
}