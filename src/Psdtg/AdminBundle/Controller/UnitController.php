<?php

namespace Psdtg\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class UnitController extends Controller {
    public function getUnitAction($id) {
        /*$soapURL = "https://ws.is.sch.gr/Retriever.svc?wsdl" ;
        $soapParameters = Array('login' => "schoolws", 'password' => "Apl0us+3fs!") ;
        $soapClient = new \SoapClient($soapURL, $soapParameters);

        $soapResult = $soapClient->GetUnits(array('StartAt' => $id, 'Count' => 1)) ;
        /*var_dump($soapResult);
        die();
        return new Response(json_encode($soapResult->GetUnitsResult->Unit));*/
        $mmservice = $this->container->get('psdtg.mm.service');
        return new Response('');
    }

    public function getUnitsAction() {
        $mmservice = $this->container->get('psdtg.mm.service');
        $units = $mmservice->findBy(array(
            'name' => $this->getRequest()->get('name')
        ));
        $view = View::create()->setStatusCode(200)->setData($units);
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}