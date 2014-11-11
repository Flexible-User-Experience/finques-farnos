<?php

namespace FinquesFarnos\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Type class
 *
 * @category Entity
 * @package  FinquesFarnos\AppBundle\Entity
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity(repositoryClass="FinquesFarnos\AppBundle\Repository\TypeRepository")
 * @ORM\Table(name="type")
 * @Gedmo\TranslationEntity(class="FinquesFarnos\AppBundle\Entity\Translations\TypeTranslation")
 */
class Type extends Base
{
    /**
     * @ORM\OneToMany(targetEntity="Property", mappedBy="type", cascade={"persist"})
     * @var ArrayCollection
     */
    private $properties;

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
     * @ORM\OneToMany(
     *     targetEntity="FinquesFarnos\AppBundle\Entity\Translations\TypeTranslation",
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
        $this->properties = new ArrayCollection();
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
     * Add property
     *
     * @param Property $property
     *
     * @return $this
     */
    public function addProperty(Property $property)
    {
        $property->setType($this);
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

    /**
     * Add translation
     *
     * @param Translations\TypeTranslation $translation
     *
     * @return $this
     */
    public function addTranslation(Translations\TypeTranslation $translation)
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
     * @param Translations\TypeTranslation $translation
     *
     * @return $this
     */
    public function removeTranslation(Translations\TypeTranslation $translation)
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
