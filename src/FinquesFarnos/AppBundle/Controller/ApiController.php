<?php

namespace FinquesFarnos\AppBundle\Controller;

use FinquesFarnos\AppBundle\Entity\Category;
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
     *
     * @return array
     */
    public function propertiesFormFilterAction()
    {
        // categories
        $categories = array();
        $categoriesCollection = $this->getDoctrine()->getRepository('AppBundle:Category')->getEnabledItemsSortedByName();
        // hack to achieve i18n translated names array because getEnabledArrayResultFilters respository result method doesn't return tranlated names
        /** @var Category $category */
        foreach ($categoriesCollection as $category) {
            $categories[] = array('id' => $category->getId(), 'name' => $category->getName());
        }
        // types
        $types = array(array('id' => -1, 'name' => $this->get('translator')->trans('properties.form.select.any.type')));
        $typesCollection = $this->getDoctrine()->getRepository('AppBundle:Type')->getEnabledItemsSortedByName();
        // hack to achieve i18n translated names array because getEnabledArrayResultFilters respository result method doesn't return tranlated names
        /** @var Type $type */
        foreach ($typesCollection as $type) {
            $types[] = array('id' => $type->getId(), 'name' => $type->getName());
        }
        // cities
        $cities = $this->getDoctrine()->getRepository('AppBundle:City')->getEnabledItemsSortedByNameArrayResult();
        array_unshift($cities, array('id' => -1, 'name' => $this->get('translator')->trans('properties.form.select.any.city')));
        // property attributes
        $filters = $this->getDoctrine()->getRepository('AppBundle:Property')->getFilters();
        /** @var array $data */
        $data = array(
            'categories' => $categories,
            'types'      => $types,
            'cities'     => $cities,
            'area'       => array('min' => 0, 'max' => intval($filters['max_area'])),
            'rooms'      => array('min' => 0, 'max' => intval($filters['max_rooms'])),
            'price'      => array('min' => 0, 'max' => intval($filters['max_price'])),
        );

        return $data;
    }

    /**
     * Get filtered properties
     *
     * @Rest\View(serializerGroups={"api"})
     * @Rest\Get("/get-properties-filtered/{categories}/{type}/{city}/{area}/{rooms}/{price}", options={"expose"=true})
     *
     * @ApiDoc(
     *  section="Properties",
     *  resource=true,
     *  description="Get filtered properties"
     * )
     *
     * @param Request $request
     * @param string  $categories
     * @param int     $type
     * @param int     $city
     * @param int     $area
     * @param int     $rooms
     * @param int     $price
     *
     * @return array
     */
    public function propertiesFilteredAction(Request $request, $categories, $type, $city, $area, $rooms, $price)
    {
        $catArray = array();
        if ($categories != '-1' && $categories != 'any') {
            if (is_array($categories)) {
                $catArray = $categories;
            } else {
                $catArray = explode('-', $categories);
            }
        }
        $request->getSession()->set('pfilter', array($catArray, $type, $city, $area, $rooms, $price));

        return $this->getDoctrine()->getRepository('AppBundle:Property')->filterBy($catArray, $type, $city, $area, $rooms, $price);
    }

    /**
     * Get cities by type
     *
     * @Rest\View(serializerGroups={"api"})
     * @Rest\Get("/get-cities-by-type/{type}", options={"expose"=true})
     *
     * @ApiDoc(
     *  section="Properties",
     *  resource=true,
     *  description="Get filtered cities by type"
     * )
     *
     * @param int $type ID
     *
     * @return array
     */
    public function getCitiesByTypeAction($type)
    {
        /** @var array $cities */
        $cities = $this->getDoctrine()->getRepository('AppBundle:City')->getEnabledItemsFilteredByTypeIdSortedByNameArrayResult($type);
        array_unshift($cities, array('id' => -1, 'name' => $this->get('translator')->trans('properties.form.select.any.city')));

        return $cities;
    }

    /**
     * Set accept cookie action
     *
     * @Rest\View(serializerGroups={"api"})
     * @Rest\Get("/set-accept-cookie-warning", options={"expose"=true})
     *
     * @param Request $request
     *
     * @return array
     */
    public function setAcceptCookieWarningAction(Request $request)
    {
        $request->getSession()->set('acceptCookiesWarning', true);

        return array('result' => 'OK');
    }
}
