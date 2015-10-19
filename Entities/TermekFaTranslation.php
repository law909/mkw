<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="termekfa_translations",
 *     options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class TermekFaTranslation extends AbstractPersonalTranslation {

    /**
     * @ORM\ManyToOne(targetEntity="TermekFa", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}

