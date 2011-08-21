<?php
namespace Midgard\ConnectionBundle\Security\User;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private $user = null;
    private $login = '';

    public function __construct(\midgard_user $user)
    {
        $this->user = $user;
        $this->login = $user->login;
    }

    public function getRoles()
    {
        $roles = array('ROLE_USER');
        
        if ($this->user->is_admin()) {
            $roles[] = 'ROLE_ADMIN';
        }
        // TODO: Check userlevel, possible group memberships

        return $roles;
    }

    public function getPassword()
    {
        return $this->user->password;
    }

    public function getSalt()
    {
        return '';
    }

    public function getUsername()
    {
        if (!$this->user) {
            return $this->login;
        }
        return $this->user->login;
    }

    public function eraseCredentials()
    {
        $this->user = null;
    }

    public function getMidgardUser()
    {
        return $this->user;
    }

    public function equals(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if (!$this->user) {
            if ($this->login == $user->getUsername()) {
                return true;
            }
            return false;
        }

        if ($this->user->guid == $user->getMidgardUser()->guid) {
            return true;
        }

        return false;
    }
}
