<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Service\DI;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('service');

        $rootNode = $builder->getRootNode();

        $this->addScannerSection($rootNode);

        return $builder;
    }

    private function addScannerSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('scanner')
                    ->useAttributeAsKey('port', false)
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('port')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('timeout')
                                ->defaultValue(500)
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
