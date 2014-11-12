<?php

namespace FinquesFarnos\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * To String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->createdAt ? $this->getCreatedAt()->format('Y') : '---'; // TODO fix property visit date format
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
}
