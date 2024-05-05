<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Node\DI;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('node');
        $rootNode = $builder->getRootNode();

        $this->addHostsSection($rootNode);
        $this->addDefaultDriverSection($rootNode);

        return $builder;
    }

    private function addHostsSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('hosts')
                    ->defaultValue([])
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('ip')->defaultNull()->end()
                            ->scalarNode('hostname')->defaultNull()->end()
                            ->scalarNode('mac')->defaultNull()->end()
                            ->scalarNode('note')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addDefaultDriverSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('default_processor')
                    ->children()
                        ->scalarNode('power_on')->defaultValue('etherwake')->end()
                        ->scalarNode('power_off')->defaultValue('ssh')->end()
                        ->scalarNode('reboot')->defaultValue('ssh')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
