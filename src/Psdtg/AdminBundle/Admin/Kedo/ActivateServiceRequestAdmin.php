<?php
namespace Psdtg\AdminBundle\Admin\Kedo;

use Psdtg\AdminBundle\Admin\ActivateServiceRequestAdmin as BaseActivateServiceRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class ActivateServiceRequestAdmin extends BaseActivateServiceRequestAdmin
{
    protected $baseRouteName = 'admin_lms_changeservicerequest_kedo';
    protected $baseRoutePattern = 'changeservicerequest_kedo';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper->remove('circuit');
        $formMapper
            ->add('status', 'requeststatus', array('class' => $this->getClass()))
        ;
    }
}