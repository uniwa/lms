<?php

namespace Psdtg\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use FOS\RestBundle\View\View;

class UnitController extends Controller {
    /**
     * @Secure("ROLE_USER")
     */
    public function getUnitsAction() {
        $mmservice = $this->container->get('psdtg.mm.service');
        $params = array();
        $securityContext = $this->container->get('security.context');
        /* Show only requests from same FY as the user
        if(!$securityContext->isGranted('ROLE_KEDO')) {
            $fyName = $securityContext->getToken()->getUser()->getUnit()->getFy()->getName();
            $params['fy'] = $fyName;
        }*/
        $unitsByName = $mmservice->findUnitsBy(array(
            'name' => $this->getRequest()->get('name'),
        )+$params);
        $unitsByRegistryNo = $mmservice->findUnitsBy(array(
            'registry_no' => $this->getRequest()->get('name'),
        )+$params);
        $unitsMmId = $mmservice->findUnitsBy(array(
            'mm_id' => $this->getRequest()->get('name'),
        )+$params);
        $units = array_unique(array_merge($unitsByName, $unitsByRegistryNo, $unitsMmId));
        $view = View::create()->setStatusCode(200)->setData($units);
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}