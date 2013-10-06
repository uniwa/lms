<?php

namespace Psdtg\SiteBundle\Extension;

use Doctrine\Common\EventArgs;
use Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit;
use Psdtg\SiteBundle\Entity\Circuits\ConnectivityType;
use Psdtg\SiteBundle\Entity\MMSyncableEntity;

class MMSyncableListener
{
    protected $mmservice;

    public function __construct(MMService $mmservice) {
        $this->mmservice = $mmservice;
    }

    public function prePersist(EventArgs $args) {
        $entity = $args->getEntity();
        if(!$entity instanceof MMSyncableEntity) {
            return;
        }

        $this->mmservice->persistMM($entity);
    }

    public function preUpdate(EventArgs $args) {
        $entity = $args->getEntity();
        if(!$entity instanceof MMSyncableEntity) {
            return;
        }

        $this->mmservice->persistMM($entity);
    }

    public function preRemove(EventArgs $args) {
        $entity = $args->getEntity();
        if(!$entity instanceof MMSyncableEntity) {
            return;
        }
        $oldDeletedAt = $entity->getDeletedAt();
        $entity->setDeletedAt(new \DateTime('now'));
        $this->mmservice->persistMM($entity);
        $entity->setDeletedAt($oldDeletedAt);
    }
}