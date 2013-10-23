<?php
namespace Psdtg\SiteBundle\Extension;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

use Psdtg\SiteBundle\Extension\RequestService;
use Psdtg\SiteBundle\Entity\Requests\Request;
use Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest;
use Psdtg\SiteBundle\Entity\Requests\ExistingCircuitRequest;

class RequestListener {
    protected $requestService;

    public function __construct(RequestService $requestService) {
        $this->requestService = $requestService;
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs) {
        $request = $eventArgs->getEntity();
        if(!$request instanceof Request) {
            return;
        }
        if ($eventArgs->hasChangedField('status')) {
            if(($request instanceof NewCircuitRequest && $eventArgs->getNewValue('status') === NewCircuitRequest::STATUS_INSTALLED) ||
                ($request instanceof Request && $eventArgs->getNewValue('status') === Request::STATUS_APPROVED)) {
                $this->requestService->approveRequest($request);
            }
        }
    }

    public function postUpdate(LifecycleEventArgs $eventArgs) {
        $request = $eventArgs->getEntity();
        if(!$request instanceof Request) {
            return;
        }
        if(($request instanceof NewCircuitRequest && $request->getCircuit() != null) || $request instanceof ExistingCircuitRequest) {
            $eventArgs->getEntityManager()->persist($request->getCircuit());
            $eventArgs->getEntityManager()->flush($request->getCircuit());
        }
    }
}