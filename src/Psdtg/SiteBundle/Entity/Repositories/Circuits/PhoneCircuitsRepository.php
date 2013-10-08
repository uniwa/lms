<?php

namespace Psdtg\SiteBundle\Entity\Repositories\Circuits;

use Doctrine\ORM\QueryBuilder;

class PhoneCircuitsRepository extends CircuitsRepository
{
    protected function addFilters(QueryBuilder &$qb, array $filters) {
        if(isset($filters['name']) && $filters['name'] != '') {
            $qb->join('c.unit', 'nu');
            $qb->andWhere('c.number LIKE :name OR nu.name LIKE :name');
            $qb->setParameter('name', '%'.$filters['name'].'%');
        }
    }
}