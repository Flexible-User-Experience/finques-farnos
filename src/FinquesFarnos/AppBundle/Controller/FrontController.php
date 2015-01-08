<?php

namespace FinquesFarnos\AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use FinquesFarnos\AppBundle\Entity\ContactMessage;
use FinquesFarnos\AppBundle\Entity\Property;
use FinquesFarnos\AppBundle\Entity\PropertyVisit;
use FinquesFarnos\AppBundle\Form\Type\ContactType;
use FinquesFarnos\AppBundle\Entity\Contact;
use FinquesFarnos\AppBundle\Service\MailerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class FrontController
 *
 * @category Controller
 * @package  FinquesFarnos\AppBundle\Controller
 * @author   David Romaní <david@flux.cat>
 */
class FrontController extends Controller
{
    /**
     * @Route("/", name="front_homepage", options={"sitemap" = true})
     */
    public function homepageAction()
    {
        return $this->render('Front/homepage.html.twig', array(
                'slides' => $this->getDoctrine()->getRepository('AppBundle:ImageSlider')->getHomepageItems(),
                'properties' => $this->getDoctrine()->getRepository('AppBundle:Property')->getHomepageItems(),
            ));
    }

    /**
     * @Route("/properties/", name="front_properties", options={"sitemap" = true})
     */
    public function propertiesAction()
    {
        $propertiesFormFilter = $this->forward('AppBundle:Api:propertiesFormFilter', array(), array('_format' => 'json'));
        $filters = json_decode($propertiesFormFilter->getContent(), true/* get array format */);
        $filteredProperties = $this->forward('AppBundle:Api:propertiesFiltered', array(
                // TODO: make dynamic & adaptative
                'type' => $filters['types'][0]['id'],
                'city' => $filters['cities'][0]['id'],
                'area' => 0, //intval(ceil(($filters['area']['max'] - $filters['area']['min']) / 2) + $filters['area']['min']),
                'rooms' => 0, //intval(ceil(($filters['rooms']['max'] - $filters['rooms']['min']) / 2) + $filters['rooms']['min']),
                'price' => 0, //intval(ceil(($filters['price']['max'] -$filters['price']['min']) / 2) + $filters['price']['min']),
            ), array('_format' => 'json'));

        return $this->render('Front/properties.html.twig', array(
                'propertiesFormFilter' => $propertiesFormFilter->getContent(),
                'filteredProperties'   => $filteredProperties->getContent(),
            ));
    }

    /**
     * @Route("/property/previous/{id}/", name="front_property_prev", options={"expose" = false})
     */
    public function prevPropertyForwardAction($id)
    {
        /** @var Property $previousProperty */
        $previousProperty = $this->getDoctrine()->getRepository('AppBundle:Property')->getEnabledPrevProperty($id);
        if (is_null($previousProperty)) {
            $previousProperty = $this->getDoctrine()->getRepository('AppBundle:Property')->getLastEnabledProperty();
            if (is_null($previousProperty)) {
                $previousProperty = $this->getDoctrine()->getRepository('AppBundle:Property')->find($id);
            }
        }

        return $this->redirectToRoute('front_property', array(
               'type' => $previousProperty->getType()->getNameSlug(),
               'city' => $previousProperty->getCity()->getNameSlug(),
               'name' => $previousProperty->getNameSlug(),
               'reference' => $previousProperty->getReference(),
            ));
    }

    /**
     * @Route("/property/next/{id}/", name="front_property_next", options={"expose" = false})
     */
    public function nextPropertyForwardAction($id)
    {
        /** @var Property $nextProperty */
        $nextProperty = $this->getDoctrine()->getRepository('AppBundle:Property')->getEnabledNextProperty($id);
        if (is_null($nextProperty)) {
            $nextProperty = $this->getDoctrine()->getRepository('AppBundle:Property')->getFirstEnabledProperty();
            if (is_null($nextProperty)) {
                $nextProperty = $this->getDoctrine()->getRepository('AppBundle:Property')->find($id);
            }
        }

        return $this->redirectToRoute('front_property', array(
                'type' => $nextProperty->getType()->getNameSlug(),
                'city' => $nextProperty->getCity()->getNameSlug(),
                'name' => $nextProperty->getNameSlug(),
                'reference' => $nextProperty->getReference(),
            ));
    }

    /**
     * @Route("/{type}/{city}/{name}/{reference}/", name="front_property", options={"expose" = true})
     * @ParamConverter("property", class="AppBundle:Property", options={"mapping": {"reference": "reference"}})
     */
    public function propertyAction(Request $request, Property $property)
    {
        $adminListPropertiesRoute = $this->generateUrl('admin_finquesfarnos_app_property_list');
        if (strpos($request->headers->get('referer'), $adminListPropertiesRoute) == 0) {
            // Referer request is not an admin route so add a visit record
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $visit = new PropertyVisit();
            $visit->setProperty($property);
            $em->persist($visit);
            $em->flush();
        }
        $contact = new Contact();
        $form = $this->createForm(new ContactType(), $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var MailerService $ms */
            $ms = $this->get('app.mailer.service');
            $fc = $request->get('contact');
            $ms->performPropertyDeliveryAction($form->getData(), $fc['message'], $property);

            $this->addFlash('success', 'mail sended');
        }

        $localization = array(
            'id' => $property->getId(),
            'coords' => $property->getGoogleMapsCords(),
            'radius' => $property->getRadius(),
            'smt' => $property->getShowMapType(),
        );

        return $this->render('Front/property.html.twig', array(
                'property' => $property,
                'localization' => json_encode($localization),
                'form' => $form->createView(),
            ));
    }

    /**
     * @Route("/about-us/", name="front_about", options={"sitemap" = true})
     */
    public function aboutAction()
    {
        return $this->render('Front/about.html.twig');
    }

    /**
     * @Route("/contact/", name="front_contact", options={"sitemap" = true})
     */
    public function contactAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(new ContactType(), $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var MailerService $ms */
            $ms = $this->get('app.mailer.service');
            $fc = $request->get('contact');
            $ms->performContactDeliveryAction($form->getData(), $fc['message']);

            return $this->redirect($this->generateUrl('front_contact_thankyou'));
        }

        return $this->render('Front/contact.html.twig', array(
                'form' => $form->createView(),
            ));
    }

    /**
     * @Route("/contact/thank-you/", name="front_contact_thankyou", options={"sitemap" = true})
     */
    public function contactThankYouAction()
    {
        return $this->render('Front/contact_thank_you.html.twig');
    }

    /**
     * @Route("/privacy/", name="front_privacy", options={"sitemap" = true})
     */
    public function privacyAction()
    {
        return $this->render('Front/privacy.html.twig');
    }

    /**
     * @Route("/legal/", name="front_legal", options={"sitemap" = true})
     */
    public function legalAction()
    {
        return $this->render('Front/legal.html.twig');
    }

    /**
     * @Route("/credits/", name="front_credits", options={"sitemap" = true})
     */
    public function creditsAction()
    {
        return $this->render('Front/credits.html.twig');
    }
}
