<?php

namespace Psdtg\AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Psdtg\SiteBundle\Entity\Requests\Request;
use Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest;

class RequestController extends CRUDController {
    public function redirectTo($object) {
        if(!($object instanceof Request)) {
            throw new \Exception('Invalid object type');
        }
        if($object instanceof NewCircuitRequest && $object->getStatus() == NewCircuitRequest::STATUS_INSTALLED) {
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
        } else if($object instanceof Request && $object->getStatus() == Request::STATUS_APPROVED) {
            $url = $this->admin->generateUrl('list');
            return new RedirectResponse($url);
        }
        return parent::redirectTo($object);
    }
}