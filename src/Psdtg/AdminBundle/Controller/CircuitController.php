<?php

namespace Psdtg\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use FOS\RestBundle\View\View;

class CircuitController extends Controller {
    /**
     * @Secure("ROLE_USER")
     */
    public function getCircuitsAction() {
        $repo = $this->container->get('doctrine')->getManager()->getRepository('Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit');
        $filters = $this->getRequest()->get('filters', urlencode(json_encode(array())));
        $decodedFilters = json_decode(urldecode($filters));
        $arrayFilters = array();
        foreach($decodedFilters as $curName => $curFilter) {
            $arrayFilters[$curName] = $curFilter;
        }
        $categories = $repo->findCircuits(array_merge(array(
            'name' => $this->getRequest()->get('name')
        ), $arrayFilters));
        $view = View::create()->setStatusCode(200)->setData($categories);
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}