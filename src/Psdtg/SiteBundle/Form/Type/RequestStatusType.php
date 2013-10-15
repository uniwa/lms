<?php
namespace Psdtg\SiteBundle\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;

use Psdtg\SiteBundle\Entity\Requests\Request;

class RequestStatusType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['confirmApproved'] = $options['confirmApproved'];
        $view->vars['confirmationValue'] = $options['confirmationValue'];
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'class' => null,
            'choices' => function (Options $options) {
                $class = $options['class'];
                return $class::getStatuses();
            },
            'confirmApproved' => true,
            'confirmationValue' => function(Options $options) {
                $choices = $options['choices'];
                if(isset($choices['ΟΤΕ_PSD_CONTROL'])) {
                    return end($choices['ΟΤΕ_PSD_CONTROL']);
                } else {
                    return Request::STATUS_APPROVED;
                }
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
