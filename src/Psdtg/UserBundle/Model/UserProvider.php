<?php
namespace Psdtg\UserBundle\Model;

use FOS\UserBundle\Security\UserProvider as BaseUserProvider;
use BeSimple\SsoAuthBundle\Security\Core\User\UserFactoryInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider extends BaseUserProvider implements UserFactoryInterface
{
    protected $mmService;

    public function setMmService($mmService) {
        $this->mmService = $mmService;
    }

    public function createUser($username, array $roles, array $attributes)
    {
        try {
            $user = $this->userManager->createUser();
            $user->setEmail($username.'@'.$username.'.com');
            $user->setPlainPassword(md5(rand(0, 10000)));
            $user->setPassword(md5(rand(0, 10000)));
            $user->setUsername($username);
            $this->getRoles($user, $attributes);
            $user->setEnabled(true);

            if (!$user instanceof UserInterface) {
                throw new AuthenticationServiceException('The user provider must create an UserInterface object.');
            }
        } catch (\Exception $repositoryProblem) {
            throw new AuthenticationServiceException($repositoryProblem->getMessage(), 0, $repositoryProblem);
        }

        return $user;
    }

    /*public function loadUserByUsername($username)
    {
        $user = parent::loadUserByUsername($username);
        $this->userManager->deleteUser($user);
        throw new UsernameNotFoundException();
    }*/

    protected function getRoles(UserInterface $user, array $entry)
    {
        // If the user is has the eduPerson objectClass then they get ROLE_USER
        $kedo = false;
        if(isset($entry['memberof'])) {
            $groups = explode(';', $entry['memberof']);
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
        /*$unit = $this->mmService->findOneUnitBy(array(
            'ldapuid' => $entry['uid'],
        ));
        if(count($unit) > 0) {
            $user->setUnit($unit);
        } else {
            throw new \Exception('Δεν βρέθηκε η μονάδα (mm_id) του χρήστη');
        }*/
    }
}