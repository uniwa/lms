<?php
namespace Psdtg\SiteBundle\Extension;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest;

class NewCircuitRequestListener {
    public function prePersist(LifecycleEventArgs $lcea) {
        $newCircuitRequest = $lcea->getEntity();
        if(!$newCircuitRequest instanceof NewCircuitRequest) {
            return;
        }

        $em = $lcea->getEntityManager();
        
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs) {
        $newCircuitRequest = $eventArgs->getEntity();
        if(!$newCircuitRequest instanceof NewCircuitRequest) {
            return;
        }
        if ($eventArgs->hasChangedField('status') && $eventArgs->getNewValue('status') === NewCircuitRequest::STATUS_INSTALLED) {
            // Create a new circuit
        }
    }

    public function preRemove(LifecycleEventArgs $eventArgs) {
        $newCircuitRequest = $eventArgs->getEntity();
        if(!$newCircuitRequest instanceof NewCircuitRequest) {
            return;
        }

        $em = $eventArgs->getEntityManager();
    }
}