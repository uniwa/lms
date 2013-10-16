<?php
namespace Psdtg\AdminBundle\Admin\Kedo;

use Psdtg\AdminBundle\Admin\ActivateServiceRequestAdmin as BaseActivateServiceRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class ActivateServiceRequestAdmin extends BaseActivateServiceRequestAdmin
{
    protected $baseRouteName = 'admin_lms_activateservicerequest_kedo';
    protected $baseRoutePattern = 'activateservicerequest_kedo';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('newConnectivityType', null, array('disabled' => true, 'query_builder' => $this->getServiceConnectivityTypes()))
            ->add('newBandwidthProfile', 'bandwidth_profile', array('required' => true, 'dependentProperty' => 'connectivityType', 'dependentField' => 'newConnectivityType'))
            ->add('status', 'requeststatus', array('class' => $this->getClass()))
        ;
    }
}