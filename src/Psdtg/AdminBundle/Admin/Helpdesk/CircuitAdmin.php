<?php
namespace Psdtg\AdminBundle\Admin\Helpdesk;

use Psdtg\AdminBundle\Admin\CircuitAdmin as BaseCircuitAdmin;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CircuitAdmin extends BaseCircuitAdmin
{
    protected $baseRouteName = 'admin_lms_circuit_user';
    protected $baseRoutePattern = 'circuit_user';

    protected $securityContext;

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array(
            'list',
            'show',
            'batch',
        ));
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        // No Editing
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);
        $datagridMapper->remove('unit.fyName');
    }

    public function createQuery($context = 'list')
    {
        if($context === 'list') {
            // User should only see the lines belong to their FY
            $fyName = $this->securityContext->getToken()->getUser()->getUnit()->getFy()->getName();
            $repository = $this->getModelManager()->getEntityManager($this->getClass())->getRepository($this->getClass());

            $qb = $repository->createQueryBuilder('c')
                    ->join('c.unit', 'u')
                    ->andWhere('u.fyName = :fyName')
                    ->setParameter('fyName', $fyName)
            ;

            return new ProxyQuery($qb);
        }
    }

    public function setSecurityContext(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }
}