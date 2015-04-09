<?php

namespace FinquesFarnos\AppBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 *
 * @category Test
 * @package  FinquesFarnos\AppBundle\Tests
 * @author   David Romaní <david@flux.cat>
 */
class ApiControllerTest extends WebTestCase
{
    /**
     * Test page is successful
     *
     * @dataProvider provideUrls
     *
     * @param string $url
     */
    public function testAdminPagesAreSuccessful($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * Urls provider
     *
     * @return array
     */
    public function provideUrls()
    {
        return array(
            array('/api/doc/'),
            array('/api/get-properties-form-filter.json'),
            array('/api/set-accept-cookie-warning.json'),
            array('/api/get-properties-filtered/-1/-1/-1/0/0/0.json'),
        );
    }
}
