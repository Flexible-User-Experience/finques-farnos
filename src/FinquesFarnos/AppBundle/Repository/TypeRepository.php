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
     * Get enabled array result filters
     *
     * @return array
     */
    public function getEnabledArrayResultFilters()
    {
        return $this->createQueryBuilder('t')
            ->select('t.id, t.name')
            ->where('t.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Get enabled filters
     *
     * @return array
     */
    public function getEnabledFilters()
    {
        return $this->createQueryBuilder('t')
            ->where('t.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
