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
     * Get enabled items array result
     *
     * @return array
     */
    public function getEnabledArrayResultItems()
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
     * Get enabled items
     *
     * @return array
     */
    public function getEnabledItems()
    {
        return $this->createQueryBuilder('t')
            ->where('t.enabled = :enabled')
            ->setParameter('enabled', true)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get enabled items sorted by name
     *
     * @return array
     */
    public function getEnabledItemsSortedByName()
    {
        return $this->createQueryBuilder('t')
            ->where('t.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
