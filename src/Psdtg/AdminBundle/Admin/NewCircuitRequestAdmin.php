<?php
namespace Psdtg\AdminBundle\Admin;

use Psdtg\SiteBundle\Entity\Requests\Request;
use Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class NewCircuitRequestAdmin extends RequestAdmin
{
    protected function configureShowField(ShowMapper $showMapper)
    {
        parent::configureShowField($showMapper);
        $showMapper
            ->add('unit.mmId')
            ->add('unit.name')
            ->add('unit.state')
            ->add('unit.categoryName')
            ->add('unit.fy')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('unit', 'mmunit', array('required' => true))
            ->add('techFactsheetNo')
            ->add('connectivityType', null, array('required' => true, 'query_builder' => $this->getAllowedConnectivityTypes()))
            ->add('bandwidthProfile', 'bandwidth_profile', array('required' => true, 'dependentProperty' => 'connectivityType', 'dependentField' => 'connectivityType'))
        ;
        $subject = $this->getSubject();
        if($subject->getStatus() === NewCircuitRequest::STATUS_INSTALLED) {
            foreach($formMapper->getFormBuilder()->all() as $curField) {
                if($curField->getName() !== 'status') {
                    $curField->setDisabled(true);
                }
            }
        }
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper
            ->add('unit.name')
            ->add('unit.categoryName')
            ->add('unit.fy')
        ;
    }

    private function getAllowedConnectivityTypes() {
        $ctRepository = $this->getModelManager()->getEntityManager('Psdtg\SiteBundle\Entity\Circuits\ConnectivityType')->getRepository('Psdtg\SiteBundle\Entity\Circuits\ConnectivityType');
        return $ctRepository->getConnectivityTypesQb(array('noLease' => false));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);
        $datagridMapper
            ->add('unit', null, array(), 'mmunit')
            ->add('unit.categoryName', null, array(), 'mmcategory', array('required' => false))
            ->add('unit.fyName', null, array(), 'mmfy', array('required' => false))
            ->add('unit.state', null, array(), 'choice', array('choices' => array('ΕΝΕΡΓΗ' => 'ΕΝΕΡΓΗ', 'ΚΑΤΑΡΓΗΜΕΝΗ' => 'ΚΑΤΑΡΓΗΜΕΝΗ', 'ΣΕ ΑΝΑΣΤΟΛΗ' => 'ΣΕ ΑΝΑΣΤΟΛΗ')))
        ;
    }
}