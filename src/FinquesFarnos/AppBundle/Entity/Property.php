<?php

namespace FinquesFarnos\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Oh\GoogleMapFormTypeBundle\Validator\Constraints as OhAssert;
use JMS\Serializer\Annotation as JMS;

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
 */
class Property extends Base
{
    const SHOW_MAP_ALL = 0;
    const SHOW_MAP_STREET = 1;
    const SHOW_MAP_AREA = 2;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="properties", cascade={"persist"})
     * @var ArrayCollection
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="properties", fetch="EAGER")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="type_id", referencedColumnName="id")})
     * @var Type
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="properties", fetch="EAGER")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="customer_id", referencedColumnName="id")})
     * @var Customer
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity="ImageProperty", mappedBy="property", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     * @var ArrayCollection
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="ContactMessage", mappedBy="property", cascade={"persist", "remove"})
     * @var ArrayCollection
     */
    private $messages;

    /**
     * @ORM\Column(type="string", length=16, name="reference", nullable=false, unique=true)
     * @JMS\Groups({"api"})
     * @Assert\Regex("/^[0-9a-zA-Z]+-?[0-9a-zA-Z]+$/", match=true, message="Només lletres i números, opcionalment separats entre mig per 1 guionet (-). Sense caràcters especials")
     * @var string
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255, name="name", nullable=false)
     * @Gedmo\Translatable
     * @JMS\Groups({"api"})
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, name="name_slug", nullable=false)
     * @Gedmo\Slug(fields={"name"})
     * @JMS\Groups({"api"})
     * @var string
     */
    private $nameSlug;

    /**
     * @ORM\Column(type="text", length=4000, name="description", nullable=true)
     * @Gedmo\Translatable
     * @JMS\Groups({"api"})
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(name="square_meters", type="integer", nullable=true)
     * @JMS\Groups({"api"})
     * @var integer
     */
    private $squareMeters = 0;

    /**
     * @ORM\Column(type="string", length=255, name="address", nullable=true)
     * @var string
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="properties", fetch="EAGER")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="city_id", referencedColumnName="id")})
     * @var City
     */
    private $city;

    /**
     * @ORM\Column(name="price", type="float", precision=2, nullable=true)
     * @JMS\Groups({"api"})
     * @var float
     */
    private $price = 0.0;

    /**
     * @ORM\Column(name="price_old", type="float", precision=2, nullable=true)
     * @JMS\Groups({"api"})
     * @var float
     */
    private $oldPrice = 0.0;

    /**
     * @ORM\Column(name="rooms", type="integer", nullable=true)
     * @JMS\Groups({"api"})
     * @var integer
     */
    private $rooms = 0;

    /**
     * @ORM\Column(name="bathrooms", type="integer", nullable=true)
     * @JMS\Groups({"api"})
     * @var integer
     */
    private $bathrooms = 0;

    /**
     * @ORM\Column(name="offer_discount", type="boolean", nullable=false)
     * @JMS\Groups({"api"})
     * @var boolean
     */
    private $offerDiscount = false;

    /**
     * @ORM\Column(name="offer_special", type="boolean", nullable=false)
     * @JMS\Groups({"api"})
     * @var boolean
     */
    private $offerSpecial = false;

    /**
     * @ORM\Column(name="show_in_homepage", type="boolean", nullable=false)
     * @var boolean
     */
    private $showInHomepage = false;

    /**
     * @ORM\Column(name="show_price_only_with_numbers", type="boolean", nullable=false)
     * @JMS\Groups({"api"})
     * @var boolean
     */
    private $showPriceOnlyWithNumbers = true;

    /**
     * @ORM\Column(name="reserved", type="boolean", nullable=false)
     * @JMS\Groups({"api"})
     * @var boolean
     */
    private $reserved = false;

    /**
     * @ORM\Column(name="sold", type="boolean", nullable=false)
     * @JMS\Groups({"api"})
     * @var boolean
     */
    private $sold = false;

    /**
     * @ORM\Column(name="sold_at", type="datetime", nullable=true)
     * @var \DateTime
     */
    private $soldAt;

    /**
     * @ORM\Column(name="energy_class", type="integer", nullable=true)
     * @var integer
     */
    private $energyClass = 0;

    /**
     * @ORM\Column(name="show_map_type", type="integer", nullable=false)
     * @var integer
     */
    private $showMapType = 0;

    /**
     * @ORM\Column(type="float", precision=14, name="gps_longitude", nullable=false)
     * @Assert\Range(min = -180, max = 180)
     * @var float
     */
    private $gpsLongitude = 0.5801695000000109;

    /**
     * @ORM\Column(type="float", precision=14, name="gps_latitude", nullable=false)
     * @Assert\Range(min = -90, max = 90)
     * @var float
     */
    private $gpsLatitude = 40.7067997;

    /**
     * @ORM\Column(name="radius", type="integer", nullable=true)
     * @JMS\Groups({"api"})
     * @var integer
     */
    private $radius = 0;

    /**
     * @ORM\OneToMany(targetEntity="FinquesFarnos\AppBundle\Entity\PropertyVisit", mappedBy="property", cascade={"persist", "remove"})
     * @ORM\OrderBy({"createdAt" = "ASC"})
     * @var ArrayCollection
     */
    private $visits;

    /**
     * @ORM\Column(name="total_visits", type="integer", nullable=true)
     * @var integer
     */
    private $totalVisits = 0;

    /**
     * @JMS\SerializedName("first_image_path")
     * @JMS\Groups({"api"})
     * @var string
     */
    private $virtualFirstEnabledImageUrl;

    /**
     * @ORM\OneToMany(
     *     targetEntity="FinquesFarnos\AppBundle\Entity\Translations\PropertyTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     * @Assert\Valid(deep = true)
     * @var ArrayCollection
     */
    private $translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->visits = new ArrayCollection();
    }

    /**
     * To String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->reference ? $this->reference.' · '.$this->name : '---';
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
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
     * Set SquareMeters
     *
     * @param int $squareMeters
     *
     * @return $this
     */
    public function setSquareMeters($squareMeters)
    {
        $this->squareMeters = $squareMeters;

        return $this;
    }

    /**
     * Get SquareMeters
     *
     * @return int
     */
    public function getSquareMeters()
    {
        return $this->squareMeters;
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
     * Set Radius
     *
     * @param int $radius
     *
     * @return $this
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;

        return $this;
    }

    /**
     * Get Radius
     *
     * @return int
     */
    public function getRadius()
    {
        return $this->radius;
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
     * Get decoreted Price
     *
     * @return float
     */
    public function getDecoratedPrice()
    {
        return number_format($this->getPrice(), 0, '\'', '.') . ' €';
    }

    /**
     * Get decoreted Price
     *
     * @return float
     */
    public function getDecoratedOldPrice()
    {
        return number_format($this->getOldPrice(), 0, '\'', '.') . ' €';
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
     * Set Address
     *
     * @param string $address address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get Address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param City|null $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set showInHomepage
     *
     * @param boolean $showInHomepage
     *
     * @return $this
     */
    public function setShowInHomepage($showInHomepage)
    {
        $this->showInHomepage = $showInHomepage;

        return $this;
    }

    /**
     * Get showInHomepage
     *
     * @return boolean
     */
    public function getShowInHomepage()
    {
        return $this->showInHomepage;
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
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
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
     * @param ImageProperty $image
     *
     * @return $this
     */
    public function addImage(ImageProperty $image)
    {
        $image->setProperty($this);
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param ImageProperty $image
     *
     * @return $this
     */
    public function removeImage(ImageProperty $image)
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
     * Add message
     *
     * @param ContactMessage $message
     *
     * @return $this
     */
    public function addMessage(ContactMessage $message)
    {
        $message->setProperty($this);
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param ContactMessage $message
     *
     * @return $this
     */
    public function removeMessage(ContactMessage $message)
    {
        $this->messages->removeElement($message);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param ArrayCollection $messages
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
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

    /**
     * Set visits
     *
     * @param ArrayCollection $visits
     *
     * @return $this
     */
    public function setVisits($visits)
    {
        $this->visits = $visits;

        return $this;
    }

    /**
     * Get visits
     *
     * @return ArrayCollection
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Add visit
     *
     * @param PropertyVisit $visit
     *
     * @return $this
     */
    public function addVisit(PropertyVisit $visit)
    {
        $visit->setProperty($this);
        $this->visits[] = $visit;

        return $this;
    }

    /**
     * Remove visit
     *
     * @param PropertyVisit $visit
     *
     * @return $this
     */
    public function removeVisit(PropertyVisit $visit)
    {
        $this->visits->removeElement($visit);

        return $this;
    }

    /**
     * Set totalVisits
     *
     * @param int $totalVisits
     *
     * @return $this
     */
    public function setTotalVisits($totalVisits)
    {
        $this->totalVisits = $totalVisits;

        return $this;
    }

    /**
     * Get totalVisits
     *
     * @return int
     */
    public function getTotalVisits()
    {
        return $this->totalVisits;
    }

    /**
     * Set showMapType
     *
     * @param int $showMapType
     *
     * @return $this
     */
    public function setShowMapType($showMapType)
    {
        $this->showMapType = $showMapType;

        return $this;
    }

    /**
     * Get showMapType
     *
     * @return int
     */
    public function getShowMapType()
    {
        return $this->showMapType;
    }

    /**
     * Set showPriceOnlyWithNumbers
     *
     * @param boolean $showPriceOnlyWithNumbers
     *
     * @return $this
     */
    public function setShowPriceOnlyWithNumbers($showPriceOnlyWithNumbers)
    {
        $this->showPriceOnlyWithNumbers = $showPriceOnlyWithNumbers;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isReserved()
    {
        return $this->reserved;
    }

    /**
     * @param boolean $reserved
     */
    public function setReserved($reserved)
    {
        $this->reserved = $reserved;
    }

    /**
     * @return boolean
     */
    public function isSold()
    {
        return $this->sold;
    }

    /**
     * @param boolean $sold
     */
    public function setSold($sold)
    {
        $this->sold = $sold;
    }

    /**
     * @return \DateTime
     */
    public function getSoldAt()
    {
        return $this->soldAt;
    }

    /**
     * @param \DateTime $soldAt
     */
    public function setSoldAt($soldAt)
    {
        $this->soldAt = $soldAt;
    }

    /**
     * Get showPriceOnlyWithNumbers
     *
     * @return boolean
     */
    public function getShowPriceOnlyWithNumbers()
    {
        return $this->showPriceOnlyWithNumbers;
    }

    /**
     * Set LatLng
     *
     * @param array $latlng
     *
     * @return $this
     */
    public function setLatLng($latlng)
    {
        $this->setGpsLatitude($latlng['lat']);
        $this->setGpsLongitude($latlng['lng']);

        return $this;
    }

    /**
     * Set VirtualFirstEnabledImageUrl
     *
     * @param string $virtualFirstEnabledImageUrl
     *
     * @return $this
     */
    public function setVirtualFirstEnabledImageUrl($virtualFirstEnabledImageUrl)
    {
        $this->virtualFirstEnabledImageUrl = $virtualFirstEnabledImageUrl;

        return $this;
    }

    /**
     * Get VirtualFirstEnabledImageUrl
     *
     * @return string
     */
    public function getVirtualFirstEnabledImageUrl()
    {
        return $this->virtualFirstEnabledImageUrl;
    }

    /**
     * Get LatLng
     *
     * @Assert\NotBlank()
     * @OhAssert\LatLng()
     *
     * @return array
     */
    public function getLatLng()
    {
        return array(
            'lat' => $this->getGpsLatitude(),
            'lng' => $this->getGpsLongitude(),
        );
    }

    /**
     * Get Google Maps coords format
     *
     * @JMS\VirtualProperty
     * @JMS\Type("array")
     * @JMS\SerializedName("coords")
     * @JMS\Groups({"api"})
     */
    public function getGoogleMapsCords()
    {
        return array(
            'latitude' => $this->getGpsLatitude(),
            'longitude' => $this->getGpsLongitude(),
        );
    }

    /**
     * Get first enabled image. The collection is sorted by position (see line #39)
     *
     * @return ImageProperty|null
     */
    public function getFirstEnabledImage()
    {
        $firstImage = null;
        /** @var ImageProperty $image */
        foreach ($this->images as $image) {
            if ($image->getEnabled()) {
                $firstImage = $image;
                break;
            }
        }

        return $firstImage;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\Type("string")
     * @JMS\SerializedName("type_name_slug")
     * @JMS\Groups({"api"})
     */
    public function getTypeNameSlug()
    {
        return $this->getType()->getNameSlug();
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\Type("string")
     * @JMS\SerializedName("city_name_slug")
     * @JMS\Groups({"api"})
     */
    public function getCityNameSlug()
    {
        return $this->getCity()->getNameSlug();
    }
}
