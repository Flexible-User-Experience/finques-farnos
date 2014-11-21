<?php

namespace FinquesFarnos\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PropertyVisit class
 *
 * @category Entity
 * @package  FinquesFarnos\AppBundle\Entity
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity(repositoryClass="FinquesFarnos\AppBundle\Repository\PropertyVisitRepository")
 * @ORM\Table(name="property_visit")
 */
class PropertyVisit extends Base
{
    /**
     * @ORM\ManyToOne(targetEntity="FinquesFarnos\AppBundle\Entity\Property", inversedBy="visits")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="property_id", referencedColumnName="id")})
     * @var Property
     */
    protected $property;

    /**
     * @var boolean
     */
    protected $enabled;

    /**
     * To String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->createdAt ? $this->getCreatedAt()->format('d/m/Y H:i:s') : '---';
    }

    /**
     * Set property
     *
     * @param Property $property
     *
     * @return $this
     */
    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
}
