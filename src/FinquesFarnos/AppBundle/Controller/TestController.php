<?php

namespace FinquesFarnos\AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class FrontController
 *
 * @category Controller
 * @package  FinquesFarnos\AppBundle\Controller
 * @author   David Romaní <david@flux.cat>
 */
class TestController extends Controller
{
    /**
     * @Route("/test", name="api_test", options={"sitemap"=false, "expose"=true})
     */
    public function testAction()
    {
        $data = array(
            'types' => array(array('name' => 'atic'), array('name' => 'pis')),
            'area' => array('min' => 10, 'max' => 50),
            'rooms' => array('min' => 1, 'max' => 5),
            'price' => array('min' => 30000, 'max' => 600000),
        );

        return JsonResponse::create($data);
    }
}