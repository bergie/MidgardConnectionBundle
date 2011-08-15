<?php

namespace Midgard\ConnectionBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class MidgardSecurityFactory implements SecurityFactoryInterface
{
    public function __construct()
    { 
        //$this->addOption('display', 'page');
    }

    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.midgard.'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('security.authentication.provider.midgard'))
            ->replaceArgument(0, new Reference($userProvider))
            ;

        $listenerId = 'security.authentication.listener.midgard.'.$id;
        $listener = $container->setDefinition($listenerId, new DefinitionDecorator('security.authentication.listener.midgard'));

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    { 
        return 'pre_auth';
    }

    public function getKey()
    { 
        return 'midgard';
    }

    protected function getListenerId()
    { 
        return 'security.authentication.listener.midgard';
    }

    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    { 
        // with user provider
        if (isset($config['provider'])) {
            $authProviderId = 'midgard.auth.'.$id;

            $container
                ->setDefinition($authProviderId, new DefinitionDecorator('midgard.auth'))
                ->addArgument(new Reference($userProviderId))
                ->addArgument(new Reference('security.user_checker'))
            ;

            return $authProviderId;
        }

        // without user provider
        return 'midgard.auth';
    }

    protected function createEntryPoint($container, $id, $config, $defaultEntryPointId)
    { 
        $entryPointId = 'security.authentication.entry_point.midgard'.$id;
        $container
            ->setDefinition($entryPointId, new DefinitionDecorator('security.authentication.entry_point.midgard'))
            ->replaceArgument(1, $config)
        ;

        // set options to container for use by other classes
        $container->setParameter('midgard.options.'.$id, $config);

        return $entryPointId;
    }

    public function addConfiguration(NodeDefinition $node)
    {
    
    }
}
