<?php

namespace FinquesFarnos\AppBundle\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * ImageSliderTranslation class
 *
 * @category Translation
 * @package  FinquesFarnos\AppBundle\Entity\Translations
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity
 * @ORM\Table(name="image_slider_translation",
 *   uniqueConstraints={@ORM\UniqueConstraint(name="lookup_image_slider_unique_idx", columns={
 *     "locale", "object_id", "field"
 *   })}
 * )
 */
class ImageSliderTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="FinquesFarnos\AppBundle\Entity\ImageSlider", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
