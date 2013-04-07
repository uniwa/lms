<?php
namespace Psdtg\AdminBundle\Admin;

use Psdtg\SiteBundle\Entity\Requests\Request;
use Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class NewCircuitRequestAdmin extends RequestAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('techFactsheetNo')
            ->add('circuitType', 'choice', array('choices' => NewCircuitRequest::getNewCircuitRequestTypes()))
        ;
        $subject = $this->getSubject();
        if($subject->getStatus() === Request::STATUS_APPROVED) {
            foreach($formMapper->getFormBuilder()->all() as $curField) {
                if($curField->getName() !== 'status') {
                    $curField->setDisabled(true);
                }
            }
        }
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);
        $datagridMapper
            
        ;
    }
}