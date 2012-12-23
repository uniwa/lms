<?php
namespace Kp\AdminBundle\Builder;

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\Datagrid;

use Kp\AdminBundle\Datagrid\DnnaPager;
use Sonata\DoctrineORMAdminBundle\Builder\DatagridBuilder;

class DnnaDatagridBuilder extends DatagridBuilder
{
    public function getBaseDatagrid(AdminInterface $admin, array $values = array())
    {
        $pager = new DnnaPager;
        $pager->setCountColumn($admin->getModelManager()->getIdentifierFieldNames($admin->getClass()));

        $formBuilder = $this->formFactory->createNamedBuilder('filter', 'form', array(), array('csrf_protection' => false));

        return new Datagrid($admin->createQuery(), $admin->getList(), $pager, $formBuilder, $values);
    }
}