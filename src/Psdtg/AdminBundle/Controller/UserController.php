<?php

namespace Kp\AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends CRUDController {
    /*public function approveAction($id) {
        $user = $this->get('doctrine')->getRepository('KpUserBundle:User')->find($id);

        $this->get('kp.userservice')->approveCook($user);
        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function removeApprovalAction($id) {
        $user = $this->get('doctrine')->getRepository('KpUserBundle:User')->find($id);

        $user->removeRole("ROLE_COOK");
        $user->setIsCook(0);

        $em = $this->container->get('doctrine')->getEntityManager();
        $em->persist($user);
        $em->flush();

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function waitingListAction($id) {
        $user = $this->get('doctrine')->getRepository('KpUserBundle:User')->find($id);

        $user->setUnderReviewWaitingList(1);

        $em = $this->container->get('doctrine')->getEntityManager();
        $em->persist($user);
        $em->flush();

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function removewaitingListAction($id) {
        $user = $this->get('doctrine')->getRepository('KpUserBundle:User')->find($id);

        $user->setUnderReviewWaitingList(0);

        $em = $this->container->get('doctrine')->getEntityManager();
        $em->persist($user);
        $em->flush();

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function verifyAction($id) {
        $user = $this->get('doctrine')->getRepository('KpUserBundle:User')->find($id);
        $user->setVerified(1);

        $em = $this->container->get('doctrine')->getEntityManager();
        $em->persist($user);
        $em->flush();

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function notVerifiedAction($id) {
        $user = $this->get('doctrine')->getRepository('KpUserBundle:User')->find($id);
        $user->setVerified(0);

        $em = $this->container->get('doctrine')->getEntityManager();
        $em->persist($user);
        $em->flush();

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }*/
}
