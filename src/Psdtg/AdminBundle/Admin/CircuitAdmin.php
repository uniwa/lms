<?php
namespace Psdtg\AdminBundle\Admin;

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
            ->remove('acl')
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
            ->add('unit.mmId')
            ->add('unit.name')
            ->add('unit.categoryName')
            ->add('unit.fy')
            ->add('unit.state')
            ->add('activatedAt', 'date')
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
            ->add('comments')
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
                    'show' => array('template' => 'PsdtgAdminBundle:Button:show_button.html.twig'),
                    'edit' => array('template' => 'PsdtgAdminBundle:Button:edit_button.html.twig'),
            )))
            ->add('unit.mmId', 'string')
            ->add('unit.registryNo', 'string')
            ->add('unit.name')
            ->add('unit.categoryName')
            ->add('unit.fy')
            ->add('activatedAt', 'date')
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
            ->add('unit.state', null, array(), 'choice', array('choices' => array('ΕΝΕΡΓΗ' => 'ΕΝΕΡΓΗ', 'ΚΑΤΑΡΓΗΜΕΝΗ' => 'ΚΑΤΑΡΓΗΜΕΝΗ', 'ΣΕ ΑΝΑΣΤΟΛΗ' => 'ΣΕ ΑΝΑΣΤΟΛΗ')))
            ->add('activatedAt', 'doctrine_orm_datetime_range', array(), null, array('widget' => 'single_text', 'required' => false, 'attr' => array('class' => 'datepicker')))
        ;
    }

    public function getExportFields()
    {
        return array(
            'id',
            'unit.name',
            'unit.categoryName',
            'unit.fy',
            'activatedAt',
        );
    }

    public function getBatchActions()
    {
        return array();
    }
}