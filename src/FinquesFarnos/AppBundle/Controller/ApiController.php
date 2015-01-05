<?php

namespace FinquesFarnos\AppBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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
        $types = $this->getDoctrine()->getRepository('AppBundle:Type')->getFilters();
        array_unshift($types, array('id' => -1, 'name' => $this->get('translator')->trans('properties.form.select.any')));
        $filters = $this->getDoctrine()->getRepository('AppBundle:Property')->getFilters();
        $data = array(
            'types' => $types,
//            'area' => array('min' => intval($filters['min_area']), 'max' => intval($filters['max_area'])),
            'area' => array('min' => 0, 'max' => intval($filters['max_area'])),
            'rooms' => array('min' => 0, 'max' => intval($filters['max_rooms'])),
            'price' => array('min' => 0, 'max' => intval($filters['max_price'])),
        );

        return $data;
    }

    /**
     * Get filtered properties
     *
     * @Rest\View(serializerGroups={"api"})
     * @Rest\Get("/get-properties-filtered/{type}/{area}/{rooms}/{price}", options={"sitemap"=false, "expose"=true})
     *
     * @ApiDoc(
     *  section="Properties",
     *  resource=true,
     *  description="Get filtered properties"
     * )
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
            return $this->getDoctrine()->getRepository('AppBundle:Property')->filterBy($type, $area, $rooms, $price);
        }

        return array();
    }
}
