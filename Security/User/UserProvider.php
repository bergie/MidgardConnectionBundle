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
        $mgdUser = $this->getMidgardUser($username);
        if (!$mgdUser) {
            throw new UsernameNotFoundException("User {$username} not found");
        }
        $user = new User($username);
        $user->setMidgardUser($mgdUser);
        return $user;
    }

    private function getMidgardUser($username)
    {
        $tokens = array(
            'login' => $username,
            'active' => true,
            // TODO: Match this to the Encoder set for Midgard user objects in SF2 config
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
        $user->setMidgardUser($this->getMidgardUser($user->getUsername()));
        return $user;
    }

    public function supportsClass($class)
    {
        return $class === 'Midgard\ConnectionBundle\Security\User\User';
    }
}
