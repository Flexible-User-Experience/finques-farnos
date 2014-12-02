<?php

namespace FinquesFarnos\AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use FinquesFarnos\AppBundle\Entity\ContactMessage;
use FinquesFarnos\AppBundle\Entity\Property;
use FinquesFarnos\AppBundle\Entity\PropertyVisit;
use FinquesFarnos\AppBundle\Form\Type\ContactType;
use FinquesFarnos\AppBundle\Entity\Contact;
use Knp\Component\Pager\Paginator;
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
        return $this->render('::Front/homepage.html.twig', array(
                'slides' => $this->getDoctrine()->getRepository('AppBundle:ImageSlider')->getHomepageItems(),
                'properties' => $this->getDoctrine()->getRepository('AppBundle:Property')->getHomepageItems(),
            ));
    }

    /**
     * @Route("/properties/", name="front_properties", options={"sitemap" = true})
     */
    public function propertiesAction()
    {
        return $this->render('::Front/properties.html.twig');
    }

    /**
     * @Route("/property/{type}/{name}/", name="front_property")
     * @ParamConverter("property", class="AppBundle:Property", options={"mapping": {"name": "nameSlug"}})
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

        }

        return $this->render('::Front/property.html.twig', array(
                'property' => $property,
                'form' => $form->createView(),
            ));
    }

    /**
     * @Route("/about-us/", name="front_about", options={"sitemap" = true})
     */
    public function aboutAction()
    {
        return $this->render('::Front/about.html.twig');
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
            /** @var Contact $contactForm */
            $contactForm = $form->getData();
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            /** @var Contact $contactToBePersisted */
            $contactToBePersisted = $em->getRepository('AppBundle:Contact')->findOneBy(array('email' => $contactForm->getEmail()));
            if ($contactToBePersisted) {
                $contactToBePersisted
                    ->setName($contactForm->getName())
                    ->setPhone($contactForm->getPhone())
                    ->setEnabled(true);
            } else {
                $contactToBePersisted = $contactForm;
            }
            $fc = $request->get('contact');
            /** @var ContactMessage $message */
            $message = new ContactMessage();
            $message->setContact($contactToBePersisted)->setText($fc['message']);
            $contactToBePersisted->addMessage($message);
            $em->persist($contactToBePersisted);
            $em->persist($message);
            $em->flush();
            // Send email
            /** @var \Swift_Message $emailMessage */
            $emailMessage = \Swift_Message::newInstance()
                ->setSubject('Formulari de contacte pàgina web www.finquesfarnos.com')
                ->setFrom('webapp@finquesfarnos.com')
                ->setTo('info@fiquesfarnos.com')
                ->setBody(
                    $this->renderView(
                        '::Front/contact.email.html.twig',
                        array(
                            'form' => $contactForm,
                            'message' => $fc['message']
                        )
                    )
                )
                ->setCharset('UTF-8')
                ->setContentType('text/html')
            ;
            $this->get('mailer')->send($emailMessage);

            return $this->redirect($this->generateUrl('front_contact_thankyou'));
        }

        return $this->render('::Front/contact.html.twig', array(
                'form' => $form->createView(),
            ));
    }

    /**
     * @Route("/contact/thank-you/", name="front_contact_thankyou", options={"sitemap" = true})
     */
    public function contactThankYouAction()
    {
        return $this->render('::Front/contact_thank_you.html.twig');
    }

    /**
     * @Route("/privacy/", name="front_privacy", options={"sitemap" = true})
     */
    public function privacyAction()
    {
        return $this->render('::Front/privacy.html.twig');
    }

    /**
     * @Route("/legal/", name="front_legal", options={"sitemap" = true})
     */
    public function legalAction()
    {
        return $this->render('::Front/legal.html.twig');
    }

    /**
     * @Route("/credits/", name="front_credits", options={"sitemap" = true})
     */
    public function creditsAction()
    {
        return $this->render('::Front/credits.html.twig');
    }
}
