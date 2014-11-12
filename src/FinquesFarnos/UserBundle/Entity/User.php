<?php

namespace FinquesFarnos\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;

/**
 * Class User
 *
 * @category Entity
 * @package  FinquesFarnos\UserBundle\Entity
 * @author   David Romaní <david@flux.cat>
 */
class User extends BaseUser
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
