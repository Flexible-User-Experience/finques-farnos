<?php

namespace FinquesFarnos\AppBundle\Tests;

/**
 * Class DefaultControllerTest
 *
 * @category Test
 * @package  FinquesFarnos\AppBundle\Tests
 * @author   David Romaní <david@flux.cat>
 */
class ApiControllerTest extends AbstractBaseTest
{
    /**
     * Test HTTP request is successful
     *
     * @dataProvider provideUrls
     * @param string $url
     */
    public function testPagesAreSuccessful($url)
    {
        $client = $this->createClient();
        $client->request('GET', $url);

        $this->assertStatusCode(200, $client);
    }

    /**
     * Urls provider
     *
     * @return array
     */
    public function provideUrls()
    {
        return array(
            array('/api/doc'),
            array('/api/get-properties-form-filter.json'),
            array('/api/set-accept-cookie-warning.json'),
            array('/api/get-properties-filtered/-1/-1/-1/0/0/0.json'),
        );
    }
}
