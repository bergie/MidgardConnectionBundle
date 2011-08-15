<?php

namespace Midgard\ConnectionBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/* A token represents the user authentication data present in the request. 
 * Once a request is authenticated, the token retains the user's data, 
 * and delivers this data across the security context. */

/**
 * {@inheritdoc}
 */ 
class UserToken extends AbstractToken
{
    public $created;
    public $digest;
    public $nonce;
    private $midgardUser;
    private $password;

    public function __construct($uid = '', array $roles = array())
    {
        parent::__construct($roles);

        $this->setUser($uid);

        if (!empty($uid)) {
            $this->setAuthenticated(true);
        }
    }

    public function getCredentials()
    {
        return '';
    }

    public function setMidgardUser(\midgard_user $user)
    {
        $this->midgardUser = $user;
    }

    public function getMidgardUser()
    {
        return $this->midgardUser;
    }

    public function setPassword($password = '')
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }
}

?>
