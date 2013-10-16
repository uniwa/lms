<?php
namespace Psdtg\AdminBundle\Admin;

use Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PhoneCircuitAdmin extends CircuitAdmin
{
    protected function configureShowField(ShowMapper $showMapper)
    {
        parent::configureShowField($showMapper);
        $showMapper
            ->add('connectivityType.name', 'trans')
            ->add('number')
            ->add('paidByPsd')
            ->add('bandwidth', 'trans')
            ->add('realspeed', 'trans')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('connectivityType', null, array('disabled' => !$this->circuitNoLease($this->getSubject()), 'query_builder' => $this->circuitNoLease($this->getSubject()) ? $this->getAllowedConnectivityTypes() : null, 'help' => ($this->circuitNoLease($this->getSubject())? '&nbsp;Επιτρέπονται μόνο τύποι που δεν εμπεριέχουν μίσθωση για το ΠΣΔ. <BR />&nbsp;Για άλλους τύπους πρέπει να δημιουργηθεί Αίτημα Νέου Κυκλώματος.' : '')))
            ->add('number')
            ->add('paidByPsd', null, array('required' => false, 'disabled' => !$this->circuitNoLease($this->getSubject())))
            ->add('bandwidth', null, array('disabled' => !$this->circuitNoLease($this->getSubject())))
            ->add('realspeed')
        ;
    }

    private function getAllowedConnectivityTypes() {
        $ctRepository = $this->getModelManager()->getEntityManager('Psdtg\SiteBundle\Entity\Circuits\ConnectivityType')->getRepository('Psdtg\SiteBundle\Entity\Circuits\ConnectivityType');
        return $ctRepository->getConnectivityTypesQb(array('noLease' => true));
    }

    protected function configureListFields(ListMapper $listMapper) {
        parent::configureListFields($listMapper);
        $listMapper
            ->add('connectivityType.name', 'trans')
            ->add('number')
            ->add('bandwidth', 'trans')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);
        $datagridMapper
        ;
    }

    public function getExportFields()
    {
        return array_merge(parent::getExportFields(),array(
            //'connectivityType.name',
            'number',
            'paidByPsd',
            'bandwidth',
            'realspeed',
        ));
    }
}