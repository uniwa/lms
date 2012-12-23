<?php
namespace Kp\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Kp\AdminBundle\Datagrid\DnnaProxyQuery;
use Doctrine\ORM\EntityRepository;

class PageAdmin extends Admin
{
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
            ->add('id')
            ->add('locale')
            ->add('searchable')
            ->add('title')
            ->add('caption')
            ->add('content')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();
        if ($subject->getId()){
            $readonly = true;
        } else {
            $readonly = false;
        }

        $formMapper
            ->add('id', null, array('read_only' => $readonly))
            ->add('locale', 'choice', array('choices' => array('en' => 'en', 'el' => 'el')))
            ->add('searchable', null, array('required' => false))
            ->add('title')
            ->add('caption', null, array('required' => false))
            ->add('tags', 'entity', array('class' => 'KpSiteBundle:MenuItem', 'multiple' => true, 'required' => false,
                'query_builder' => function(EntityRepository $er) {
                    return $er->getChildrenQueryBuilder(null, false, null, 'ASC', false)
                        ->andWhere('materialized_path_entity.isTag = 1')
                        ->orderBy('materialized_path_entity.path');
                    ;
                },
            ))
            ->add('relatedPages', 'entity', array('class' => 'KpSiteBundle:Page', 'multiple' => true, 'required' => false))
            ->add('authors', 'entity', array('class' => 'KpSiteBundle:Person', 'multiple' => true, 'required' => false, 'label' => 'Related People'))
            ->add('authorsPriority', null, array('label' => 'Related People Priority', 'required' => false))
            ->add('summary', 'textarea', array('attr' => array('class' => 'tinymce'), 'required' => false))
            ->add('content', 'textarea', array('attr' => array('class' => 'tinymce')))
            ->with('Slideshow Options')
                ->add('inSlideshow', null, array('required' => false))
                ->add('slideorder', null, array('label' => 'Slideshow Order'))
            ->end()
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper, $params = array())
    {
        $actions = array(
            'view' => array(),
            'edit' => array(),
        );
        $listMapper
            ->add('_action', 'actions', array(
                'actions' => $actions
            ));
        $listMapper->addIdentifier('id')
            ->add('locale')
            ->add('searchable')
            ->add('inSlideshow')
            ->add('slideorder', null, array('label' => 'Slideshow Order'))
            ->add('tags')
            ->add('relatedPages')
            ->add('authors', null, array('label' => 'Related People'))
            ->add('authorsPriority', null, array('label' => 'Related People Priority'))
            ->add('title')
            ->add('caption')
            ->add('content', 'raw')
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
            ->add('id')
            ->add('title')
            ->add('content')
        ;
    }

    public function getExportFields()
    {
        $results = $this->getModelManager()->getExportFields($this->getClass());

        // Need to add again our foreign key field here
        $results[] = 'tagsImploded';
        $results[] = 'relatedPagesImploded';

        return $results;
    }
}