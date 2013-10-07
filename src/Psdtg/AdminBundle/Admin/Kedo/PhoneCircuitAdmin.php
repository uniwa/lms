<?php
namespace Psdtg\AdminBundle\Admin\Kedo;

use Psdtg\AdminBundle\Admin\PhoneCircuitAdmin as BasePhoneCircuitAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class PhoneCircuitAdmin extends BasePhoneCircuitAdmin
{
    protected $baseRouteName = 'admin_lms_circuit_kedo';
    protected $baseRoutePattern = 'circuit_kedo';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('connectivityType', null, array('required' => true))
        ;
    }
}