<?php

namespace FinquesFarnos\AppBundle\Controller;

use FinquesFarnos\AppBundle\Entity\Type;
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
     * @Rest\Get("/get-properties-form-filter", options={"expose"=true})
     */
    public function propertiesFormFilterAction()
    {
        $cities = $this->getDoctrine()->getRepository('AppBundle:City')->getEnabledItemsSortedByNameArrayResult();
        array_unshift($cities, array('id' => -1, 'name' => $this->get('translator')->trans('properties.form.select.any.city')));
        $types = array(array('id' => -1, 'name' => $this->get('translator')->trans('properties.form.select.any.type')));
        $typesCollection = $this->getDoctrine()->getRepository('AppBundle:Type')->getEnabledItemsSortedByName();
        // hack to achieve i18n translated names array because getEnabledArrayResultFilters respository result method doesn't return tranlated names
        /** @var Type $type */
        foreach ($typesCollection as $type) {
            $types[] = array('id' => $type->getId(), 'name' => $type->getName());
        }
        $filters = $this->getDoctrine()->getRepository('AppBundle:Property')->getFilters();
        $data = array(
            'types' => $types,
            'cities' => $cities,
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
     * @Rest\Get("/get-properties-filtered/{type}/{city}/{area}/{rooms}/{price}", options={"expose"=true})
     *
     * @ApiDoc(
     *  section="Properties",
     *  resource=true,
     *  description="Get filtered properties"
     * )
     *
     * @param int $type
     * @param int $city
     * @param int $area
     * @param int $rooms
     * @param int $price
     *
     * @return mixed
     */
    public function propertiesFilteredAction($type, $city, $area, $rooms, $price)
    {
        if ($area !== 'undefined' && $rooms !== 'undefined' && $price !== 'undefined') {
            return $this->getDoctrine()->getRepository('AppBundle:Property')->filterBy($type, $city, $area, $rooms, $price);
        }

        return array();
    }
}
