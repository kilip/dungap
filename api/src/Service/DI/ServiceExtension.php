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

use Dungap\Contracts\Service\ServiceScannerInterface;
use Dungap\Service\Service\ServiceScanner;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\AbstractExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class ServiceExtension extends ConfigurableExtension
{
    /**
     * @param array<string,mixed> $mergedConfig
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->setParameter('dungap.service.scanner.configs', $mergedConfig['scanner']);
    }

    public function getAlias(): string
    {
        return 'service';
    }
}
