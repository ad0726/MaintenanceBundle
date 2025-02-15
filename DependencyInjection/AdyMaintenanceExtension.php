<?php

namespace Ady\Bundle\MaintenanceBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @author  Gilles Gauthier <g.gauthier@lexik.fr>
 */
class AdyMaintenanceExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        if (isset($config['driver']['ttl'])) {
            $config['driver']['options']['ttl'] = $config['driver']['ttl'];
        }

        $container->setParameter('ady_maintenance.driver', $config['driver']);

        $container->setParameter('ady_maintenance.authorized.path', $config['authorized']['path']);
        $container->setParameter('ady_maintenance.authorized.host', $config['authorized']['host']);
        $container->setParameter('ady_maintenance.authorized.ips', $config['authorized']['ips']);
        $container->setParameter('ady_maintenance.authorized.query', $config['authorized']['query']);
        $container->setParameter('ady_maintenance.authorized.cookie', $config['authorized']['cookie']);
        $container->setParameter('ady_maintenance.authorized.route', $config['authorized']['route']);
        $container->setParameter('ady_maintenance.authorized.attributes', $config['authorized']['attributes']);
        $container->setParameter('ady_maintenance.response.http_code', $config['response']['code']);
        $container->setParameter('ady_maintenance.response.http_status', $config['response']['status']);
        $container->setParameter('ady_maintenance.response.exception_message', $config['response']['exception_message']);

        if (isset($config['driver']['options']['dsn'])) {
            $this->registerDsnconfiguration($config['driver']['options']);
        }
    }

    /**
     * Load dsn configuration.
     *
     * @param array $options A configuration array
     *
     * @throws InvalidArgumentException
     */
    protected function registerDsnConfiguration(array $options): void
    {
        if (!isset($options['table'])) {
            throw new InvalidArgumentException('You need to define table for dsn use');
        }

        if (!isset($options['user'])) {
            throw new InvalidArgumentException('You need to define user for dsn use');
        }

        if (!isset($options['password'])) {
            throw new InvalidArgumentException('You need to define password for dsn use');
        }
    }
}
