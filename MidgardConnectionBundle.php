<?php
namespace Midgard\ConnectionBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MidgardConnectionBundle extends Bundle
{
    public function boot()
    {
        $connection = \midgard_connection::get_instance();
        if ($connection->is_connected())
        {
            return;
        } 

        $setup = $this->container->getParameter('midgard.connection.config');
        if (!$setup) {
            throw new \RuntimeException('Failed to open Midgard2 connection: no configuration defined');
        }

        $config = new \midgard_config();
        $config->dbtype = $setup['type'];
        $config->database = $setup['name'];
        $config->dbdir = $setup['databasedir'];
        $config->logfilename = $setup['logfile'];
        $config->loglevel = $setup['loglevel'];
        $config->blobdir = $setup['blobdir'];
        $config->sharedir = $setup['sharedir'];

        if (!$connection->open_config($config)) {
            throw new \RuntimeException('Failed to open Midgard2 connection: ' . $connection->get_error_string());
        }
    }
}
