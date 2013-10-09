<?php
namespace Psdtg\AdminBundle\Admin\Kedo;

use Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest;

use Psdtg\AdminBundle\Admin\NewCircuitRequestAdmin as BaseNewCircuitRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class NewCircuitRequestAdmin extends BaseNewCircuitRequestAdmin
{
    protected $baseRouteName = 'admin_lms_newcircuitrequest_kedo';
    protected $baseRoutePattern = 'newcircuitrequest_kedo';

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection
            ->remove('create')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $subject = $this->getSubject();
        $formMapper
            ->add('status', 'choice', array('choices' => NewCircuitRequest::getStatuses()))
            //->add('submitterId', null, array('disabled' => true, 'data' => $subject->getSubmitterId()))
        ;
    }
}