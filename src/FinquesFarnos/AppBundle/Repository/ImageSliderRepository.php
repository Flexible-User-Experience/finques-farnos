<?php

namespace FinquesFarnos\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ImageSliderRepository class.
 *
 * @category Repository
 *
 * @author   David Romaní <david@flux.cat>
 */
class ImageSliderRepository extends EntityRepository
{
    /**
     * Get homepage items.
     *
     * @return array
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
