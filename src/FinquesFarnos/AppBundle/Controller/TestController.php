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
        $filters = $this->getDoctrine()->getRepository('AppBundle:Property')->getFilters();
        $data = array(
            'types' => $this->getDoctrine()->getRepository('AppBundle:Type')->getFilters(),
            'area' => array('min' => $filters['min_area'], 'max' => $filters['max_area']),
            'rooms' => array('min' => $filters['min_rooms'], 'max' => $filters['max_rooms']),
            'price' => array('min' => $filters['min_price'], 'max' => $filters['max_price']),
        );

        return JsonResponse::create($data);
    }

    /**
     * @Route("/get/properties/{type}/{area}/{rooms}/{price}", name="api_get_properties", options={"sitemap"=false, "expose"=true})
     */
    public function filterAction($type, $area, $rooms, $price)
    {
        if ($area !== 'undefined' && $rooms !== 'undefined' && $price !== 'undefined') {
            return JsonResponse::create($this->getDoctrine()->getRepository('AppBundle:Property')->searchBy($type, $area, $rooms, $price));
        }


        return JsonResponse::create(array());
    }
}
