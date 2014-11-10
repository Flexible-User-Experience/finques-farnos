<?php

namespace FinquesFarnos\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Image class
 *
 * @category Entity
 * @package  FinquesFarnos\AppBundle\Entity
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity(repositoryClass="FinquesFarnos\AppBundle\Repository\ImageRepository")
 * @ORM\Table(name="image")
 * @Gedmo\TranslationEntity(class="FinquesFarnos\AppBundle\Entity\Translations\ImageTranslation")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @Vich\Uploadable
 */
class Image extends Base
{
    /**
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="images")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="image_id", referencedColumnName="id")})
     * @var Property
     */
    protected $property;

    /**
     * @Vich\UploadableField(mapping="property_image", fileNameProperty="imageName")
     * @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/png", "image/gif"},
     * )
     * @Assert\Image(minWidth = 1200)
     * @var File $imageFile
     */
    protected $imageFile;

    /**
     * @ORM\Column(type="string", length=255, name="image_name")
     * @var string $imageName
     */
    protected $imageName;

    /**
     * @ORM\Column(type="string", length=255, name="meta_title", nullable=true)
     * @Gedmo\Translatable
     * @var string
     */
    protected $metaTitle;

    /**
     * @ORM\Column(type="string", length=255, name="meta_alt", nullable=true)
     * @Gedmo\Translatable
     * @var string
     */
    protected $metaAlt;

    /**
     * @ORM\Column(name="position", type="integer", nullable=false)
     * @var integer
     */
    protected $position = 1;

	/**
     * @ORM\OneToMany(
     *     targetEntity="FinquesFarnos\AppBundle\Entity\Translations\ImageTranslation",
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
        return $this->imageName ? $this->imageName : '---';
    }

    /**
     * Set property
     *
     * @param Property $property property
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
     * Set Image file
     *
     * @param File|UploadedFile $image
     *
     * @return $this
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * Get image file
     *
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set image name
     *
     * @param $imageName
     *
     * @return $this
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get image name
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set Position
     *
     * @param int $position position
     *
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get Position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set meta alt
     *
     * @param string $metaAlt
     *
     * @return $this
     */
    public function setMetaAlt($metaAlt)
    {
        $this->metaAlt = $metaAlt;

        return $this;
    }

    /**
     * Get meta alt
     *
     * @return string
     */
    public function getMetaAlt()
    {
        return $this->metaAlt;
    }

    /**
     * Set meta title
     *
     * @param string $metaTitle
     *
     * @return $this
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get meta title
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Add translation
     *
     * @param Translations\ImageTranslation $translation
     *
     * @return $this
     */
    public function addTranslation(Translations\ImageTranslation $translation)
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
     * @param Translations\ImageTranslation $translation
     *
     * @return $this
     */
    public function removeTranslation(Translations\ImageTranslation $translation)
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
