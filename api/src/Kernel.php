<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap;

use Dungap\Core\DI\CoreExtension;
use Dungap\Node\DI\NodeExtension;
use Dungap\Service\DI\ServiceExtension;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function prepareContainer(ContainerBuilder $container): void
    {
        parent::prepareContainer($container);
        $container->registerExtension(new CoreExtension());
        $container->registerExtension(new NodeExtension());
        $container->registerExtension(new ServiceExtension());
    }
}
