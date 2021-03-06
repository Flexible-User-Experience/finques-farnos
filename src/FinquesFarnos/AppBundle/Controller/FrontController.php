<?php

namespace FinquesFarnos\AppBundle\Controller;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use FinquesFarnos\AppBundle\Entity\Property;
use FinquesFarnos\AppBundle\Entity\PropertyVisit;
use FinquesFarnos\AppBundle\Form\Type\ContactType;
use FinquesFarnos\AppBundle\Entity\Contact;
use FinquesFarnos\AppBundle\PdfGenerator\PropertyWebPdfGenerator;
use FinquesFarnos\AppBundle\Service\MailerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class FrontController.
 *
 * @category Controller
 *
 * @author   David Romaní <david@flux.cat>
 */
class FrontController extends Controller
{
    /**
     * @Route("/", name="front_homepage", options={"sitemap"=true})
     */
    public function homepageAction()
    {
        return $this->render('Front/homepage.html.twig', array(
                'slides' => $this->getDoctrine()->getRepository('AppBundle:ImageSlider')->getHomepageItems(),
                'properties' => $this->getDoctrine()->getRepository('AppBundle:Property')->getHomepageItems(),
            ));
    }

    /**
     * @Route("/properties", name="front_properties", options={"sitemap"=true})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function propertiesAction(Request $request)
    {
        $propertiesFormFilter = $this->forward('AppBundle:Api:propertiesFormFilter', array(), array('_format' => 'json'));
        if ($request->getSession()->has('isbacktolistredirect')) {
            $request->getSession()->remove('isbacktolistredirect');
            $selectedPropertiesFormFilter = array(
                $request->getSession()->get('pfilter')[0],
                intval($request->getSession()->get('pfilter')[1]),
                intval($request->getSession()->get('pfilter')[2]),
                intval($request->getSession()->get('pfilter')[3]),
                intval($request->getSession()->get('pfilter')[4]),
                intval($request->getSession()->get('pfilter')[5]),
            );
        } else {
            $ao = json_decode($propertiesFormFilter->getContent());
            $selectedPropertiesFormFilter = array(-1, -1, -1, $ao->{'area'}->{'max'}, $ao->{'rooms'}->{'max'}, $ao->{'price'}->{'max'});
        }
        $filteredProperties = $this->forward('AppBundle:Api:propertiesFiltered', array(
            'categories' => $selectedPropertiesFormFilter[0],
            'type' => $selectedPropertiesFormFilter[1],
            'city' => $selectedPropertiesFormFilter[2],
            'area' => $selectedPropertiesFormFilter[3],
            'rooms' => $selectedPropertiesFormFilter[4],
            'price' => $selectedPropertiesFormFilter[5],
        ), array('_format' => 'json'));

        return $this->render('Front/properties.html.twig', array(
                'propertiesFormFilter' => $propertiesFormFilter->getContent(),
                'selectedPropertiesFormFilter' => json_encode($selectedPropertiesFormFilter),
                'filteredProperties' => $filteredProperties->getContent(),
            ));
    }

    /**
     * @Route("/properties-inmopc", name="front_properties_inmopc", options={"sitemap"=false})
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function propertiesInmopcAction()
    {
        return $this->render('Front/properties_inmopc.html.twig');
    }

    /**
     * @Route("/property/previous/{id}", name="front_property_prev", options={"expose"=false})
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function prevPropertyForwardAction(Request $request, $id)
    {
        if (!$request->getSession()->has('pfilter')) {
            $request->getSession()->set('pfilter', array(-1, -1, -1, 0, 0, 0));
        }
        /** @var Property $previousProperty */
        $previousProperty = $this->getDoctrine()->getRepository('AppBundle:Property')->getEnabledPrevProperty($id, $request->getSession()->get('pfilter'));

