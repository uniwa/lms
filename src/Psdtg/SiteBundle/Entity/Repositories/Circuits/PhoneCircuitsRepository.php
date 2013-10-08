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
        if(isset($filters['isService']) && $filters['isService'] == true) {
            $qb->join('c.connectivityType', 'isct');
            $qb->andWhere('isct.isService = 1');
        } else if(isset($filters['isService']) && $filters['isService'] == false) {
            $qb->join('c.connectivityType', 'isct');
            $qb->andWhere('isct.isService = 0');
        }
    }
}