<?php

namespace FinquesFarnos\AppBundle\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * TypeTranslation class
 *
 * @category Translation
 * @package  FinquesFarnos\AppBundle\Entity\Translations
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity
 * @ORM\Table(name="type_translation",
 *   uniqueConstraints={@ORM\UniqueConstraint(name="lookup_type_unique_idx", columns={
 *     "locale", "object_id", "field"
 *   })}
 * )
 */
class TypeTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="FinquesFarnos\AppBundle\Entity\Type", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
