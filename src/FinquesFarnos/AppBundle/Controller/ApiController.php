<?php

namespace FinquesFarnos\AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;

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
     * @Rest\Get("/get-properties-form-filter", name="get_properties_form_filter", options={"sitemap"=false, "expose"=true})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPropertiesFormFilterAction()
    {
        $fakes = array(
            'types' => array('0' => 'atic', '1' => 'pis'),
            'area' => array('min' => 10, 'max' => 50),
            'rooms' => array('min' => 1, 'max' => 5),
            'price' => array('min' => 30000, 'max' => 600000),
        );

        return $fakes;
    }
}
