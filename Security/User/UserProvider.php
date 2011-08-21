<?php
namespace Midgard\ConnectionBundle\Security\User;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        $user = $this->getMidgardUser($username);
        if (!$user) {
            throw new UsernameNotFoundException("User {$username} not found");
        }
        return new User($user);
    }

    private function getMidgardUser($username)
    {
        $tokens = array(
            'login' => $username,
            'active' => true,
            'authtype' => 'Plaintext',
        );

        try {
            return new \midgard_user($tokens);
        } catch (\midgard_error_exception $e) {
            return null;
        }
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Midgard\ConnectionBundle\Security\User\User';
    }
}
