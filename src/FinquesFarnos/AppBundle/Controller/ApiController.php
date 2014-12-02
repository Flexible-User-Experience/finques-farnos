<?php

namespace FinquesFarnos\AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FrontController
 *
 * @category Controller
 * @package  FinquesFarnos\AppBundle\Controller
 * @author   David Romaní <david@flux.cat>
 *
 * @Rest\NamePrefix("api_")
 */
class ApiController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get min max frontend properties form filters
     *
     * @Rest\View()
     * @Rest\Get("/get-properties-form-filter", options={"sitemap"=false, "expose"=true})
     *
     * @var Request $request
     *
     * @return array
     */
    public function getPropertiesFormFilterAction(Request $request)
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
}
