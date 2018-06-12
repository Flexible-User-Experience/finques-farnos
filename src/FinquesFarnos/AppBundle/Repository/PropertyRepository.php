<?php

namespace FinquesFarnos\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Orx;
use FinquesFarnos\AppBundle\Entity\Property;

/**
 * PropertyRepository class.
 *
 * @category Repository
 *
 * @author   David Romaní <david@flux.cat>
 */
class PropertyRepository extends EntityRepository
{
    /**
     * Get homepage items.
     *
     * @return array
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
     * Get enabled properties sorted by price query.
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
     * Get top 10 visited array.
     *
     * @return array
     */
    public function getTop10VisitedArray()
    {
        return $this->getMostVisitedQuery()->setMaxResults(10)->getArrayResult();
    }

    /**
     * Get most visited properties query.
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
     * Get filters.
     *
     * @return array
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getFilters()
    {
        $qb = $this->createQueryBuilder('p')
            ->select('MIN(p.squareMeters) AS min_area, MAX(p.squareMeters) AS max_area, MIN(p.rooms) AS min_rooms, MAX(p.rooms) AS max_rooms, MIN(p.price) AS min_price, MAX(p.price) AS max_price')
            ->where('p.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('p.price', 'ASC')
        ;

        return $qb->getQuery()->getSingleResult(Query::HYDRATE_ARRAY);
    }

    /**
     * Filter properties by.
     *
     * @param array $categories
     * @param int   $type
     * @param int   $city
     * @param int   $area
     * @param int   $rooms
     * @param int   $price
     *
     * @return array
     */
    public function filterBy($categories, $type, $city, $area, $rooms, $price)
    {
        return $this->filterByQuery($categories, $type, $city, $area, $rooms, $price)->getResult();
    }

    /**
     * Filter properties by.
     *
     * @param array $categories
     * @param int   $type
     * @param int   $city
     * @param int   $area
     * @param int   $rooms
     * @param int   $price
     * @param int   $limit
     *
     * @return array
     */
    public function filterByLimited($categories, $type, $city, $area, $rooms, $price, $limit = 20)
    {
        return $this->filterByQuery($categories, $type, $city, $area, $rooms, $price)->setMaxResults($limit)->getResult();
    }

    /**
     * Get filtered properties query.
     *
     * @param array $categories
     * @param int   $type
     * @param int   $city
     * @param int   $area
     * @param int   $rooms
     * @param int   $price
     *
     * @return Query
     */
    public function filterByQuery($categories, $type, $city, $area, $rooms, $price)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('p')
            ->select('p, i, t, c')
            ->leftJoin('p.images', 'i')
            ->leftJoin('p.type', 't')
            ->leftJoin('p.categories', 'c')
            ->where('p.enabled = :enabled')
            ->andWhere('p.squareMeters <= :area')
            ->andWhere('i.enabled = :enabled')
            ->setParameters(
                array(
                    'enabled' => true,
                    'area' => $area,
                )
            )
            ->addOrderBy('p.price', 'ASC')
            ->addOrderBy('p.name', 'ASC')
            ->addOrderBy('p.totalVisits', 'DESC')
        ;
        if ($type > 0) {
            $qb->andWhere('p.type = :type')->setParameter('type', $type);
        }
        if ($city > 0) {
            $qb->andWhere('p.city = :city')->setParameter('city', $city);
        }
        if ($categories) {
            /** @var Orx $orWhereExpr */
            $orWhereExpr = $qb->expr()->orX();
            foreach ($categories as $categoryId) {
                $orWhereExpr->add($qb->expr()->orX($qb->expr()->eq('c.id', $categoryId)));
            }
            $qb->andWhere($orWhereExpr);
        }
        if ($rooms > 0) {
            $qb->andWhere('p.rooms <= :rooms')->setParameter('rooms', $rooms);
        }
        if ($price > 0) {
            $qb->andWhere('p.price <= :price')->setParameter('price', $price);
        }

        return $qb->getQuery();
    }

    /**
     * Get first enabled property.
     *
     * @return Property|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
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
     * Get enabled previous property.
     *
     * @param int   $id
     * @param mixed $filter
     *
     * @return Property|null
     */
    public function getEnabledPrevProperty($id, $filter)
    {
        $filteredProperties = $this->handleFilter($filter);
        $index = 0;
        /** @var Property $filteredProperty */
        foreach ($filteredProperties as $filteredProperty) {
            if ($filteredProperty->getId() == $id) {
                break;
            }
            ++$index;
        }
        if (0 == $index) {
            return $filteredProperties[count($filteredProperties) - 1];
        }

        return $filteredProperties[$index - 1];
    }

    /**
     * Get enabled next property.
     *
     * @param int   $id
     * @param mixed $filter
     *
     * @return Property|null
     */
    public function getEnabledNextProperty($id, $filter)
    {
        $filteredProperties = $this->handleFilter($filter);
        $index = 0;
        /** @var Property $filteredProperty */
        foreach ($filteredProperties as $filteredProperty) {
            if ($filteredProperty->getId() == $id) {
                break;
            }
            ++$index;
        }
        if ($index == count($filteredProperties) - 1) {
            return $filteredProperties[0];
        }

        return $filteredProperties[$index + 1];
    }

    /**
     * Get last enabled property.
     *
     * @return Property|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
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

    /**
     * @param mixed $filter
     *
     * @return array
     */
    private function handleFilter($filter)
    {
        $catArray = array();
        if ('-1' != $filter[0] && 'any' != $filter[0]) {
            if (is_array($filter[0])) {
                $catArray = $filter[0];
            } else {
                $catArray = explode('-', $filter[0]);
            }
        }

        return $this->filterBy($catArray, $filter[1], $filter[2], $filter[3], $filter[4], $filter[5]);
    }
}
