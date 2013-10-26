<?php
namespace Psdtg\AdminBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ActivateServiceRequestAdmin extends ChangeConnectivityTypeRequestAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper->remove('circuit');
        $formMapper
            ->add('unit', 'mmunit', array('required' => true))
            ->add('number', null, array('required' => true, 'help' => 'Ο αριθμός θα πρέπει να αφορά κύκλωμα που δεν ανήκει στο ΠΣΔ. Σε διαφορειτκή περίπτωση ακολουθείτε τη ροή "Αίτημα αλλαγής τύπου κυκλώματος".'))
            ->add('newConnectivityType', null, array('query_builder' => $this->getServiceConnectivityTypes()))
            ->add('newBandwidthProfile', 'bandwidth_profile', array('dependentProperty' => 'connectivityType', 'dependentField' => 'newConnectivityType'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper->remove('circuit.unit');
        $listMapper->remove('circuit.number');
        $listMapper->remove('circuit.connectivityType');
        $listMapper->remove('circuit.bandwidthProfile');
        $listMapper
            ->add('unit')
            ->add('number')
        ;
    }
}