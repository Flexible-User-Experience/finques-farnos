<?php

namespace FinquesFarnos\AppBundle\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * CategoryTranslation class
 *
 * @category Translation
 * @package  FinquesFarnos\AppBundle\Entity\Translations
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity
 * @ORM\Table(name="category_translation",
 *   uniqueConstraints={@ORM\UniqueConstraint(name="lookup_category_unique_idx", columns={
 *     "locale", "object_id", "field"
 *   })}
 * )
 */
class CategoryTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="FinquesFarnos\AppBundle\Entity\Category", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
