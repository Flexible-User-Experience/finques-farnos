<?php

namespace FinquesFarnos\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ContactMessage class
 *
 * @category Entity
 * @package  FinquesFarnos\AppBundle\Entity
 * @author   David Romaní <david@flux.cat>
 *
 * @ORM\Entity(repositoryClass="FinquesFarnos\AppBundle\Repository\ContactMessageRepository")
 * @ORM\Table(name="contact_message")
 */
class ContactMessage extends Base
{
    /**
     * @ORM\ManyToOne(targetEntity="Contact", inversedBy="messages")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="contact_id", referencedColumnName="id")})
     * @var Contact
     */
    private $contact;

    /**
     * @ORM\Column(type="text", length=4000, name="text", nullable=false)
     * @var string
     */
    private $text;

    /**
     * Set Text
     *
     * @param string $text text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get Text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set Contact
     *
     * @param Contact $contact
     *
     * @return $this
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get Contact
     *
     * @return Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

	/**
     * To String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->text ? $this->text : '---';
    }
}
