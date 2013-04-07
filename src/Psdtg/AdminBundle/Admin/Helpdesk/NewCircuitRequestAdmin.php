<?php
namespace Psdtg\AdminBundle\Admin\Helpdesk;

use Psdtg\AdminBundle\Admin\NewCircuitRequestAdmin as BaseNewCircuitRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class NewCircuitRequestAdmin extends BaseNewCircuitRequestAdmin
{
    protected $baseRouteName = 'admin_lms_newcircuitrequest_user';
    protected $baseRoutePattern = 'newcircuitrequest_user';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
    }
}