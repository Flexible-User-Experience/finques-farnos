<?php

namespace FinquesFarnos\AppBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use FinquesFarnos\AppBundle\Entity\Property;

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
            ->select('p, i, t, c')
            ->where('p.enabled = :enabled')
            ->andWhere('p.showInHomepage = :enabled')
            ->andWhere('i.enabled = :enabled')
            ->leftJoin('p.images', 'i')
            ->leftJoin('p.type', 't')
            ->leftJoin('p.categories', 'c')
            ->setParameter('enabled', true)
            ->orderBy('p.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get enabled properties sorted by price query
     *
     * @return Query
     */
    public function getEnabledPropertiesSortedByPriceQuery()
    {
        return $this->createQueryBuilder('p')
            ->where('p.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('p.price', 'ASC')
            ->getQuery();
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

    /**
     * Get filters
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->createQueryBuilder('p')
            ->select('p, MIN(p.squareMeters) AS min_area, MAX(p.squareMeters) AS max_area, MIN(p.rooms) AS min_rooms, MAX(p.rooms) AS max_rooms, MIN(p.price) AS min_price, MAX(p.price) AS max_price')
            ->where('p.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('p.price', 'ASC')
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);
    }

    /**
     * Filter properties by
     *
     * @param int $type
     * @param int $city
     * @param int $area
     * @param int $rooms
     * @param int $price
     *
     * @return ArrayCollection
     */
    public function filterBy($type, $city, $area, $rooms, $price)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('p')
            ->select('p, i, t, c')
            ->where('p.enabled = :enabled')
            ->andWhere('p.squareMeters >= :area')
            ->andWhere('p.rooms >= :rooms')
            ->andWhere('p.price >= :price')
            ->andWhere('i.enabled = :enabled')
            ->leftJoin('p.images', 'i')
            ->leftJoin('p.type', 't')
            ->leftJoin('p.categories', 'c')
            ->setParameters(array(
                    'enabled' => true,
                    'area' => $area,
                    'rooms' => $rooms,
                    'price' => $price,
                ))
            ->addOrderBy('p.price', 'ASC')
            ->addOrderBy('p.name', 'ASC')
            ->addOrderBy('p.totalVisits', 'DESC');
        if ($type > 0) {
            $qb->andWhere('p.type = :type')->setParameter('type', $type);
        }
        if ($city > 0) {
            $qb->andWhere('p.city = :city')->setParameter('city', $city);
        }
        $qb->setMaxResults(2); // TODO remove this

        return $qb->getQuery()->getResult();
    }

    /**
     * Get first enabled property
     *
     * @return Property|null
     */
    public function getFirstEnabledProperty()
    {
        return $this->createQueryBuilder('p')
            ->where('p.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get enabled previous property
     *
     * @param $id
     *
     * @return Property|null
     */
    public function getEnabledPrevProperty($id)
    {
        return $this->createQueryBuilder('p')
            ->where('p.enabled = :enabled')
            ->andWhere('p.id < :id')
            ->setParameter('enabled', true)
            ->setParameter('id', $id)
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get enabled next property
     *
     * @param $id
     *
     * @return Property|null
     */
    public function getEnabledNextProperty($id)
    {
        return $this->createQueryBuilder('p')
            ->where('p.enabled = :enabled')
            ->andWhere('p.id > :id')
            ->setParameter('enabled', true)
            ->setParameter('id', $id)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get last enabled property
     *
     * @return Property|null
     */
    public function getLastEnabledProperty()
    {
        return $this->createQueryBuilder('p')
            ->where('p.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
