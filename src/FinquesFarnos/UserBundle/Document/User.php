<?php

namespace FinquesFarnos\UserBundle\Document;

use Sonata\UserBundle\Document\BaseUser as BaseUser;

/**
 * Class User
 *
 * @category Document
 * @package  FinquesFarnos\AppBundle\UserBundle\Document
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
