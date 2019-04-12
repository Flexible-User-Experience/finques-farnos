<?php

namespace FinquesFarnos\AppBundle\Service;

use Doctrine\ORM\EntityManager;
use FinquesFarnos\AppBundle\Entity\Property;
use Swift_Mailer;
use FinquesFarnos\AppBundle\Entity\Contact;
use FinquesFarnos\AppBundle\Entity\ContactMessage;
use Symfony\Bundle\TwigBundle\TwigEngine;

/**
 * Class MailerService.
 *
 * @category Service
 *
 * @author   David Romaní <david@flux.cat>
 */
class MailerService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var TwigEngine
     */
    private $templating;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var string email_notifications_sender_domain
     */
    private $ensd;

    /**
     * Constructor.
     *
     * @param EntityManager $em
     * @param TwigEngine    $templating
     * @param Swift_Mailer  $mailer
     * @param string        $ensd
     */
    public function __construct(EntityManager $em, TwigEngine $templating, Swift_Mailer $mailer, $ensd)
    {
        $this->em = $em;
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->ensd = $ensd;
    }

    /**
     * Perform frontend property detail page delivery email notification action.
     *
     * @param Contact  $contactForm
     * @param          $textMessage
     * @param Property $property
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function performPropertyDeliveryAction(Contact $contactForm, $textMessage, Property $property)
    {
        $this->manageModel($contactForm, $textMessage, $property);
        $this->delivery($contactForm, $textMessage, $property);
    }

    /**
     * Perform frontend contact page delivery email notification action.
     *
     * @param Contact $contactForm
     * @param         $textMessage
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function performContactDeliveryAction(Contact $contactForm, $textMessage)
    {
        $this->manageModel($contactForm, $textMessage);
        $this->delivery($contactForm, $textMessage);
    }

    /**
     * Manage model relations. Determine if there is user reference or not & persist new question with asker.
     * Apply property reference if it is necessary.
     *
     * @param Contact       $contactForm
     * @param string        $textMessage
     * @param Property|null $property
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function manageModel(Contact $contactForm, $textMessage, Property $property = null)
    {
        /** @var Contact $contactToBePersisted */
        $contactToBePersisted = $this->em->getRepository('AppBundle:Contact')->findOneBy(array('email' => $contactForm->getEmail()));
        if ($contactToBePersisted) {
            $contactToBePersisted
                ->setName($contactForm->getName())
                ->setPhone($contactForm->getPhone())
                ->setEnabled(true);
        } else {
            $contactToBePersisted = $contactForm;
        }
        /** @var ContactMessage $message */
        $message = new ContactMessage();
        $message->setContact($contactToBePersisted)->setText($textMessage);
        if ($property) {
            $message->setProperty($property);
        }
        $contactToBePersisted->addMessage($message);
        $this->em->persist($contactToBePersisted);
        $this->em->persist($message);
        $this->em->flush();
    }

    /**
     * Deliver email notifitacion task.
     *
     * @param Contact       $contactForm
     * @param string        $textMessage
     * @param Property|null $property
     *
     * @throws \Twig\Error\Error
     */
    private function delivery(Contact $contactForm, $textMessage, Property $property = null)
    {
        /** @var \Swift_Message $emailMessage */
        $emailMessage = \Swift_Message::newInstance()
            ->setSubject('Formulari de contacte pàgina web www.'.$this->ensd)
            ->setFrom('webapp@'.$this->ensd)
            ->setTo('info@'.$this->ensd)
            ->setBody(
                $this->templating->render(
                    'Front/contact.email.html.twig',
                    array(
                        'form' => $contactForm,
                        'message' => $textMessage,
                        'property' => $property,
                        'ensd' => $this->ensd,
                    )
                )
            )
            ->setCharset('UTF-8')
            ->setContentType('text/html')
        ;

        $this->mailer->send($emailMessage);
    }
}
