<?php
namespace Psdtg\AdminBundle\Admin;

use Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PhoneCircuitAdmin extends CircuitAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('circuitType', null, array('required' => true))
            ->add('number')
            ->add('paidByPsd', null, array('required' => true))
            ->add('bandwidth')
            ->add('realspeed')
        ;
    }

    protected function configureListFields(ListMapper $listMapper) {
        parent::configureListFields($listMapper);
        $listMapper
            ->add('circuitType.name', 'trans')
            ->add('number')
            ->add('paidByPsd')
            ->add('bandwidth', 'trans')
            ->add('realspeed', 'trans')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);
        $datagridMapper

        ;
    }
}