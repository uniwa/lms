<?php

namespace Psdtg\AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest;
use Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit;

class NewCircuitRequestController extends CRUDController {
    public function redirectTo($object) {
        if(!($object instanceof NewCircuitRequest)) {
            throw new \Exception('Invalid object type');
        }
        if($object->getStatus() == NewCircuitRequest::STATUS_INSTALLED) {
            try {
                $circuitAdmin = $this->get('sonata.admin.phonecircuits.kedo');
                $circuit = $object->getCircuit();
                $url = $circuitAdmin->generateObjectUrl('edit', $circuit);
                return new RedirectResponse($url);
            } catch(\Exception $e) {
                $object->setStatus(NewCircuitRequest::STATUS_APPROVED);
                $this->admin->update($object);
                $this->addFlash('sonata_flash_error', $e->getMessage());
                return parent::redirectTo($object);
            }
        }
        return parent::redirectTo($object);
    }
}