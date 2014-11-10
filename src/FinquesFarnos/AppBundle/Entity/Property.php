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
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="properties", cascade={"persist"})
     * @var ArrayCollection
     */
    protected $categories;

    /**
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="properties")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="property_id", referencedColumnName="id")})
     * @var Type
     */
    protected $type;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="property", cascade={"persist"})
     * @var ArrayCollection
     */
    protected $images;

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
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
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
     * Set Bathrooms
     *
     * @param int $bathrooms bathrooms
     *
     * @return $this
     */
    public function setBathrooms($bathrooms)
    {
        $this->bathrooms = $bathrooms;

        return $this;
    }

    /**
     * Get Bathrooms
     *
     * @return int
     */
    public function getBathrooms()
    {
        return $this->bathrooms;
    }

    /**
     * Set Description
     *
     * @param string $description description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set EnergyClass
     *
     * @param int $energyClass energyClass
     *
     * @return $this
     */
    public function setEnergyClass($energyClass)
    {
        $this->energyClass = $energyClass;

        return $this;
    }

    /**
     * Get EnergyClass
     *
     * @return int
     */
    public function getEnergyClass()
    {
        return $this->energyClass;
    }

    /**
     * Set GpsLatitude
     *
     * @param float $gpsLatitude gpsLatitude
     *
     * @return $this
     */
    public function setGpsLatitude($gpsLatitude)
    {
        $this->gpsLatitude = $gpsLatitude;

        return $this;
    }

    /**
     * Get GpsLatitude
     *
     * @return float
     */
    public function getGpsLatitude()
    {
        return $this->gpsLatitude;
    }

    /**
     * Set GpsLongitude
     *
     * @param float $gpsLongitude gpsLongitude
     *
     * @return $this
     */
    public function setGpsLongitude($gpsLongitude)
    {
        $this->gpsLongitude = $gpsLongitude;

        return $this;
    }

    /**
     * Get GpsLongitude
     *
     * @return float
     */
    public function getGpsLongitude()
    {
        return $this->gpsLongitude;
    }

    /**
     * Set OfferDiscount
     *
     * @param boolean $offerDiscount offerDiscount
     *
     * @return $this
     */
    public function setOfferDiscount($offerDiscount)
    {
        $this->offerDiscount = $offerDiscount;

        return $this;
    }

    /**
     * Get OfferDiscount
     *
     * @return boolean
     */
    public function getOfferDiscount()
    {
        return $this->offerDiscount;
    }

    /**
     * Set OfferSpecial
     *
     * @param boolean $offerSpecial offerSpecial
     *
     * @return $this
     */
    public function setOfferSpecial($offerSpecial)
    {
        $this->offerSpecial = $offerSpecial;

        return $this;
    }

    /**
     * Get OfferSpecial
     *
     * @return boolean
     */
    public function getOfferSpecial()
    {
        return $this->offerSpecial;
    }

    /**
     * Set OldPrice
     *
     * @param float $oldPrice oldPrice
     *
     * @return $this
     */
    public function setOldPrice($oldPrice)
    {
        $this->oldPrice = $oldPrice;

        return $this;
    }

    /**
     * Get OldPrice
     *
     * @return float
     */
    public function getOldPrice()
    {
        return $this->oldPrice;
    }

    /**
     * Set Price
     *
     * @param float $price price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get Price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set Rooms
     *
     * @param int $rooms rooms
     *
     * @return $this
     */
    public function setRooms($rooms)
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * Get Rooms
     *
     * @return int
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Set Type
     *
     * @param \FinquesFarnos\AppBundle\Entity\Type $type type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get Type
     *
     * @return \FinquesFarnos\AppBundle\Entity\Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add category
     *
     * @param Category $category
     *
     * @return $this
     */
    public function addCategory(Category $category)
    {
        $category->addProperty($this);
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param Category $category
     *
     * @return $this
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * Set categories
     *
     * @param ArrayCollection $categories
     *
     * @return $this
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add image
     *
     * @param Image $image
     *
     * @return $this
     */
    public function addImage(Image $image)
    {
        $image->setProperty($this);
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param Image $image
     *
     * @return $this
     */
    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);

        return $this;
    }

    /**
     * Set Images
     *
     * @param ArrayCollection $images images
     *
     * @return $this
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * Get Images
     *
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
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
