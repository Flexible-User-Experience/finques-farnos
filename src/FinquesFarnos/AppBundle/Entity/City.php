<?php

namespace FinquesFarnos\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * City class
 *
 * @category Entity
 * @package  FinquesFarnos\AppBundle\Entity
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity(repositoryClass="FinquesFarnos\AppBundle\Repository\CityRepository")
 * @ORM\Table(name="city")
 */
class City extends Base
{
    /**
     * @ORM\OneToMany(targetEntity="Property", mappedBy="city", cascade={"persist"})
     * @var ArrayCollection
     */
    private $properties;

    /**
     * @ORM\Column(type="string", length=255, name="name", nullable=false, unique=true)
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
     * Constructor
     */
    public function __construct()
    {
        $this->properties = new ArrayCollection();
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
     * Add property
     *
     * @param Property $property
     *
     * @return $this
     */
    public function addProperty(Property $property)
    {
        $property->setCity($this);
        $this->properties[] = $property;

        return $this;
    }

    /**
     * Remove property
     *
     * @param Property $property
     *
     * @return $this
     */
    public function removeExtension(Property $property)
    {
        $this->properties->removeElement($property);

        return $this;
    }

    /**
     * Set Properties
     *
     * @param ArrayCollection $properties
     *
     * @return $this
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * Get Properties
     *
     * @return ArrayCollection
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
