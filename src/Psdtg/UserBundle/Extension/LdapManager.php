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
        $kedo = false;
        if(isset($entry['memberof'])) {
            $groups = explode(';', $entry['memberof'][0]);
            if(in_array('lms', $groups)) {
                $kedo = true;
            }
        }
        if($kedo == true) {
            $user->setRoles(array('ROLE_KEDO'));
        } else {
            $user->setRoles(array('ROLE_HELPDESK'));
        }
        // Set the unit
        $mmservice = $this->container->get('psdtg.mm.service');
        $units = $mmservice->findBy(array(
            'ldapuid' => $entry['uid'][0],
        ));
        if(count($units) > 0) {
            $user->setUnit($units[0]);
        }
    }
}