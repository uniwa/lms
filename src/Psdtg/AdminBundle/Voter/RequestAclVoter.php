<?php
namespace Psdtg\AdminBundle\Voter;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Acl\Voter\AclVoter;

use Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest;
use Psdtg\SiteBundle\Entity\Requests\Request;
use Psdtg\AdminBundle\Admin\RequestAdmin;

class RequestAclVoter extends AclVoter
{
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if(($user = $token->getUser()) instanceof UserInterface) {
            // Kedo user should not be able to create requests or edit requests are are INSTALLED or APPROVED
            if($user->hasRole('ROLE_KEDO')) {
                foreach ($attributes as $attribute) {
                    if(($object instanceof Request || $object instanceof RequestAdmin) && ($attribute == 'CREATE')) {
                        return self::ACCESS_DENIED;
                    }
                    if($attribute == 'EDIT' || $attribute == 'DELETE') {
                        if($object instanceof Request && !($object instanceof NewCircuitRequest) && $object->getStatus() == Request::STATUS_APPROVED) {
                            return self::ACCESS_DENIED;
                        } else if($object instanceof NewCircuitRequest && $object->getStatus() == NewCircuitRequest::STATUS_INSTALLED) {
                            return self::ACCESS_DENIED;
                        }
                    }
                }
            }
            // Helpdesk user should not be able to edit requests that aren't PENDING
            if($user->hasRole('ROLE_HELPDESK')) {
                foreach ($attributes as $attribute) {
                    if($object instanceof Request && ($attribute == 'EDIT' || $attribute == 'DELETE')) {
                        if($object->getStatus() !== Request::STATUS_PENDING) {
                            return self::ACCESS_DENIED;
                        }
                    }
                }
            }
        }
        return self::ACCESS_ABSTAIN;
    }
}