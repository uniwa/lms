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

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

class CategoryType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'configs' => array(
                'path' => 'get_categories',
                'field_id' => 'name',
                'field_name' => 'name',
            ),
            'class' => null,
        ));
    }

    public function getParent() {
        return 'genemu_jqueryselect2_hidden';
    }

    public function getName()
    {
        return 'mmcategory';
    }
}
