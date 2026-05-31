<?php

namespace Entities;

class AfaRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Afa::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']],
            '2' => ['caption' => 'név szerint csökkenő', 'order' => ['_xx.nev' => 'DESC']],
            '3' => ['caption' => 'kulcs szerint növekvő', 'order' => ['_xx.ertek' => 'ASC']],
            '4' => ['caption' => 'kulcs szerint csökkenő', 'order' => ['_xx.ertek' => 'DESC']],
        ]);
    }

    public function getNavcaseList($sel = null)
    {
        return [
            [
                'id' => '',
                'caption' => '',
                'selected' => ($sel == ''),
                'reason' => ''
            ],
            [
                'id' => 'AAM',
                'caption' => 'Alanyi adómentes',
                'selected' => ($sel == 'AAM'),
                'reason' => 'Áfa tv. XIII. fejezet'
            ],
            [
                'id' => 'TAM',
                'caption' => 'Tárgyi adómentes',
                'selected' => ($sel == 'TAM'),
                'reason' => 'Áfa tv. 85. §, 86. §'
            ],
            [
                'id' => 'KBAET',
                'caption' => 'Adómentes Közösségen belüli termékértékesítés',
                'selected' => ($sel == 'KBAET'),
                'reason' => 'Áfa tv. 89. §'
            ],
            [
                'id' => 'KBAUK',
                'caption' => 'Adómentes Közösségen belüli új közlekedési eszköz értékesítés',
                'selected' => ($sel == 'KBAUK'),
                'reason' => 'Áfa tv. 89. § (2)'
            ],
            [
                'id' => 'EAM',
                'caption' => 'Adómentes termékértékesítés a Közösség területén kívülre',
                'selected' => ($sel == 'EAM'),
                'reason' => 'Áfa tv. 98-109. §'
            ],
            [
                'id' => 'NAM',
                'caption' => 'Egyéb nemzetközi ügyletekhez kapcsolódó jogcímen megállapított adómentesség',
                'selected' => ($sel == 'NAM'),
                'reason' => 'Áfa tv. 110-118. §'
            ]
        ];
    }

    public function getNavReason($case)
    {
        $tomb = $this->getNavcaseList();
        foreach ($tomb as $item) {
            if ($item['id'] === $case) {
                return $item['reason'];
            }
        }
        return '';
    }
}