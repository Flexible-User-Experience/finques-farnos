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
            array('/ca/immoble/pdf/1/'),
            array('/es/inmueble/pdf/1/'),
            array('/en/property/pdf/1/'),
            array('/ca/qui-som/'),
            array('/es/quien-somos/'),
            array('/en/about-us/'),
            array('/ca/contacte/'),
            array('/es/contacto/'),
            array('/en/contact/'),
            array('/ca/contacte/gracies/'),
            array('/es/contacto/gracias/'),
            array('/en/contact/thank-you/'),
            array('/ca/privacitat/'),
            array('/es/privacidad/'),
            array('/en/privacy/'),
            array('/ca/avis-legal/'),
            array('/es/aviso-legal/'),
            array('/en/legal/'),
            array('/ca/credits/'),
            array('/es/creditos/'),
            array('/en/credits/'),
        );
    }

    /**
     * Test page is redirect
     *
     * @dataProvider provideRedirectUrls
     */
    public function testAdminPagesAreRedirects($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirection());
    }

    /**
     * Redirect Urls provider
     *
     * @return array
     */
    public function provideRedirectUrls()
    {
        return array(
            array('/ca/immoble/anterior/1/'),
            array('/es/inmueble/anterior/1/'),
            array('/en/property/previous/1/'),
            array('/ca/immoble/seguent/1/'),
            array('/es/inmueble/siguiente/1/'),
            array('/en/property/next/1/'),
        );
    }
}
