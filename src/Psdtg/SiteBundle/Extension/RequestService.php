<?php

namespace Psdtg\SiteBundle\Extension;

use Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit;
use Psdtg\SiteBundle\Entity\Requests\Request;
use Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest;
use Psdtg\SiteBundle\Entity\Requests\RemoveCircuitRequest;
use Psdtg\SiteBundle\Entity\Requests\ActivateServiceRequest;
use Psdtg\SiteBundle\Entity\Requests\ChangeServiceRequest;

class RequestService {
    public function approveRequest(Request $request) {
        $methods = get_class_methods($this);
        $requestClass = get_class($request);
        foreach($methods as $curMethod) {
            $cutMethod = substr($curMethod, 7);
            if($cutMethod != 'Request' && strpos($requestClass, $cutMethod) !== false) {
                return $this->$curMethod($request);
            }
        }
        throw new \Exception('Method not found');
    }

    protected function approveNewCircuitRequest(NewCircuitRequest $request) {
        $circuit = new PhoneCircuit();
        $circuit->setNewCircuitRequest($request);
        $circuit->setConnectivityType($request->getConnectivityType());
        $circuit->setUnit($request->getUnit());
        $circuit->setBandwidth($request->getBandwidth());
        $circuit->setPaidByPsd(true);
        $circuit->setComments($request->getComments());
        $request->setCircuit($circuit);
    }

    protected function approveRemoveCircuitRequest(RemoveCircuitRequest $request) {
        $request->getCircuit()->setDeletedAt(new \DateTime('now'));
    }

    protected function approveActivateServiceRequest(ActivateServiceRequest $request) {
        echo 'activateservice';
        die();
    }
}
?>
