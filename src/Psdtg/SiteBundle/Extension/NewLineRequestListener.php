<?php
namespace Psdtg\SiteBundle\Extension;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Psdtg\SiteBundle\Entity\Circuit;

class NewLineRequestListener {
    public function prePersist(LifecycleEventArgs $lcea) {
        $circuit = $lcea->getEntity();
        if(!$circuit instanceof Circuit) {
            return;
        }

        $em = $lcea->getEntityManager();

    }

    public function preUpdate(PreUpdateEventArgs $eventArgs) {
        $circuit = $eventArgs->getEntity();
        if(!$circuit instanceof Circuit) {
            return;
        }
        if (!$eventArgs->hasChangedField('deleted') || $eventArgs->getNewValue('deleted') != 1) {
            return;
        }
    }

    public function preRemove(LifecycleEventArgs $eventArgs) {
        $circuit = $eventArgs->getEntity();
        if(!$circuit instanceof Circuit) {
            return;
        }

        $em = $eventArgs->getEntityManager();
    }
}