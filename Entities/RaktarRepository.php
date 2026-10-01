<?php
namespace Entities;

class RaktarRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Raktar');
		$this->setOrders([
		    '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']],
		    '2' => ['caption' => 'név szerint csökkenő', 'order' => ['_xx.nev' => 'DESC']],
		]);
	}

	public function getAllActive() {
	    $filter = new \mkwhelpers\FilterDescriptor();
	    // az archiv nullable és alapérték nélküli: a puszta `<> 1` a NULL sorokat is kizárná
	    $filter->addSql('((_xx.archiv IS NULL) OR (_xx.archiv <> 1))');
	    return $this->getAll($filter, ['nev' => 'ASC']);
    }

    /**
     * Ids of the warehouses whose stock counts in the given webshop (default: this deployment's): the
     * visible ones, never an archived one. Returns null when every warehouse counts - the callers then run
     * unfiltered, which keeps the common single-webshop case free of an extra IN () on every stock query.
     *
     * @return int[]|null
     */
    public function getWebshopRaktarIds($webshopnum = null)
    {
        $num = (int)($webshopnum ?: \mkw\store::getWebshopNum());
        $mezo = 'lathato' . ($num > 1 && $num <= 15 ? $num : '');
        $rows = $this->_em->getConnection()->fetchAllAssociative(
            'SELECT id, ' . $mezo . ' AS lathato, archiv FROM raktar'
        );
        $lathatok = [];
        $vanrejtett = false;
        foreach ($rows as $row) {
            if ($row['lathato'] && !$row['archiv']) {
                $lathatok[] = (int)$row['id'];
            } else {
                $vanrejtett = true;
            }
        }
        return $vanrejtett ? $lathatok : null;
    }
}