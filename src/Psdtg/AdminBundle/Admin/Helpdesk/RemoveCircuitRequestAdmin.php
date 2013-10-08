<?php
namespace Psdtg\AdminBundle\Admin\Helpdesk;

use Psdtg\AdminBundle\Admin\RemoveCircuitRequestAdmin as BaseRemoveCircuitRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class RemoveCircuitRequestAdmin extends BaseRemoveCircuitRequestAdmin
{
    protected $baseRouteName = 'admin_lms_removecircuitrequest_user';
    protected $baseRoutePattern = 'removecircuitrequest_user';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
    }
}