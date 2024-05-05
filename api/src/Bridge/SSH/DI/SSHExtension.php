<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH\DI;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\AbstractExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

class SSHExtension extends AbstractExtension
{
    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(__DIR__.'/../Resources/config/');
    }

    public function getAlias(): string
    {
        return 'ssh';
    }
}
