<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Setting\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Definition implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('dungap');
        $root = $builder->getRootNode();

        $root
            ->children()
                ->arrayNode('devices')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('hostname')
                                ->defaultNull()
                            ->end()
                            ->scalarNode('ip')->defaultNull()->end()
                            ->scalarNode('mac')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
