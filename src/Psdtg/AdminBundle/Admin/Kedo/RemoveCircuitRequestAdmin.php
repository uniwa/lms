<?php
namespace Psdtg\AdminBundle\Admin\Kedo;

use Psdtg\AdminBundle\Admin\RemoveCircuitRequestAdmin as BaseRemoveCircuitRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;

use Psdtg\SiteBundle\Entity\Requests\Request;

class RemoveCircuitRequestAdmin extends BaseRemoveCircuitRequestAdmin
{
    protected $baseRouteName = 'admin_lms_removecircuitrequest_kedo';
    protected $baseRoutePattern = 'removecircuitrequest_kedo';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('status', 'choice', array('choices' => Request::getStatuses()))
        ;
    }
}