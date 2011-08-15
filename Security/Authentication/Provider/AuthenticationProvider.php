<?php

namespace Midgard\ConnectionBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Midgard\ConnectionBundle\Security\Authentication\Token;

class AuthenticationProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;

    public function __construct ()
    {
        //$this->userProvider = $userProvider;
        //$this->cacheDir     = $cacheDir;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof \Midgard\ConnectionBundle\Security\Authentication\Token\UserToken;
    }

    public function authenticate(TokenInterface $token)
    {
        //$user = $this->userProvider->loadUserByUsername($token->getUsername());
        
        /* From token get midgardUser and password.
         * Depending on authentication type, check is they match */ 
        $midgardUser = $token->getMidgardUser();
        $password = $token->getPassword();

        /* TODO, handle different authentication types */
        if ($midgardUser->password != $password)
        {
            throw new AuthenticationException($token->getUsername() . ' : authentication failed.');
        }

        if ($midgardUser)
        {
            $authenticatedToken = new \Midgard\ConnectionBundle\Security\Authentication\Token\UserToken();
            //$authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException($token->getUsername() . ' : authentication failed.');
    }
}
