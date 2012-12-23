<?php
namespace Kp\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery as ProxyQuery;

class MenuItemAdmin extends Admin
{
    protected $datagridValues = array(
        '_sort_order' =>  'ASC',
        '_sort_by'    => 'root, lft'
    );

    protected $maxPerPage = 2500;

    protected function configureRoutes(RouteCollection $collection)
    {
    }

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('locale')
            ->add('name')
            ->add('isTag')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
   protected function configureFormFields(FormMapper $form)
    {
        $subject = $this->getSubject();
        $id = $subject->getId();

        $form
            ->with('General')
                ->add('parent', null, array('label' => 'Parent'
                                          , 'required'=>true
                                          , 'query_builder' => function($er) use ($id) {
                                                $qb = $er->getChildrenQueryBuilder(null, false, null, 'ASC', false);
                                                if ($id){
                                                    $qb
                                                        ->where('materialized_path_entity.id <> :id')
                                                        ->setParameter('id', $id)
                                                        ->orderBy('materialized_path_entity.path');
                                                    ;
                                                } else {
                                                    $qb
                                                        ->orderBy('materialized_path_entity.path');
                                                    ;
                                                }
                                                return $qb;
                                            }
                    ))
                ->add('name')
                ->add('locale', 'choice', array('choices' => array('en' => 'en'/*, 'el' => 'el'*/)))
                ->add('isTag', null, array('required' => false))
                ->add('order', null, array('label' => 'Override Order'))
                ->add('searchResultsPage', null, array('label' => 'Is Search Results Page', 'required' => false))
                ->add('page', null, array('label' => 'Page Id'))
            ->end()
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
            //->add('up', 'text', array('template' => 'KpAdminBundle::field_tree_up.html.twig', 'label'=>' '))
            //->add('down', 'text', array('template' => 'KpAdminBundle::field_tree_down.html.twig', 'label'=>' '))
            ->add('id', null, array('sortable'=>false))
            ->addIdentifier('laveled_title', 'raw', array('sortable'=>false, 'label'=>'Page Title'))
            ->add('isTag')
            ->add('order')
            ->add('page')
            ->add('_action', 'actions', array(
                    'actions' => array(
                        'edit' => array(),
                        'delete' => array()
                    ), 'label'=> 'Actions'
                ))
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
            ->add('locale')
            ->add('name')
        ;
    }

   public function createQuery($context = 'list')
    {
        $em = $this->modelManager->getEntityManager('Kp\SiteBundle\Entity\MenuItem');
        $queryBuilder = $em->getRepository('KpSiteBundle:MenuItem')->getChildrenQueryBuilder(null, false, null, 'ASC', false);
        $queryBuilder->where('materialized_path_entity.parent IS NOT NULL');
        $queryBuilder->orderBy('materialized_path_entity.path');

        /*$queryBuilder
            //->where('p.locale = :locale')
            //->setParameter('locale', 'en')
        ;*/

        $query = new ProxyQuery($queryBuilder);
        return $query;
    }
}