<?php

namespace FinquesFarnos\AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use FinquesFarnos\AppBundle\Entity\Property;
use FinquesFarnos\AppBundle\Entity\PropertyVisit;

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
}
