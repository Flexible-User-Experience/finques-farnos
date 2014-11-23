<?php

namespace FinquesFarnos\AppBundle\Controller\Front;

use FinquesFarnos\AppBundle\Form\Type\ContactType;
use FinquesFarnos\AppBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
    public function contactAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(new ContactType(), $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
//            $em->persist($post);
//            $em->flush();
//
//            return $this->redirect($this->generateUrl(
//                    'admin_post_show',
//                    array('id' => $post->getId())
//                ));
        }

        return $this->render('::Front/contact.html.twig', array(
                'form' => $form->createView(),
            ));
    }

    /**
     * @Route("/privacy/", name="front_privacy")
     */
    public function privacyAction()
    {
        return $this->render('::Front/privacy.html.twig');
    }

    /**
     * @Route("/legal/", name="front_legal")
     */
    public function legalAction()
    {
        return $this->render('::Front/legal.html.twig');
    }
}
