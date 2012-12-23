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

class PersonAdmin extends PageAdmin
{
    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        parent::configureShowField($showMapper);
        // Commented fields cause an invalid state exception if uncommented - Sonata bug?
        $showMapper
            //->add('id')
            //->add('locale')
            ->add('photoWebPath', 'image', array('label' => 'Photo'))
            ->add('hidefPhotoWebPath', 'image', array('label' => 'High Def Photo'))
            ->add('name')
            ->add('surname')
            ->add('phone')
            ->add('fax')
            ->add('email')
            ->add('lync')
            ->add('languages')
            ->add('education')
            //->add('content')
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
            ->add('tags', 'entity', array('class' => 'KpSiteBundle:MenuItem', 'multiple' => true, 'required' => false,
                'query_builder' => function(EntityRepository $er) {
                    return $er->getChildrenQueryBuilder(null, false, null, 'ASC', false)
                        ->andWhere('materialized_path_entity.isTag = 1')
                        ->orderBy('materialized_path_entity.path');
                    ;
                },
            ))
            ->add('photo', 'file', array('required' => false))
            ->add('hidefPhoto', 'file', array('required' => false, 'label' => 'High Def Photo'))
            ->add('vcard', 'file', array('required' => false))
            ->add('name')
            ->add('surname')
            ->add('title')
            ->add('position')
            ->add('phone', null, array('required' => false))
            ->add('fax', null, array('required' => false))
            ->add('email')
            ->add('lync', null, array('required' => false))
            ->add('languages')
            ->add('education')
            ->add('content', 'textarea', array('attr' => array('class' => 'tinymce')))
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
            ->add('tags')
            ->add('photoWebPath', 'image', array('required' => false, 'label' => 'Photo'))
            ->add('hidefPhotoWebPath', 'image', array('required' => false, 'label' => 'High Def Photo'))
            ->add('name')
            ->add('surname')
            ->add('title')
            ->add('position')
            ->add('phone')
            ->add('email')
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
            ->add('phone')
            ->add('fax')
            ->add('email')
            ->add('lync')
            ->add('languages')
            ->add('education')
            ->add('content')
        ;
    }
}