<?php
namespace Psdtg\AdminBundle\Admin\Helpdesk;

use Psdtg\AdminBundle\Admin\ChangeOwnershipRequestAdmin as BaseChangeOwnershipRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class ChangeOwnershipRequestAdmin extends BaseChangeOwnershipRequestAdmin
{
    protected $baseRouteName = 'admin_lms_changeownershiprequest_user';
    protected $baseRoutePattern = 'changeownershiprequest_user';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('newUnit', 'mmunit', array('required' => true))
        ;
    }
}