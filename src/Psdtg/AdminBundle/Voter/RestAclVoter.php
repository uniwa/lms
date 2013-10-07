<?php
namespace Psdtg\AdminBundle\Voter;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Acl\Voter\AclVoter;

use Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit;

class RestAclVoter extends AclVoter
{
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if(($user = $token->getUser()) instanceof UserInterface) {
            // User should not be able to edit objects with
            if($user->hasRole('ROLE_HELPDESK')) {
                foreach ($attributes as $attribute) {
                    if($object instanceof PhoneCircuit && ($attribute == 'EDIT' || $attribute == 'DELETE')) {
                        if($object->getConnectivityType()->getNoLease() == true) {
                            return self::ACCESS_GRANTED;
                        } else {
                            return self::ACCESS_DENIED;
                        }
                    }
                }
            }
        }
        return self::ACCESS_ABSTAIN;
    }
}