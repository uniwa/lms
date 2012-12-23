<?php

namespace Kp\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KpUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
