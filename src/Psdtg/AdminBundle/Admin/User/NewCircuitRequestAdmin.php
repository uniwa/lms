<?php
namespace Psdtg\AdminBundle\Admin\User;

use Psdtg\AdminBundle\Admin\NewCircuitRequestAdmin as BaseNewCircuitRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class NewCircuitRequestAdmin extends BaseNewCircuitRequestAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
    }
}