<?php
namespace Psdtg\AdminBundle\Admin\Helpdesk;

use Psdtg\AdminBundle\Admin\ActivateServiceRequestAdmin as BaseActivateServiceRequestAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class ActivateServiceRequestAdmin extends BaseActivateServiceRequestAdmin
{
    protected $baseRouteName = 'admin_lms_activateservicerequest_user';
    protected $baseRoutePattern = 'activateservicerequest_user';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('newConnectivityType', null, array('required' => true, 'query_builder' => $this->getServiceConnectivityTypes()))
        ;
    }

    private function getServiceConnectivityTypes() {
        $ctRepository = $this->getModelManager()->getEntityManager('Psdtg\SiteBundle\Entity\Circuits\ConnectivityType')->getRepository('Psdtg\SiteBundle\Entity\Circuits\ConnectivityType');
        return $ctRepository->getConnectivityTypesQb(array('isService' => true));
    }
}