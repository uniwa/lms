<?php
namespace Psdtg\AdminBundle\Admin\Kedo;

use Psdtg\AdminBundle\Admin\ChangeServiceRequestAdmin as BaseChangeServiceRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ChangeServiceRequestAdmin extends BaseChangeServiceRequestAdmin
{
    protected $baseRouteName = 'admin_lms_changeservicerequest_kedo';
    protected $baseRoutePattern = 'changeservicerequest_kedo';

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