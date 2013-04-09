<?php

namespace Psdtg\SiteBundle\Extension;

use Psdtg\SiteBundle\Entity\Unit;

class MMService {
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function find($mmid) {
        $em = $this->container->get('doctrine')->getEntityManager();
        $repo = $em->getRepository('Psdtg\SiteBundle\Entity\Unit');
        $unit = $repo->find($mmid);
        $yesterday = new \DateTime('yesterday');
        if(!isset($unit) || $unit->getUpdatedAt() < $yesterday) {
            // Unit not found or its too old. Query the WS for fresh data.
            $unit = new Unit;
            $unit->setMmId($mmid);
            $unit->setName('Test'.$mmid);
            $unit->setPostalCode('12345');
            $unit->setRegistryNo('321');
            $unit->setStreetAddress('Zakynthou 13');
            $em->persist($unit);
            $em->flush();
        }
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
        $unit = $this->find($unit->getMmId());
        return $unit;
    }
}
?>
