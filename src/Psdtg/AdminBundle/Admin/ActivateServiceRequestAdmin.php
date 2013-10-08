<?php
namespace Psdtg\AdminBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;

class ActivateServiceRequestAdmin extends ExistingCircuitRequestAdmin
{
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper
            ->add('newConnectivityType.name', 'trans')
        ;
    }
}