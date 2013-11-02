<?php
namespace Psdtg\AdminBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class ChangeOwnershipRequestAdmin extends ExistingCircuitRequestAdmin
{
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper
            ->remove('circuit.unit')
            ->add('unit', 'trans')
            ->add('newUnit', 'trans')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);
        $datagridMapper
            ->remove('circuit.unit')
            ->add('unit', null, array(), 'mmunit')
            ->add('newUnit', null, array(), 'mmunit')
        ;
    }
}