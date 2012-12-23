<?php
namespace Psdtg\AdminBundle\Admin;

use Psdtg\SiteBundle\Entity\TelephoneLine;
use Sonata\AdminBundle\Form\FormMapper;

class NewLineRequestAdmin extends RequestAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $subject = $this->getSubject();
        $formMapper
            ->add('lineType', 'choice', array('disabled' => $subject->getLine() != null ? true: false, 'choices' => TelephoneLine::getLineTypes()))
        ;
    }
}