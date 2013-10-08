<?php
namespace Psdtg\AdminBundle\Admin\Kedo;

use Psdtg\AdminBundle\Admin\ActivateServiceRequestAdmin as BaseActivateServiceRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ActivateServiceRequestAdmin extends BaseActivateServiceRequestAdmin
{
    protected $baseRouteName = 'admin_lms_activateservicerequest_kedo';
    protected $baseRoutePattern = 'activateservicerequest_kedo';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('status', 'choice', array('choices' => Request::getStatuses()))
        ;
    }
}