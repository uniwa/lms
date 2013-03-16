<?php
namespace Psdtg\AdminBundle\Admin\Kedo;

use Psdtg\AdminBundle\Admin\NewCircuitRequestAdmin as BaseNewCircuitRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class NewCircuitRequestAdmin extends BaseNewCircuitRequestAdmin
{
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $subject = $this->getSubject();
        $formMapper
            ->add('status', 'choice', array('choices' => Request::getStatuses(), 'disabled' => true))
            ->add('submitterId', null, array('disabled' => true, 'data' => $subject->getSubmitterId()))
        ;
    }
}