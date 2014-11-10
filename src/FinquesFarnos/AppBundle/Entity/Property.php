<?php

namespace FinquesFarnos\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Property class
 *
 * @category Entity
 * @package  FinquesFarnos\AppBundle\Entity
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity(repositoryClass="FinquesFarnos\AppBundle\Repository\PropertyRepository")
 * @ORM\Table(name="property")
 * @Gedmo\TranslationEntity(class="FinquesFarnos\AppBundle\Entity\Translations\PropertyTranslation")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Property extends Base
{
	/**
     * @ORM\Column(type="string", length=255, name="name", nullable=false, unique=true)
     * @Gedmo\Translatable
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, name="name_slug", nullable=false)
     * @Gedmo\Slug(fields={"name"})
     * @var string
     */
    private $nameSlug;

    /**
     * @ORM\Column(type="text", length=4000, name="description", nullable=true)
     * @Gedmo\Translatable
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(name="price", type="float", precision=2, nullable=true)
     * @var float
     */
    protected $price = 0.0;

    /**
     * @ORM\Column(name="price_old", type="float", precision=2, nullable=true)
     * @var float
     */
    protected $oldPrice = 0.0;

    /**
     * @ORM\Column(name="rooms", type="integer", nullable=true)
     * @var integer
     */
    protected $rooms = 0;

    /**
     * @ORM\Column(name="bathrooms", type="integer", nullable=true)
     * @var integer
     */
    protected $bathrooms = 0;

    /**
     * @ORM\Column(name="offer_discount", type="boolean", nullable=false)
     * @var boolean
     */
    protected $offerDiscount = false;

    /**
     * @ORM\Column(name="offer_special", type="boolean", nullable=false)
     * @var boolean
     */
    protected $offerSpecial = false;

    /**
     * @ORM\Column(name="energy_class", type="integer", nullable=true)
     * @var integer
     */
    protected $energyClass = 0;

    /**
     * @ORM\Column(type="float", precision=14, name="gps_longitude", nullable=false)
     * @Assert\Range(min = -180, max = 180)
     * @var float
     */
    protected $gpsLongitude;

    /**
     * @ORM\Column(type="float", precision=14, name="gps_latitude", nullable=false)
     * @Assert\Range(min = -90, max = 90)
     * @var float
     */
    protected $gpsLatitude;

	/**
     * @ORM\OneToMany(
     *     targetEntity="FinquesFarnos\AppBundle\Entity\Translations\PropertyTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     * @Assert\Valid(deep = true)
     * @var ArrayCollection
     */
    protected $translations;

	/**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

	/**
     * To String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name ? $this->name : '---';
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name slug
     *
     * @param string $nameSlug
     *
     * @return $this
     */
    public function setNameSlug($nameSlug)
    {
        $this->nameSlug = $nameSlug;

        return $this;
    }

    /**
     * Get name slug
     *
     * @return string
     */
    public function getNameSlug()
    {
        return $this->nameSlug;
    }

    /**
     * Add translation
     *
     * @param Translations\PropertyTranslation $translation
     *
     * @return $this
     */
    public function addTranslation(Translations\PropertyTranslation $translation)
    {
        if ($translation->getContent()) {
            $translation->setObject($this);
            $this->translations[] = $translation;
        }

        return $this;
    }

    /**
     * Remove translation
     *
     * @param Translations\PropertyTranslation $translation
     *
     * @return $this
     */
    public function removeTranslation(Translations\PropertyTranslation $translation)
    {
        $this->translations->removeElement($translation);

        return $this;
    }

    /**
     * Set translations
     *
     * @param ArrayCollection $translations
     *
     * @return $this
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;

        return $this;
    }

    /**
     * Get translations
     *
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}
