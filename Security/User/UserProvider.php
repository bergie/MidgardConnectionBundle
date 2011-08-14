<?php
namespace Midgard\ConnectionBundle\Security\User;


use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider implements UserProviderInterface
{

    public function __construct($username = null)
    {
        //throw new \Exception ("User provider $username");
    }

    public function loadUserByUsername($username)
    {
        //throw new \Exception ("loadUserByName $username");
    }

    public function refreshUser(UserInterface $user)
    {
        //throw new \Exception ("refreshUser");
    }

    public function supportsClass($class)
    {
        //throw new \Exception ("supportClass");
    }
}
