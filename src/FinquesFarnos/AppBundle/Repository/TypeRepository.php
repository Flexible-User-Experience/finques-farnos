<?php

namespace FinquesFarnos\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TypeRepository class
 *
 * @category Repository
 * @package  FinquesFarnos\AppBundle\Repository
 * @author   David Romaní <david@flux.cat>
 */
class TypeRepository extends EntityRepository
{
    /**
     * Get filters
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->createQueryBuilder('t')
            ->select('t.id, t.name')
            ->where('t.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
