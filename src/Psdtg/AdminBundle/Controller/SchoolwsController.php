<?php

namespace Psdtg\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class SchoolwsController extends Controller {
    /**
     * @Route("/getschool/{id}", name="getschool")
     * @Template
     */
    public function getSchoolAction($id) {
        $soapURL = "https://ws.is.sch.gr/Retriever.svc?wsdl" ;
        $soapParameters = Array('login' => "schoolws", 'password' => "Apl0us+3fs!") ;
        $soapClient = new \SoapClient($soapURL, $soapParameters);

        $soapResult = $soapClient->GetUnits(array('StartAt' => $id, 'Count' => 1)) ;
        /*var_dump($soapResult);
        die();*/
        return new Response(json_encode($soapResult->GetUnitsResult->Unit));
    }
}