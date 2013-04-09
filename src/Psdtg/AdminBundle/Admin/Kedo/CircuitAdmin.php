<?php
namespace Psdtg\AdminBundle\Admin\Kedo;

use Psdtg\AdminBundle\Admin\CircuitAdmin as BaseCircuitAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class CircuitAdmin extends BaseCircuitAdmin
{
    protected $baseRouteName = 'admin_lms_circuit_kedo';
    protected $baseRoutePattern = 'circuit_kedo';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
    }
}