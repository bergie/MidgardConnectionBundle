<?php
namespace Midgard\ConnectionBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Resource\FileResource;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class MidgardConnectionExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('connection.xml');

        $setup = $configs[0];

        $config = new \midgard_config();
        $config->dbtype = $setup['type'];
        $config->database = $setup['name'];
        $config->dbdir = $setup['databasedir'];
        $config->logfilename = $setup['logfile'];
        $config->loglevel = $setup['loglevel'];
        $config->blobdir = $setup['blobdir'];
        $config->sharedir = $setup['sharedir'];

        $connection = \midgard_connection::get_instance();
        if (!$connection->open_config($config)) {
            throw new \RuntimeException('Failed to open Midgard2 connection: ' . $connection->get_error_string());
        }

        $container->setParameter('midgard.connection.config', $setup);
    }
}
