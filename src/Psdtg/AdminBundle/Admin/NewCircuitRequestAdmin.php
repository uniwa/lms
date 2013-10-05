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
            ->add('circuitType', null, array('required' => true, 'query_builder' => $this->getAllowedCircuitTypes()))
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

    protected function getAllowedCircuitTypes() {
        $ctRepository = $this->getModelManager()->getEntityManager('Psdtg\SiteBundle\Entity\Circuits\CircuitType')->getRepository('Psdtg\SiteBundle\Entity\Circuits\CircuitType');
        return $ctRepository->getCircuitTypesQb(array('noLease' => false));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);
        $datagridMapper
            
        ;
    }
}