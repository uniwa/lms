<?php
namespace Psdtg\AdminBundle\Admin\Kedo;

use Psdtg\AdminBundle\Admin\ChangeOwnershipRequestAdmin as BaseChangeOwnershipRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class ChangeOwnershipRequestAdmin extends BaseChangeOwnershipRequestAdmin
{
    protected $baseRouteName = 'admin_lms_changeownershiprequest_kedo';
    protected $baseRoutePattern = 'changeownershiprequest_kedo';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('newUnit', 'mmunit', array('disabled' => true))
            ->add('status', 'requeststatus', array('class' => $this->getClass()))
        ;
    }
}