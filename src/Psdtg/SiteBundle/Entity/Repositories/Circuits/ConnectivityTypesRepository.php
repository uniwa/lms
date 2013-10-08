<?php

namespace Psdtg\SiteBundle\Entity\Repositories\Circuits;

use Psdtg\SiteBundle\Entity\Repositories\BaseRepository;

class ConnectivityTypesRepository extends BaseRepository
{
    public function getConnectivityTypesQb($filters = array()) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ct');
        $qb->from($this->_entityName, 'ct');
        if(isset($filters['noLease']) && $filters['noLease'] == true) {
            $qb->andWhere('ct.noLease = 1');
        } else if(isset($filters['noLease']) && $filters['noLease'] == false) {
            $qb->andWhere('ct.noLease = 0');
        }
        if(isset($filters['isService']) && $filters['isService'] == true) {
            $qb->andWhere('ct.isService = 1');
        } else if(isset($filters['isService']) && $filters['isService'] == false) {
            $qb->andWhere('ct.isService = 0');
        }
        return $qb;
    }
}