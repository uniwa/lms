<?php
namespace Psdtg\AdminBundle\Admin;

use Psdtg\SiteBundle\Entity\Services\ADSL;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class ADSLAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('profile', 'choice', array('choices' => ADSL::getProfiles(), 'required' => true))
            ->add('realspeed')
        ;
    }
}