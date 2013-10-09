<?php
namespace Psdtg\AdminBundle\Admin\Kedo;

use Psdtg\AdminBundle\Admin\ChangeServiceRequestAdmin as BaseChangeServiceRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;

use Psdtg\SiteBundle\Entity\Requests\Request;

class ChangeServiceRequestAdmin extends BaseChangeServiceRequestAdmin
{
    protected $baseRouteName = 'admin_lms_changeservicerequest_kedo';
    protected $baseRoutePattern = 'changeservicerequest_kedo';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('status', 'choice', array('choices' => Request::getStatuses()))
        ;
    }
}