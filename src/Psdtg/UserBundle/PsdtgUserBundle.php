<?php

namespace Psdtg\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PsdtgUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
