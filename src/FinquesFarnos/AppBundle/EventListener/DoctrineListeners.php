<?php

namespace FinquesFarnos\AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use FinquesFarnos\AppBundle\Entity\ImageProperty;
use FinquesFarnos\AppBundle\Entity\Property;
use FinquesFarnos\AppBundle\Entity\PropertyVisit;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Class EntitiesListeners
 *
 * @category Listener
 * @package  FinquesFarnos\AppBundle\EventListener
 * @author   David Romaní <david@flux.cat>
 */
class DoctrineListeners
{
    /**
     * @var CacheManager $cm
     */
    private $cm;

    /**
     * @var UploaderHelper $uh
     */
    private $uh;

    /**
     * Constructor
     *
     * @param CacheManager   $cm
     * @param UploaderHelper $uh
     */
    public function __construct(CacheManager $cm, UploaderHelper $uh)
    {
        $this->cm = $cm;
        $this->uh = $uh;
    }

    /**
     * Pre persist event listener.
     * - set property visit counter
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var PropertyVisit $entity */
        $entity = $args->getEntity();

        if ($entity instanceof PropertyVisit) {
            $entity->getProperty()->setTotalVisits($entity->getProperty()->getTotalVisits() + 1);
        }
    }

    /**
     * Post load event listener.
     * - set virtual first enabled image URL on property entity
     *
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        /** @var Property $entity */
        $entity = $args->getEntity();

        if ($entity instanceof Property) {
            /** @var ImageProperty $image */
//            $image = $entity->getFirstEnabledImage();
//            if ($image) {
//                $entity->setVirtualFirstEnabledImageUrl($this->cm->generateUrl($this->uh->asset($image, 'imageFile'), '373x192'));
//            }
        } else if ($entity instanceof ImageProperty) {
            /** @var ImageProperty $entity */
//            $entity->setMetaAlt($entity->getMetaAlt() ? $entity->getMetaAlt() : $entity->getProperty()->getName());
        }
    }
}
