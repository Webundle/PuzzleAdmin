<?php

namespace Puzzle\Admin\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PuzzleAdminExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        $container->setParameter('puzzle_admin', $config);
        $container->setParameter('puzzle_admin.navigation', $config['navigation']);
        $container->setParameter('puzzle_admin.website.name', $config['website']['name']);
        $container->setParameter('puzzle_admin.website.description', $config['website']['description']);
        $container->setParameter('puzzle_admin.website.email', $config['website']['email']);
        
        $container->setParameter('puzzle_admin.website.time_format', $config['website']['time_format']);
        $container->setParameter('puzzle_admin.website.date_format', $config['website']['date_format']);
        
        $container->setParameter('puzzle_admin.template_bundle', $config['template_bundle']);
        
        $container->setParameter('puzzle_admin.modules_availables', $config['modules_availables']);
    }
}
