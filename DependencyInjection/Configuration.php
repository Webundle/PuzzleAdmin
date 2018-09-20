<?php

namespace Puzzle\Admin\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('puzzle_admin');
		
        $rootNode
            ->children()
                ->scalarNode('template_bundle')->defaultValue('@PuzzleAdmin')->end()
                ->scalarNode('modules_availables')->defaultValue('@PuzzleAdmin')->end()
                ->arrayNode('website')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultNull()->end()
                        ->scalarNode('description')->defaultNull()->end()
                        ->scalarNode('email')->defaultNull()->end()
                        ->scalarNode('time_format')->defaultNull()->end()
                        ->scalarNode('date_format')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;
        
        return $treeBuilder;
    }
}
