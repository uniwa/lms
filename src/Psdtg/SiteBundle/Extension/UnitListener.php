<?php
namespace Psdtg\SiteBundle\Extension;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Psdtg\SiteBundle\Entity\Unit;

class UnitListener {
    protected $mmservice;

    public function __construct(MMService $mmservice) {
        $this->mmservice = $mmservice;
    }

    public function postLoad(LifecycleEventArgs $lcea) {
        $unit = $lcea->getEntity();
        if(!($unit instanceof Unit)) {
            return;
        }

        // Refresh the unit if its older than 1 day
        /*$yesterday = new \DateTime('yesterday');
        if($unit->getUpdatedAt() <= $yesterday) {
            $this->mmservice->refresh($unit);
        }*/
    }
}