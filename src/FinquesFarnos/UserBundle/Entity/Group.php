<?php

namespace FinquesFarnos\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseGroup as BaseGroup;

/**
 * Class Group
 *
 * @category Entity
 * @package  FinquesFarnos\UserBundle\Entity
 * @author   David Romaní <david@flux.cat>
 */
class Group extends BaseGroup
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
}
