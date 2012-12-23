<?php
namespace Psdtg\AdminBundle\Admin;

use Psdtg\SiteBundle\Entity\Requests\Request;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

abstract class RequestAdmin extends Admin
{
    protected $datagridValues = array(
        '_sort_order' => 'ASC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'id' // name of the ordered field (default = the model id
    );

    protected function configureRoutes(RouteCollection $collection)
    {
        if($this->getSecurityContext()->getToken() != null) {
            $user = $this->getSecurityContext()->getToken()->getUser();
            if($user->hasRole('ROLE_KEDO')) {
            $collection
                ->remove('create')
                ;
            }
        }
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
            ->add('submitterId')
            ->add('status')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $user = $this->getSecurityContext()->getToken()->getUser();
        $subject = $this->getSubject();
        if($subject->getId() == null && $this->getSecurityContext()->isGranted('ROLE_KEDO')) {
            throw new AccessDeniedException('KEDO user cannot create new requests');
        }
        $formMapper
            ->add('ypepthId', null, array('disabled' => $subject->getYpepthId() != null ? true: false))
            ->add('submitterId', null, array('disabled' => true, 'data' => $subject->getSubmitterId() != '' ? $subject->getSubmitterId() : $user->getUsername()))
            ->add('status', 'choice', array('choices' => Request::getStatuses(), 'disabled' => ($user->hasRole('ROLE_KEDO') && $subject->getLine() == null) ? false : true))
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
            ->add('submitterId')
            ->add('status')
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

    public function prePersist($object)
    {
        $user = $this->getSecurityContext()->getToken()->getUser();
        $object->setSubmitterId($user->getUsername());
    }

    public function setSecurityContext($securityContext) {
        $this->securityContext = $securityContext;
    }

    public function getSecurityContext() {
        return $this->securityContext;
    }
}