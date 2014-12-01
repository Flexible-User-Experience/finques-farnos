<?php

namespace FinquesFarnos\AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;

/**
 * Class FrontController
 *
 * @category Controller
 * @package  FinquesFarnos\AppBundle\Controller
 * @author   David Romaní <david@flux.cat>
 */
class ApiController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Rest\View()
     * @Rest\Get("/get-properties-form-filter")
     */
    public function getPropertiesFormFilterAction()
    {
//        $view = View::create();
        $data = array(
            'types' => array(0 => 'atic', 1 => 'pis'),
//            'area' => array('min' => 10, 'max' => 50),
//            'rooms' => array('min' => 1, 'max' => 5),
//            'price' => array('min' => 30000, 'max' => 600000),
        );
//        $view->setData($fakes);

        return $data;
//        return $view;
    }
}
