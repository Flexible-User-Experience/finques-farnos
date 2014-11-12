<?php

namespace FinquesFarnos\UserBundle\Document;

use Sonata\UserBundle\Document\BaseGroup as BaseGroup;

/**
 * Class Group
 *
 * @category Document
 * @package  FinquesFarnos\AppBundle\UserBundle\Document
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