        return $this->redirectToRoute('front_property', array(
               'type' => $previousProperty->getType()->getNameSlug(),
               'city' => $previousProperty->getCity()->getNameSlug(),
               'name' => $previousProperty->getNameSlug(),
               'reference' => $previousProperty->getReference(),
            ));
    }

    /**
     * @Route("/property/next/{id}", name="front_property_next", options={"expose"=false})
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function nextPropertyForwardAction(Request $request, $id)
    {
        if (!$request->getSession()->has('pfilter')) {
            $request->getSession()->set('pfilter', array(-1, -1, -1, 0, 0, 0));
        }
        /** @var Property $nextProperty */
        $nextProperty = $this->getDoctrine()->getRepository('AppBundle:Property')->getEnabledNextProperty($id, $request->getSession()->get('pfilter'));

        return $this->redirectToRoute('front_property', array(
                'type' => $nextProperty->getType()->getNameSlug(),
                'city' => $nextProperty->getCity()->getNameSlug(),
                'name' => $nextProperty->getNameSlug(),
                'reference' => $nextProperty->getReference(),
            ));
    }

    /**
     * @Route("/property/back-to-list", name="front_property_return", options={"expose"=false})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function backToListAction(Request $request)
    {
        if (!$request->getSession()->has('pfilter')) {
            $request->getSession()->set('pfilter', array(-1, -1, -1, 0, 0, 0));
        }
        $request->getSession()->set('isbacktolistredirect', true);

        return $this->redirectToRoute('front_properties');
    }

    /**
     * @Route("/{type}/{city}/{name}/{reference}/{homepage}", name="front_property", options={"expose"=true})
     * @ParamConverter("property", class="AppBundle:Property", options={"mapping": {"reference": "reference"}})
     *
     * @param Request  $request
     * @param Property $property
     *
     * @return Response
     *
     * @throws OptimisticLockException
     */
    public function propertyAction(Request $request, Property $property, $homepage = false)
    {
        if (!$property->getEnabled()) {
            throw new NotFoundHttpException();
        }

        $adminListPropertiesRoute = $this->generateUrl('admin_finquesfarnos_app_property_list');
        $frontRoute = $this->generateUrl('front_homepage', array(), true);
        if (0 == strpos($request->headers->get('referer'), $adminListPropertiesRoute)) {
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
                'showNavigationArrows' => $request->headers->get('referer') != $frontRoute,
                'property' => $property,
                'localization' => json_encode($localization),
                'form' => $form->createView(),
                'homepageReferrer' => $request->get('hr'),
            ));
    }

    /**
     * @Route("/property/pdf/{id}", name="front_property_pdf", options={"expose"=false})
     * @ParamConverter("property", class="AppBundle:Property", options={"mapping": {"id": "id"}})
     *
     * @param Property $property
     *
     * @return Response
     */
    public function propertyPdfAction($property)
    {
        if (!$property->getEnabled()) {
            throw new NotFoundHttpException();
        }

        /** @var PropertyWebPdfGenerator $generator */
        $generator = $this->get('app.property_web_pdf_generator');
        $pdf = $generator->generate(array('property' => $property));

        /** @var Slugify $slugger */
        $slugger = $this->get('sonata.core.slugify.cocur');

        return new Response($pdf->getNativeObject()->Output('Ref-'.$slugger->slugify($property->getReference()).'.pdf', 'I'), 200, array('Content-type' => 'application/pdf'));
    }

    /**
     * @Route("/about-us", name="front_about", options={"sitemap"=true})
     */
    public function aboutAction()
    {
        return $this->render('Front/about.html.twig');
    }

    /**
     * @Route("/contact", name="front_contact", options={"sitemap"=true})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws OptimisticLockException
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

            return $this->redirectToRoute('front_contact_thankyou');
        }

        return $this->render('Front/contact.html.twig', array(
                'form' => $form->createView(),
            ));
    }

    /**
     * @Route("/contact/thank-you", name="front_contact_thankyou", options={"sitemap"=true})
     */
    public function contactThankYouAction()
    {
        return $this->render('Front/contact_thank_you.html.twig');
    }

    /**
     * @Route("/privacy", name="front_privacy", options={"sitemap"=true})
     */
    public function privacyAction()
    {
        return $this->render('Front/privacy.html.twig');
    }

    /**
     * @Route("/legal", name="front_legal", options={"sitemap"=true})
     */
    public function legalAction()
    {
        return $this->render('Front/legal.html.twig');
    }

    /**
     * @Route("/credits", name="front_credits", options={"sitemap"=true})
     */
    public function creditsAction()
    {
        return $this->render('Front/credits.html.twig');
    }
}
