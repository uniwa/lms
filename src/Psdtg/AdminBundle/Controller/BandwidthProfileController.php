<?php

namespace Psdtg\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use FOS\RestBundle\View\View;

class BandwidthProfileController extends Controller {
    /**
     * @Secure("ROLE_USER")
     */
    public function getBandwidthProfilesAction() {
        $repo = $this->container->get('doctrine')->getManager()->getRepository('Psdtg\SiteBundle\Entity\Circuits\BandwidthProfile');
        $connTypeRepo = $this->container->get('doctrine')->getManager()->getRepository('Psdtg\SiteBundle\Entity\Circuits\ConnectivityType');
        $connType = $connTypeRepo->find($this->getRequest()->get('connectivityType'));
        if($connType != null) {
            $bandwidthProfiles = $repo->findBy(array(
                'connectivityType' => $connType,
            ));
        } else {
            throw new \Exception('Couldn\'t find this connectivity Type');
        }
        $view = View::create()->setStatusCode(200)->setData($bandwidthProfiles);
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}