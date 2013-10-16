<?php

namespace Psdtg\SiteBundle\Extension;

use Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit;
use Psdtg\SiteBundle\Entity\Requests\Request;
use Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest;

class RequestService {
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function approveRequest(Request $request) {
        if($request instanceof NewCircuitRequest) {
            return $this->approveNewCircuitRequest($request);
        } else {
            throw new \Exception('Unknown request type');
        }
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
        // We don't persist here because the circuit will be cascaded
    }
}
?>
