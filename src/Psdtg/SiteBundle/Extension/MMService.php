<?php

namespace Psdtg\SiteBundle\Extension;

use Psdtg\SiteBundle\Entity\Unit;

class MMService {
    public function find($mmid) {
        $unit = new Unit;
        $unit->setMmId($mmid);
        $unit->setName('Test'.$mmid);
        $unit->setPostalCode('12345');
        $unit->setRegistryNo('321');
        $unit->setStreetAddress('Zakynthou 13');
        return $unit;
    }

    public function findBy(array $filters = array()) {
        $results = array();
        if(isset($filters['name']) && $filters['name'] != '') {
            $results[] = $this->find(1);
            $results[] = $this->find(2);
        }
        return $results;
    }

    public function refresh(Unit $unit) {
        $unit->setName('refreshed unit');
        return $unit;
    }
}
?>