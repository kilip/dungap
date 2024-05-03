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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class NodeExtension extends ConfigurableExtension
{
    /**
     * @param array<string,mixed> $mergedConfig
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->setParameter('dungap.node.hosts', $mergedConfig['hosts']);
    }

    public function getAlias(): string
    {
        return 'node';
    }
}
