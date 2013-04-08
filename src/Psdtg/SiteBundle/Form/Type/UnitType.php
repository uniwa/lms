<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psdtg\SiteBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

use Psdtg\SiteBundle\Form\DataTransformer\UnitToMmIdTransformer;

class UnitType extends AbstractType
{
    protected $unitToMmIdTransformer;

    public function __construct(UnitToMmIdTransformer $unitToMmIdTransformer) {
        $this->unitToMmIdTransformer = $unitToMmIdTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mmId', 'genemu_jqueryselect2_entity', array(
                'class' => 'Psdtg\SiteBundle\Entity\Unit',
                'property' => 'mmId',
                'required' => true,
            ))
        ;
        $builder->addModelTransformer($this->unitToMmIdTransformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Psdtg\SiteBundle\Entity\Unit',
            'class' => 'Psdtg\SiteBundle\Entity\Unit',
        ));
    }

    public function getName()
    {
        return 'mmunit';
    }
}
