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
        $this->configureDefaultProcessor($container, $mergedConfig['default_processor']);
    }

    public function getAlias(): string
    {
        return 'node';
    }

    /**
     * @param array<string,string> $config
     */
    private function configureDefaultProcessor(ContainerBuilder $container, array $config): void
    {
        $container->setParameter('dungap.node.processor.power_on', $config['power_on']);
        $container->setParameter('dungap.node.processor.power_off', $config['power_off']);
        $container->setParameter('dungap.node.processor.reboot', $config['reboot']);
    }
}
