<?php

namespace FinquesFarnos\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * TypeRepository class.
 *
 * @category Repository
 *
 * @author   David Romaní <david@flux.cat>
 */
class TypeRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function getEnabledItemsQB()
    {
        return $this->createQueryBuilder('t')
            ->where('t.enabled = :enabled')
            ->setParameter('enabled', true);
    }

    /**
     * @return Query
     */
    public function getEnabledItemsQ()
    {
        return $this->getEnabledItemsQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getEnabledItems()
    {
        return $this->getEnabledItemsQ()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getEnabledItemsSortedByNameQB()
    {
        return $this->getEnabledItemsQB()->orderBy('t.name', 'ASC');
    }

    /**
     * @return Query
     */
    public function getEnabledItemsSortedByNameQ()
    {
        return $this->getEnabledItemsSortedByNameQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getEnabledItemsSortedByName()
    {
        return $this->getEnabledItemsSortedByNameQ()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getEnabledItemsSortedByNameOnlyWithEnabledPropertiesQB()
    {
        return $this->getEnabledItemsSortedByNameQB()
            ->leftJoin('t.properties', 'p')
            ->andWhere('p.enabled = :enabled')
            ->setParameter('enabled', true);
    }

    /**
     * @return Query
     */
    public function getEnabledItemsSortedByNameOnlyWithEnabledPropertiesQ()
    {
        return $this->getEnabledItemsSortedByNameOnlyWithEnabledPropertiesQB()->getQuery();
    }

    /**
     * @return array
     */
    public function getEnabledItemsSortedByNameOnlyWithEnabledProperties()
    {
        return $this->getEnabledItemsSortedByNameOnlyWithEnabledPropertiesQ()->getResult();
    }

    /**
     * Get enabled items array result.
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
}
