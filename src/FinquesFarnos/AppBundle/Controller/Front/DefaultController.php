<?php

namespace FinquesFarnos\AppBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 *
 * @category Controller
 * @package  FinquesFarnos\AppBundle\Controller
 * @author   David Romaní <david@flux.cat>
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="front_homepage")
     */
    public function homepageAction()
    {
        return $this->render('::Front/homepage.html.twig');
    }

    /**
     * @Route("/properties/", name="front_properties")
     */
    public function propertiesAction()
    {
        return $this->render('::Front/properties.html.twig');
    }
}
