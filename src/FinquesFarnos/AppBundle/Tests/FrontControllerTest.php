<?php

namespace FinquesFarnos\AppBundle\Tests;

/**
 * Class DefaultControllerTest
 *
 * @category Test
 * @package  FinquesFarnos\AppBundle\Tests
 * @author   David Romaní <david@flux.cat>
 */
class FrontControllerTest extends AbstractBaseTest
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
     * Test HTTP request is redirection
     *
     * @dataProvider provideRedirectUrls
     * @param string $url
     */
    public function testPagesAreRedirect($url)
    {
        $client = $this->createClient();
        $client->request('GET', $url);

        $this->assertStatusCode(302, $client);
    }

    /**
     * Redirect Urls provider
     *
     * @return array
     */
    public function provideRedirectUrls()
    {
        return array(
//            array('/ca/immoble/anterior/2/'),
//            array('/es/inmueble/anterior/2/'),
//            array('/en/property/previous/2/'),
//            array('/ca/immoble/seguent/2/'),
//            array('/es/inmueble/siguiente/2/'),
//            array('/en/property/next/2/'),
            array('/ca/immoble/tornar-al-llistat/'),
            array('/es/inmueble/volver-al-listado/'),
            array('/en/property/back-to-list/'),
        );
    }
}
