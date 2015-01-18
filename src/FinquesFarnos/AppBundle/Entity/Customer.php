<?php

namespace FinquesFarnos\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Customer class
 *
 * @category Entity
 * @package  FinquesFarnos\AppBundle\Entity
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity(repositoryClass="FinquesFarnos\AppBundle\Repository\CustomerRepository")
 * @ORM\Table(name="customer")
 */
class Customer extends Base
{
    /**
     * @ORM\OneToMany(targetEntity="Property", mappedBy="customer", cascade={"persist"})
     * @var ArrayCollection
     */
    private $properties;

    /**
     * @ORM\Column(type="string", length=255, name="name", nullable=false, unique=false)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, name="phone", nullable=true, unique=false)
     * @var string
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, name="email", nullable=true, unique=true)
     * @Assert\Email(
     *     strict = true,
     *     checkMX = true
     * )
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, name="dni", nullable=false, unique=true)
     * @var string
     */
    private $dni;

    /**
     * @ORM\Column(type="string", length=255, name="address", nullable=true, unique=false)
     * @var string
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, name="city", nullable=true, unique=false)
     * @var string
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, name="province", nullable=true, unique=false)
     * @var string
     */
    private $province;

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
     * Add property
     *
     * @param Property $property
     *
     * @return $this
     */
    public function addProperty(Property $property)
    {
        $property->setCustomer($this);
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
    public function removeProperty(Property $property)
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

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * @param string $dni
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param string $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }
}
