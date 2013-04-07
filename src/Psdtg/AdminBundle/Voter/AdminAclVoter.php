<?php
namespace Psdtg\AdminBundle\Voter;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Acl\Voter\AclVoter;

class AdminAclVoter extends AclVoter
{
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if(($user = $token->getUser()) instanceof UserInterface) {
            if($user->hasRole('ROLE_HELPDESK')) {
                foreach ($attributes as $attribute) {
                    $class = get_class($object);
                    if(strpos($class, 'Admin') === false || strpos($class, 'Helpdesk') !== false) {
                        return self::ACCESS_GRANTED;
                    }
                }
            } else if($user->hasRole('ROLE_KEDO')) {
                foreach ($attributes as $attribute) {
                    $class = get_class($object);
                    if(strpos($class, 'Admin') === false || strpos($class, 'Kedo') !== false) {
                        return self::ACCESS_GRANTED;
                    }
                }
            }
        }
        //return self::ACCESS_ABSTAIN;
        return self::ACCESS_DENIED;
    }
}