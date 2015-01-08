<?php

namespace FinquesFarnos\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ImagePropertyRepository class
 *
 * @category Repository
 * @package  FinquesFarnos\AppBundle\Repository
 * @author   David Romaní <david@flux.cat>
 */
class ImagePropertyRepository extends EntityRepository
{
    /**
     * Get enabled items sorted by position array result
     *
     * @param int $id propertyId
     *
     * @return array
     */
    public function getFirstEnabledImageOfPropertyId($id)
    {
        return $this->createQueryBuilder('i')
            ->where('i.enabled = :enabled')
            ->andWhere('i.property = :id')
            ->setParameter('enabled', true)
            ->setParameter('id', $id)
            ->orderBy('i.position', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
