<?php
namespace Entities;

class AfaRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Afa');
	}

	public function getNavcaseList($sel = null) {
	    return array(
	        array(
	            'id' => '',
                'caption' => '',
                'selected' => ($sel == '')
            ),
	        array(
	            'id' => 'AAM',
                'caption' => 'Alanyi adómentes',
                'selected' => ($sel == 'AAM')
            ),
            array(
                'id' => 'TAM',
                'caption' => 'Tárgyi adómentes',
                'selected' => ($sel == 'TAM')
            ),
            array(
                'id' => 'KBAET',
                'caption' => 'Adómentes Közösségen belüli termékértékesítés',
                'selected' => ($sel == 'KBAET')
            ),
            array(
                'id' => 'KBAUK',
                'caption' => 'Adómentes Közösségen belüli új közlekedési eszköz értékesítés',
                'selected' => ($sel == 'KBAUK')
            ),
            array(
                'id' => 'EAM',
                'caption' => 'Adómentes termékértékesítés a Közösség területén kívülre',
                'selected' => ($sel == 'EAM')
            ),
            array(
                'id' => 'NAM',
                'caption' => 'Egyéb nemzetközi ügyletekhez kapcsolódó jogcímen megállapított adómentesség',
                'selected' => ($sel == 'NAM')
            )
        );
    }
}