<?php
namespace Psdtg\AdminBundle\Admin;

use Psdtg\SiteBundle\Entity\Requests\Request;
use Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class ExistingCircuitRequestAdmin extends RequestAdmin
{
    protected function configureShowField(ShowMapper $showMapper)
    {
        parent::configureShowField($showMapper);
        $showMapper
            ->add('circuit.unit')
            ->add('circuit.number')
            ->add('circuit.connectivityType', 'trans')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('circuit', 'circuit', array('required' => true, 'help' => 'Αναζήτηση μπορεί να γίνει είτε με το όνομα της μονάδας είτε με τον αριθμό του κυκλώματος'))
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

    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper
            ->add('circuit.unit')
            ->add('circuit.number')
            ->add('circuit.connectivityType', 'trans')
            ->add('circuit.bandwidthProfile', 'trans')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);
        $datagridMapper
            ->add('circuit.unit', null, array(), 'mmunit')
        ;
    }
}