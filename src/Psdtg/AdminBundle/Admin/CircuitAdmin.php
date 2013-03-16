<?php
namespace Psdtg\AdminBundle\Admin;

use Psdtg\SiteBundle\Entity\Circuit;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CircuitAdmin extends Admin
{
    protected $datagridValues = array(
        '_sort_order' => 'ASC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'id' // name of the ordered field (default = the model id
    );

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ;
    }

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('ypepthId')
            ->add('number')
            ->add('status')
            ->add('installDate')
            //->add('newLineRequest')
            ->add('address')
            ->add('lineType')
            //->add('adsl')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('ypepthId', null, array('disabled' => true))
            ->add('number')
            ->add('status', 'choice', array('choices' => Circuit::getStatuses()))
            ->add('installDate')
            //->add('newLineRequest')
            ->add('address', null, array('disabled' => true))
            ->add('lineType', 'choice', array('choices' => Circuit::getLineTypes(), 'disabled' => true))
            //->add('adsl')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array(),
                    'edit' => array(),
            )))
            ->addIdentifier('id')
            ->add('ypepthId')
            ->add('number')
            ->add('status')
            ->add('installDate')
            //->add('newLineRequest')
            ->add('address')
            ->add('lineType')
            //->add('adsl')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
        ;
    }
}