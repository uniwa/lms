<?php

namespace Psdtg\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Psdtg\AdminBundle\DependencyInjection\Compiler\DisableVotersPass;

class PsdtgAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DisableVotersPass());
    }
}
