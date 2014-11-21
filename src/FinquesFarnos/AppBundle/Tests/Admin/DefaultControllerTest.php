<?php

namespace FinquesFarnos\AppBundle\Tests\Admin;

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
        $client = $this->getAdminClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * Get admin client
     *
     * @return Client
     */
    private function getAdminClient()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login');
        $form = $crawler->selectButton('_submit')->form(
            array(
                '_username' => 'admin',
                '_password' => 'jbMF7CZW',
            )
        );
        $client->submit($form);

        return $client;
    }

    /**
     * Urls provider
     *
     * @return array
     */
    public function provideUrls()
    {
        return array(
            array('/admin/dashboard'),
            array('/admin/slide/list'),
            array('/admin/slide/create'),
            array('/admin/slide/1/edit'),
            array('/admin/contact/list'),
            array('/admin/contact/create'),
            array('/admin/contact/1/edit'),
            array('/admin/category/list'),
            array('/admin/category/create'),
            array('/admin/category/1/edit'),
            array('/admin/type/list'),
            array('/admin/type/create'),
            array('/admin/type/1/edit'),
            array('/admin/image/list'),
            array('/admin/image/create'),
            array('/admin/image/1/edit'),
            array('/admin/property/list'),
            array('/admin/property/create'),
            array('/admin/property/1/edit'),
            array('/admin/property/1/pdf'),
            array('/admin/property/1/delete'),
        );
    }
}
