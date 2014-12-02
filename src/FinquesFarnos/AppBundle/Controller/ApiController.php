<?php

namespace FinquesFarnos\AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FrontController
 *
 * @category Controller
 * @package  FinquesFarnos\AppBundle\Controller
 * @author   David Romaní <david@flux.cat>
 *
 * @Rest\Prefix("api")
 * @Rest\NamePrefix("api_")
 */
class ApiController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get min max frontend properties form filters
     *
     * @Rest\View()
     * @Rest\Get("/get-properties-form-filter", options={"sitemap"=false, "expose"=true})
     */
    public function propertiesFormFilterAction()
    {
        $filters = $this->getDoctrine()->getRepository('AppBundle:Property')->getFilters();
        $data = array(
            'types' => $this->getDoctrine()->getRepository('AppBundle:Type')->getFilters(),
            'area' => array('min' => $filters['min_area'], 'max' => $filters['max_area']),
            'rooms' => array('min' => $filters['min_rooms'], 'max' => $filters['max_rooms']),
            'price' => array('min' => $filters['min_price'], 'max' => $filters['max_price']),
        );

        return $data;
    }

    /**
     * Get filtered properties
     *
     * @Rest\View()
     * @Rest\Get("/get-properties-filtered/{type}/{area}/{rooms}/{price}", options={"sitemap"=false, "expose"=true})
     *
     * @param int $type
     * @param int $area
     * @param int $rooms
     * @param int $price
     *
     * @return mixed
     */
    public function propertiesFilteredAction($type, $area, $rooms, $price)
    {
        if ($area !== 'undefined' && $rooms !== 'undefined' && $price !== 'undefined') {
            return array('properties' => $this->getDoctrine()->getRepository('AppBundle:Property')->filterBy($type, $area, $rooms, $price));
        }


        return array();
    }
}
