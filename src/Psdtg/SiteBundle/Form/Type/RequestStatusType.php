<?php
namespace Psdtg\SiteBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;

class RequestStatusType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'class' => null,
            'choices' => function (Options $options) {
                $class = $options['class'];
                return $class::getStatuses();
            }
        ));
    }

    public function getParent() {
        return 'choice';
    }

    public function getName()
    {
        return 'requeststatus';
    }
}
