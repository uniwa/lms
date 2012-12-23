<?php

namespace Psdtg\UserBundle\Extension;

use FR3D\LdapBundle\Ldap\LdapManager as BaseLdapManager;
use Symfony\Component\Security\Core\User\UserInterface;

class LdapManager extends BaseLdapManager
{
    protected $driver;
    protected $container;
    protected $params;

    public function __construct(\FR3D\LdapBundle\Driver\LdapDriverInterface $driver, $userManager, array $params, $container) {
        $this->driver = $driver;
        $this->params = $params;
        $this->container = $container;
        parent::__construct($driver, $userManager, $params);
    }

    protected function hydrate(UserInterface $user, array $entry)
    {
        parent::hydrate($user, $entry);
        $em = $this->container->get('doctrine')->getEntityManager();
        $em->getConnection()->executeQuery('DELETE FROM Users WHERE username = "'.$entry['uid'][0].'"');
        // If the user is has the eduPerson objectClass then they get ROLE_USER
        $user->setRoles(array('ROLE_KEDO'));
    }
}