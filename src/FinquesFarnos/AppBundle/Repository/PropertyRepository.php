<?php

namespace FinquesFarnos\AppBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * PropertyRepository class
 *
 * @category Repository
 * @package  FinquesFarnos\AppBundle\Repository
 * @author   David Romaní <david@flux.cat>
 */
class PropertyRepository extends EntityRepository
{
    /**
     * Get homepage items
     *
     * @return ArrayCollection
     */
    public function getHomepageItems()
    {
        return $this->createQueryBuilder('p')
            ->where('p.enabled = :enabled')
            ->andWhere('p.showInHomepage = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('p.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get top 10 visited array
     *
     * @return array
     */
    public function getTop10VisitedArray()
    {
        return $this->getMostVisitedQuery()->setMaxResults(10)->getArrayResult();
    }

    /**
     * Get most visited properties query
     *
     * @return Query
     */
    private function getMostVisitedQuery()
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('p')
            ->where('p.enabled = :enabled')
            ->setParameter('enabled', true)
            ->addOrderBy('p.totalVisits', 'DESC')
            ->addOrderBy('p.name', 'ASC');

        return $qb->getQuery();
    }
}
