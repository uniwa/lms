<?php
namespace Psdtg\AdminBundle\Admin;

use Psdtg\SiteBundle\Entity\Requests\Request;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class RemoveCircuitRequestAdmin extends RequestAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('circuit', 'text', array('required' => true))
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