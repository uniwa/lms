<?php
namespace Psdtg\AdminBundle\Admin;

use Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CircuitAdmin extends Admin
{
    protected $datagridValues = array(
        '_sort_order' => 'ASC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'id' // name of the ordered field (default = the model id
    );

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('unit.mmId')
            ->add('unit.name')
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
            ->add('unit', 'mmunit', array('required' => true))
            ->add('activatedAt', 'genemu_jquerydate', array('widget' => 'single_text'))
            //->add('services')
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
                    'show' => array(),
                    'edit' => array(),
            )))
            ->addIdentifier('id')
            ->add('unit.mmId')
            ->add('unit.name')
            ->add('unit.categoryName')
            ->add('unit.fy')
            ->add('activatedAt')
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
            ->add('unit', null, array(), 'mmunit')
            ->add('unit.categoryName', null, array(), 'mmcategory')
            ->add('unit.fyName', null, array(), 'mmfy')
            ->add('activatedAt', 'doctrine_orm_datetime_range', array(), null, array('widget' => 'single_text', 'required' => false, 'attr' => array('class' => 'datepicker')))
        ;
    }
}