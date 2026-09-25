<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * A product's place in a menu: at most one node per menu, hence the menu copied onto the row for the unique key.
 *
 * @ORM\Entity(repositoryClass="Entities\TermekMenuTermekRepository")
 * @ORM\Table(name="termekmenutermek",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * uniqueConstraints={@ORM\UniqueConstraint(name="termekmenutermek_termekfa_uniq",columns={"termek_id","termekmenufa_id"})},
 * indexes={@ORM\Index(name="termekmenutermek_menutermek_idx",columns={"termekmenu_id","termek_id"})}
 * )
 */
class TermekMenuTermek
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $lastmod;

    /**
     * @ORM\ManyToOne(targetEntity="Termek",inversedBy="termekmenuk")
     * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $termek;

    /**
     * @ORM\ManyToOne(targetEntity="TermekMenu")
     * @ORM\JoinColumn(name="termekmenu_id",referencedColumnName="id",nullable=false,onDelete="restrict")
     */
    private $termekmenu;

    /**
     * @ORM\ManyToOne(targetEntity="TermekMenuFa")
     * @ORM\JoinColumn(name="termekmenufa_id",referencedColumnName="id",nullable=false,onDelete="restrict")
     */
    private $termekmenufa;

    public function getId()
    {
        return $this->id;
    }

    public function getTermek()
    {
        return $this->termek;
    }

    public function setTermek(Termek $termek)
    {
        $this->termek = $termek;
    }

    public function getTermekmenu(): ?TermekMenu
    {
        return $this->termekmenu;
    }

    /** The menu comes with the node, so the two can not drift apart. */
    public function setTermekmenu(TermekMenu $termekmenu)
    {
        $this->termekmenu = $termekmenu;
        $this->termekmenufa = $termekmenu->getTermekmenufa();
    }

    public function getTermekmenufa(): ?TermekMenuFa
    {
        return $this->termekmenufa;
    }
}
