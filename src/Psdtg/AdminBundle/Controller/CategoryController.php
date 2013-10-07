<?php

namespace Psdtg\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use FOS\RestBundle\View\View;

class CategoryController extends Controller {
    /**
     * @Secure("ROLE_USER")
     */
    public function getCategoriesAction() {
        $repo = $this->container->get('doctrine')->getManager()->getRepository('Psdtg\SiteBundle\Entity\Unit');
        $categories = $repo->findCategories(array(
            'name' => $this->getRequest()->get('name')
        ));
        $view = View::create()->setStatusCode(200)->setData($categories);
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}