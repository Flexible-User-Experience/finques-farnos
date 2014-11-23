<?php

namespace FinquesFarnos\AppBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

/**
 * ImageSliderRepository class
 *
 * @category Repository
 * @package  FinquesFarnos\AppBundle\Repository
 * @author   David Romaní <david@flux.cat>
 */
class ImageSliderRepository extends EntityRepository
{
    /**
     * Get homepage items
     *
     * @return ArrayCollection
     */
    public function getHomepageItems()
    {
        return $this->createQueryBuilder('s')
            ->where('s.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('s.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
