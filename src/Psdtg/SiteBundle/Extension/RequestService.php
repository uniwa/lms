<?php

namespace Psdtg\SiteBundle\Extension;

use Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit;
use Psdtg\SiteBundle\Entity\Requests\Request;
use Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest;

class RequestService {
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
    }
}
?>
