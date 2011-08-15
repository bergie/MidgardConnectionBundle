<?php

namespace Midgard\ConnectionBundle\Security\Firewall;

use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Midgard\ConnectionBundle\Security\Authentication\Token;

/*
 * The listener is responsible for fielding requests to the firewall 
 * and calling the authentication provider. 
 * A listener must be an instance of ListenerInterface. 
 * A security listener should handle the GetResponseEvent event, 
 * and set an authenticated token in the security context if successful.
 */ 
class AuthenticationListener implements ListenerInterface
{
    protected $securityContext;
    protected $authenticationManager;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
    }

    private function setResponse($event, $code)
    {
        $response = new Response();
        $response->setStatusCode($code);
        $event->setResponse($response);
    }

    public function handle(GetResponseEvent $event)
    {
        /* From request get username and password. Then, create proper token */
        $request = $event->getRequest();
        $token = new \Midgard\ConnectionBundle\Security\Authentication\Token\UserToken();

        /* Get MidgardUser from storage.
         * AuthenticationProvider will check password and invoke login() */
        try
        {
            $midgardUser = new \midgard_user(
                array (
                    'login' => $request->request->get('_username'), 
                    'authtype' => 'Plaintext') /* FIXME, authentication type shoudl come from configuration */
                ); 
            $token->setMidgardUser($midgardUser);
            $token->setPassword($request->request->get('_password'));
        }
        catch (\midgard_error_exception $e)
        {
            if (\midgard_connection::get_instance()->get_error() == MGD_ERR_NOT_EXISTS)
            {
                $this->setResponse($event, 403);
                return;
            }
            /* TODO
             * Handle more errors */
        }

        /* Via AuthenticationManager we authenticate using Midgard Authentication provider */
        try 
        { 
            $returnValue = $this->authenticationManager->authenticate($token);
            if ($returnValue instanceof TokenInterface) {
                return $this->securityContext->setToken($returnValue);
            } else if ($returnValue instanceof Response) {
                return $event->setResponse($returnValue);
            }
        } 
        catch (AuthenticationException $e)
        {
            /* Log failed attempt */
        }

        $this->setResponse($event, 403);
    }

    protected function attemptAuthentication(Request $request)
    {
        return $this->authenticationManager->authenticate();
    }
}

?>
