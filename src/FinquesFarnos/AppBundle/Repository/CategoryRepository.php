<?php

namespace FinquesFarnos\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository class
 *
 * @category Repository
 * @package  FinquesFarnos\AppBundle\Repository
 * @author   David Romaní <david@flux.cat>
 */
class CategoryRepository extends EntityRepository
{
    /**
     * Get enabled items sorted by name
     *
     * @return array
     */
    public function getEnabledItemsSortedByName()
    {
        return $this->createQueryBuilder('c')
            ->where('c.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
