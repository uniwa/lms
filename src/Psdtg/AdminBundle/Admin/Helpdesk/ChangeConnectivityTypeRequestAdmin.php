<?php
namespace Psdtg\AdminBundle\Admin\Helpdesk;

use Psdtg\AdminBundle\Admin\ChangeConnectivityTypeRequestAdmin as BaseChangeConnectivityTypeRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class ChangeConnectivityTypeRequestAdmin extends BaseChangeConnectivityTypeRequestAdmin
{
    protected $baseRouteName = 'admin_lms_changeconnectivitytyperequest_user';
    protected $baseRoutePattern = 'chagneconnectivitytyperequest_user';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('newConnectivityType', null, array('required' => true, 'query_builder' => $this->getServiceConnectivityTypes()))
            ->add('newBandwidthProfile', 'bandwidth_profile', array('required' => true, 'dependentProperty' => 'connectivityType', 'dependentField' => 'newConnectivityType'))
        ;
    }
}