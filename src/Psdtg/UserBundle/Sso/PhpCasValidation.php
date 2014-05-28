<?php

namespace Psdtg\UserBundle\Sso;

use BeSimple\SsoAuthBundle\Sso\AbstractValidation;
use BeSimple\SsoAuthBundle\Sso\ValidationInterface;
use Buzz\Message\Response;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class PhpCasValidation extends AbstractValidation implements ValidationInterface
{
    /**
     * {@inheritdoc}
     */
    protected function validateResponse(Response $response)
    {
        \phpCAS::client(SAML_VERSION_1_1,"sso-test.sch.gr",443,'',false);
        \phpCAS::setNoCasServerValidation();
        \phpCAS::handleLogoutRequests(array("sso-test.sch.gr"));
        \phpCAS::setNoClearTicketsFromUrl();
        $success = true;
        if(!\phpCAS::checkAuthentication()) {
            $success = false;
        }

        if ($success) {
            $this->username = \phpCAS::getUser();
            $this->attributes = \phpCAS::getAttributes();
        } else {
            $this->error = error_get_last();
        }

        return $success;
    }
}
