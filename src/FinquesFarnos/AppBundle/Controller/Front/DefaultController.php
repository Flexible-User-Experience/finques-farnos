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
        $slides = $this->getDoctrine()->getRepository('AppBundle:ImageSlider')->getHomepageItems();
        $properties = $this->getDoctrine()->getRepository('AppBundle:Property')->getHomepageItems();

        return $this->render('::Front/homepage.html.twig', array(
                'slides' => $slides,
                'properties' => $properties,
            ));
    }

    /**
     * @Route("/properties/", name="front_properties")
     */
    public function propertiesAction()
    {
        return $this->render('::Front/properties.html.twig');
    }

    /**
     * @Route("/about-us/", name="front_about")
     */
    public function aboutAction()
    {
        return $this->render('::Front/about.html.twig');
    }

    /**
     * @Route("/contact/", name="front_contact")
     */
    public function contactAction()
    {
        return $this->render('::Front/contact.html.twig');
    }
}
