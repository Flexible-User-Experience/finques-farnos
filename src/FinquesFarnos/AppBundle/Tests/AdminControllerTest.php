<?php

namespace FinquesFarnos\AppBundle\Tests;

/**
 * Class DefaultControllerTest
 *
 * @category Test
 * @package  FinquesFarnos\AppBundle\Tests
 * @author   David Romaní <david@flux.cat>
 */
class AdminControllerTest extends AbstractBaseTest
{
    /**
     * Test admin login request is successful
     */
    public function testAdminLoginPageIsSuccessful()
    {
        $client = $this->createClient();           // anonymous user
        $client->request('GET', '/admin/login');

        $this->assertStatusCode(200, $client);
    }

    /**
     * Test HTTP request is successful
     *
     * @dataProvider provideSuccessfulUrls
     * @param string $url
     */
    public function testAdminPagesAreSuccessful($url)
    {
        $client = $this->makeClient(true);         // authenticated user
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
            array('/admin/customer/list'),
            array('/admin/customer/create'),
            array('/admin/customer/1/edit'),
        );
    }
}
