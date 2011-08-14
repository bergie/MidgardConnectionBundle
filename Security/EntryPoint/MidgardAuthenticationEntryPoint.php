<?php

namespace Midgard\ConnectionBundle\Security\EntryPoint;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class FacebookAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct()
    {
        throw new \Exception ("Auth entry point");
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $response = new RedirectResponse($this->facebook->getLoginUrl(
           array(
                'display' => $this->options->get('display', 'page'),
                'scope' => implode(',', $this->permissions),
                'redirect_uri' => $request->getUriForPath($this->options->get('check_path', '')),
            ))
        );

        return $response;
    }
}
