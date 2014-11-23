<?php

namespace FinquesFarnos\AppBundle\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class DefaultControllerTest
 *
 * @category Test
 * @package  FinquesFarnos\AppBundle\Tests\Admin
 * @author   David Romaní <david@flux.cat>
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Test page is successful
     *
     * @dataProvider provideUrls
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
            array('/ca/'),
            array('/es/'),
            array('/en/'),
            array('/ca/immobles/'),
            array('/es/inmuebles/'),
            array('/en/properties/'),
            array('/ca/qui-som/'),
            array('/es/quien-somos/'),
            array('/en/about-us/'),
            array('/ca/contacte/'),
            array('/es/contacto/'),
            array('/en/contact/'),
            array('/ca/privacitat/'),
            array('/es/privacidad/'),
            array('/en/privacy/'),
            array('/ca/avis-legal/'),
            array('/es/aviso-legal/'),
            array('/en/legal/'),
        );
    }
}
