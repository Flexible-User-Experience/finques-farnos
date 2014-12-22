<?php

namespace FinquesFarnos\AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Swift_Mailer;
use FinquesFarnos\AppBundle\Entity\Contact;
use FinquesFarnos\AppBundle\Entity\ContactMessage;
use Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine;

/**
 * Class MailerService
 *
 * @category Service
 * @package  FinquesFarnos\AppBundle\Service
 * @author   David Romaní <david@flux.cat>
 */
class MailerService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var TimedTwigEngine
     */
    private $templating;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * Constructor
     *
     * @param EntityManager   $em
     * @param TimedTwigEngine $templating
     * @param Swift_Mailer    $mailer
     */
    public function __construct(EntityManager $em, TimedTwigEngine $templating, Swift_Mailer $mailer)
    {
        $this->em = $em;
        $this->templating = $templating;
        $this->mailer = $mailer;
    }

    /**
     * Perform contact delivery email notification action
     *
     * @param Contact $contactForm
     * @param         $textMessage
     */
    public function performContactActions(Contact $contactForm, $textMessage)
    {
        $this->manageModel($contactForm, $textMessage);
        $this->delivery($contactForm, $textMessage);
    }

    /**
     * Manage model relations. Determine if there is user reference or not & persist new question with asker
     *
     * @param Contact $contactForm
     * @param string  $textMessage
     */
    private function manageModel(Contact $contactForm, $textMessage)
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
        $contactToBePersisted->addMessage($message);
        $this->em->persist($contactToBePersisted);
        $this->em->persist($message);
        $this->em->flush();
    }

    /**
     * Deliver email notifitacion task
     *
     * @param Contact $contactForm
     * @param         $textMessage
     */
    private function delivery(Contact $contactForm, $textMessage)
    {
        /** @var \Swift_Message $emailMessage */
        $emailMessage = \Swift_Message::newInstance()
            ->setSubject('Formulari de contacte pàgina web www.finquesfarnos.com')
            ->setFrom('webapp@finquesfarnos.com')
            ->setTo('info@fiquesfarnos.com')
            ->setBody(
                $this->templating->render(
                    'Front/contact.email.html.twig',
                    array(
                        'form' => $contactForm,
                        'message' => $textMessage,
                    )
                )
            )
            ->setCharset('UTF-8')
            ->setContentType('text/html')
        ;

        $this->mailer->send($emailMessage);
    }
}
