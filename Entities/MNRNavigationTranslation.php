<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="mnrnavigation_translations",
 *     options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class MNRNavigationTranslation extends AbstractPersonalTranslation {

    /**
     * @ORM\ManyToOne(targetEntity="MNRNavigation", inversedBy="translations", cascade={"persist"})
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

    /**
     * Convenient constructor
     *
     * @param string $locale
     * @param string $field
     * @param string $value
     */
    public function __construct($locale, $field, $value) {
        $this->setLocale($locale);
        $this->setField($field);
        $this->setContent($value);
    }
}

