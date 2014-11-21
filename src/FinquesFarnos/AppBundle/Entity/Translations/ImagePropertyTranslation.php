<?php

namespace FinquesFarnos\AppBundle\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * ImagePropertyTranslation class
 *
 * @category Translation
 * @package  FinquesFarnos\AppBundle\Entity\Translations
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity
 * @ORM\Table(name="image_property_translation",
 *   uniqueConstraints={@ORM\UniqueConstraint(name="lookup_image_property_unique_idx", columns={
 *     "locale", "object_id", "field"
 *   })}
 * )
 */
class ImagePropertyTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="FinquesFarnos\AppBundle\Entity\ImageProperty", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
