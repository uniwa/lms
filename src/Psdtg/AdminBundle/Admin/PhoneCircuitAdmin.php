<?php
namespace Psdtg\AdminBundle\Admin;

use Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PhoneCircuitAdmin extends CircuitAdmin
{
    protected function configureShowField(ShowMapper $showMapper)
    {
        parent::configureShowField($showMapper);
        $showMapper
            ->add('connectivityType.name', 'trans')
            ->add('number')
            ->add('paidByPsd')
            ->add('bandwidth', 'trans')
            ->add('realspeed', 'trans')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('number')
            ->add('paidByPsd', null, array('required' => false))
            ->add('bandwidth')
            ->add('realspeed')
        ;
    }

    protected function configureListFields(ListMapper $listMapper) {
        parent::configureListFields($listMapper);
        $listMapper
            ->add('connectivityType.name', 'trans')
            ->add('number')
            ->add('bandwidth', 'trans')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);
        $datagridMapper
        ;
    }

    public function getExportFields()
    {
        return array_merge(parent::getExportFields(),array(
            //'connectivityType.name',
            'number',
            'paidByPsd',
            'bandwidth',
            'realspeed',
        ));
    }
}