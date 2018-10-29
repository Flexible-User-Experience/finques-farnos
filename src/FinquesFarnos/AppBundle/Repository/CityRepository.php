<?php

namespace FinquesFarnos\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;

/**
 * CityRepository class.
 *
 * @category Repository
 *
 * @author   David Romaní <david@flux.cat>
 */
class CityRepository extends EntityRepository
{
    /**
     * Get enabled items sorted by name query builder.
     *
     * @return QueryBuilder
     */
    public function getEnabledItemsSortedByNameQB()
    {
        return $this->createQueryBuilder('c')
            ->select('c.id, c.name')
            ->leftJoin('c.properties', 'p')
            ->where('c.enabled = :enabled')
            ->andWhere('p.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('c.name', 'ASC')
            ->groupBy('c.name')
        ;
    }

    /**
     * Get enabled items sorted by name query builder.
     *
     * @return Query
     */
    public function getEnabledItemsSortedByNameQ()
    {
        return $this->getEnabledItemsSortedByNameQB()->getQuery();
    }

    /**
     * Get enabled items sorted by name array result.
     *
     * @return array
     */
    public function getEnabledItemsSortedByNameArrayResult()
    {
        return $this->getEnabledItemsSortedByNameQ()->getArrayResult();
    }

    /**
     * Get enabled items filtered by typeId and sorted by name.
     *
     * @param int $typeId
     *
     * @return array
     */
    public function getEnabledItemsFilteredByTypeIdSortedByNameArrayResult($typeId)
    {
        if ($typeId > 0) {
            // only real update when there is a specific typeID
            $query = $this->createQueryBuilder('c')
                ->select('c')
                ->innerJoin('AppBundle:Property', 'ps', Join::WITH, '1 = 1')
                ->innerJoin('c.properties', 'p')
                ->innerJoin('p.type', 't')
                ->andWhere('t.id = :tid')
                ->andWhere('p.enabled = 1')
                ->setParameter('tid', $typeId)
                ->orderBy('c.name', 'ASC')
            ;
        } else {
            $query = $this->createQueryBuilder('c')
                ->select('c.id, c.name')
                ->leftJoin('c.properties', 'p')
                ->where('c.enabled = :enabled')
                ->andWhere('p.enabled = :enabled')
                ->setParameter('enabled', true)
                ->orderBy('c.name', 'ASC')
                ->groupBy('c.name')
            ;
        }

        return $query->getQuery()->getResult();
    }
}
