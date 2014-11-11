<?php

namespace FinquesFarnos\AppBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;

/**
 * LoadFixtures class
 *
 * @category Fixtures
 * @package  FinquesFarnos\AppBundle\DataFixtures\ORM
 * @author   David Romaní <david@flux.cat>
 */
class LoadFixtures extends DataFixtureLoader
{
    public function getFixtures()
    {
        return array(
            __DIR__ . DIRECTORY_SEPARATOR . 'fixtures.yml',
        );
    }
}
