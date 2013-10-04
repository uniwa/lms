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
     * @Secure("ROLE_HELPDESK,ROLE_KEDO")
     */
    public function getUnitsAction() {
        $mmservice = $this->container->get('psdtg.mm.service');
        $params = array(
            'name' => $this->getRequest()->get('name')
        );
        $securityContext = $this->container->get('security.context');
        /* Show only requests from same FY as the user
        if(!$securityContext->isGranted('ROLE_KEDO')) {
            $fyName = $securityContext->getToken()->getUser()->getUnit()->getFy()->getName();
            $params['fy'] = $fyName;
        }*/
        $units = $mmservice->findUnitsBy($params);
        $view = View::create()->setStatusCode(200)->setData($units);
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}