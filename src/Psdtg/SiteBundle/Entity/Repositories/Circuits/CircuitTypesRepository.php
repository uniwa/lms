<?php

namespace Psdtg\SiteBundle\Entity\Repositories\Circuits;

use Psdtg\SiteBundle\Entity\Repositories\BaseRepository;

class CircuitTypesRepository extends BaseRepository
{
    public function getCircuitTypesQb($filters = array()) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ct');
        $qb->from($this->_entityName, 'ct');
        if(isset($filters['noLease']) && $filters['noLease'] == true) {
            $qb->andWhere('ct.noLease = 1');
        } else if(isset($filters['noLease']) && $filters['noLease'] == false) {
            $qb->andWhere('ct.noLease = 0');
        }
        return $qb;
    }
}