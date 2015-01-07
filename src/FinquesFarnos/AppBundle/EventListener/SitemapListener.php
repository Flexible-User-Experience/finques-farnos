<?php

namespace FinquesFarnos\AppBundle\EventListener;

use Doctrine\ORM\EntityManager;
use FinquesFarnos\AppBundle\Entity\Property;
use Symfony\Component\Routing\RouterInterface;
use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

/**
 * Class SitemapListener
 *
 * @category Listener
 * @package  FinquesFarnos\AppBundle\EventListener
 * @author   David Romaní <david@flux.cat>
 */
class SitemapListener implements SitemapListenerInterface
{
    /**
     * @var RouterInterface $router
     */
    private $router;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Constructor
     *
     * @param RouterInterface $router
     * @param EntityManager   $em
     */
    public function __construct(RouterInterface $router, EntityManager $em)
    {
        $this->router = $router;
        $this->em = $em;
    }

    /**
     * Use this method to complex routes (with parameters)
     *
     * @param SitemapPopulateEvent $event
     */
    public function populateSitemap(SitemapPopulateEvent $event)
    {
        $section = $event->getSection();
        if (is_null($section) || $section == 'default') {
            $properties = $this->em->getRepository('AppBundle:Property')->getEnabledPropertiesSortedByPriceQuery()->getResult();
            /** @var Property $property */
            foreach ($properties as $property) {
                $url = $this->router->generate('front_property', array(
                        'type' => $property->getType()->getNameSlug(),
                        'city' => $property->getCity()->getNameSlug(),
                        'name' => $property->getNameSlug(),
                        'reference' => $property->getReference(),
                    ), true);
                $event->getGenerator()->addUrl(
                    new UrlConcrete(
                        $url,
                        new \DateTime(),
                        UrlConcrete::CHANGEFREQ_DAILY,
                        1
                    ),
                    'default'
                );
            }
        }
    }
}
