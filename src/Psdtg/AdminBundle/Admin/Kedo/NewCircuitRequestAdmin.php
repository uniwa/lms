<?php
namespace Psdtg\AdminBundle\Admin\Kedo;

use Psdtg\AdminBundle\Admin\NewCircuitRequestAdmin as BaseNewCircuitRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class NewCircuitRequestAdmin extends BaseNewCircuitRequestAdmin
{
    protected $baseRouteName = 'admin_lms_newcircuitrequest_kedo';
    protected $baseRoutePattern = 'newcircuitrequest_kedo';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $subject = $this->getSubject();
        $formMapper
            ->add('status', 'requeststatus', array('class' => $this->getClass()))
            //->add('submitterId', null, array('disabled' => true, 'data' => $subject->getSubmitterId()))
        ;
    }
}