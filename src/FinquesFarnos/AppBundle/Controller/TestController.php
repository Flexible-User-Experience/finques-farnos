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
     * @Route("/get/properties/filters", name="api_get_properties_filters", options={"sitemap"=false, "expose"=true})
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

    /**
     * @Route("/get/properties/{type}/{area}/{rooms}/{price}", name="api_get_properties", options={"sitemap"=false, "expose"=true})
     */
    public function filterAction($type, $area, $rooms, $price)
    {
        $data = $this->getDoctrine()->getRepository('AppBundle:Property')->findBy(array('rooms' => $rooms));

        return JsonResponse::create($data);
    }
}