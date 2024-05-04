<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Core\DI;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\AbstractExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

class CoreExtension extends AbstractExtension
{
    /**
     * @param array<string,mixed> $config
     */
    public function getConfiguration(array $config, ContainerBuilder $container): ?ConfigurationInterface
    {
        return new Configuration();
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->setParameter(
            'dungap.core.cache_dir',
            $builder->getParameter('kernel.cache_dir').'/dungap'
        );

        $projectDir = $builder->getParameter('kernel.project_dir');
        $configDir = $builder->getParameter('dungap.config_dir');
        $configDir = str_replace('%kernel.project_dir%', $projectDir, $configDir);
        if (is_dir(realpath($configDir))) {
            $container->import($configDir.'/');
        }
    }

    public function getAlias(): string
    {
        return 'core';
    }
}
