<?php

namespace Psdtg\AdminBundle\Controller;

use BeSimple\SsoAuthBundle\Sso\Manager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AuthController extends Controller {
    public function loginAction(Manager $manager, Request $request, AuthenticationException $exception = null) {
        /*if(isset($exception)) {
            var_dump($exception);
            die();
        }*/
        return new RedirectResponse($manager->getServer()->getLoginUrl());
    }

    public function logoutAction(Manager $manager, Request $request) {
        return new RedirectResponse(strtok($manager->getServer()->getLogoutUrl(), '?'));
    }

    public function debugAction() {
        $request = $this->getRequest();
        $session = $request->getSession();

        if($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        var_dump($error); die();

        //return $this->render('Lille3TestBundle:Default:security.html.twig',array('error'=>$error));
    }
}