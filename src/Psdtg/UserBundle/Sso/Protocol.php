<?php

namespace Psdtg\UserBundle\Sso;

use BeSimple\SsoAuthBundle\Sso\Cas\Protocol as BaseProtocol;

use BeSimple\SsoAuthBundle\Exception\InvalidConfigurationException;
use Buzz\Message\Request as BuzzRequest;
use Buzz\Message\Response as BuzzResponse;
use Buzz\Client\ClientInterface;

class Protocol extends BaseProtocol
{
    public function executeValidation(ClientInterface $client, BuzzRequest $request, $credentials)
    {
        return new PhpCasValidation(new BuzzResponse(), $credentials);
    }

}
