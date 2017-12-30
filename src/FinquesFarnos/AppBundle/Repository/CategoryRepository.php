<?php

namespace FinquesFarnos\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * CategoryRepository class.
 *
 * @category Repository
 *
 * @author   David Romaní <david@flux.cat>
 */
class CategoryRepository extends EntityRepository
{
    /**
     * Get enabled items sorted by name.
     *
     * @return QueryBuilder
     */
    public function getEnabledItemsSortedByNameQB()
    {
        return $this->createQueryBuilder('c')
            ->where('c.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('c.name', 'ASC');
    }

    /**
     * Get enabled items sorted by name.
     *
     * @return Query
     */
    public function getEnabledItemsSortedByNameQ()
    {
        return $this->getEnabledItemsSortedByNameQB()->getQuery();
    }

    /**
     * Get enabled items sorted by name.
     *
     * @return array
     */
    public function getEnabledItemsSortedByName()
    {
        return $this->getEnabledItemsSortedByNameQ()->getResult();
    }

    /**
     * Get enabled items sorted by name limited.
     *
     * @param int $limit
     *
     * @return QueryBuilder
     */
    public function getEnabledItemsSortedByNameLimitedQB($limit = 20)
    {
        return $this->getEnabledItemsSortedByNameQB()->setMaxResults($limit);
    }

    /**
     * Get enabled items sorted by name.
     *
     * @param int $limit
     *
     * @return Query
     */
    public function getEnabledItemsSortedByNameLimitedQ($limit = 20)
    {
        return $this->getEnabledItemsSortedByNameLimitedQB($limit)->getQuery();
    }

    /**
     * Get enabled items sorted by name.
     *
     * @param int $limit
     *
     * @return array
     */
    public function getEnabledItemsSortedByNameLimit($limit = 20)
    {
        return $this->getEnabledItemsSortedByNameLimitedQ($limit)->getResult();
    }
}
