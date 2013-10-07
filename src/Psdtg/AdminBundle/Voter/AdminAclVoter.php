<?php
namespace Psdtg\AdminBundle\Voter;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Acl\Voter\AclVoter;

use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Model\ObjectIdentityInterface;
use Symfony\Component\Security\Acl\Permission\PermissionMapInterface;
use Symfony\Component\Security\Acl\Model\SecurityIdentityRetrievalStrategyInterface;
use Symfony\Component\Security\Acl\Model\ObjectIdentityRetrievalStrategyInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AdminAclVoter extends AclVoter
{
    private $objectIdentityRetrievalStrategy;

    public function __construct(AclProviderInterface $aclProvider, ObjectIdentityRetrievalStrategyInterface $oidRetrievalStrategy, SecurityIdentityRetrievalStrategyInterface $sidRetrievalStrategy, PermissionMapInterface $permissionMap, LoggerInterface $logger = null, $allowIfObjectIdentityUnavailable = true)
    {
        $this->objectIdentityRetrievalStrategy = $oidRetrievalStrategy;
        parent::__construct($aclProvider, $oidRetrievalStrategy, $sidRetrievalStrategy, $permissionMap, $logger, $allowIfObjectIdentityUnavailable);
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        // Login
        if (null === $object) {
            return self::ACCESS_GRANTED;
        } elseif ($object instanceof FieldVote) {
            $field = $object->getField();
            $object = $object->getDomainObject();
        } else {
            $field = null;
        }

        if ($object instanceof ObjectIdentityInterface) {
            $oid = $object;
        } elseif (null === $oid = $this->objectIdentityRetrievalStrategy->getObjectIdentity($object)) {
            return self::ACCESS_GRANTED;
        }

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