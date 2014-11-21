<?php

namespace FinquesFarnos\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class UserBundle
 *
 * @package  FinquesFarnos\AppBundle\UserBundle
 * @author   David Romaní <david@flux.cat>
 */
class UserBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'SonataUserBundle';
    }
}
