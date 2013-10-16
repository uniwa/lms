<?php
namespace Psdtg\SiteBundle\Extension;

use Doctrine\ORM\Event\PreUpdateEventArgs;

use Psdtg\SiteBundle\Extension\RequestService;
use Psdtg\SiteBundle\Entity\Requests\Request;
use Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest;

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
        if ($eventArgs->hasChangedField('status') && ($eventArgs->getNewValue('status') === NewCircuitRequest::STATUS_INSTALLED || $eventArgs->getNewValue('status') === Request::STATUS_APPROVED)) {
            $this->requestService->approveRequest($request);
        }
    }
}