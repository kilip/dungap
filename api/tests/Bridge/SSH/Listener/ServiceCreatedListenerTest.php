<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\SSH\Listener;

use Dungap\Bridge\SSH\Listener\ServiceCreatedListener;
use Dungap\Contracts\Node\FeatureInterface;
use Dungap\Contracts\Node\FeatureRepositoryInterface;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Dungap;
use Dungap\Service\Entity\Service;
use Dungap\Service\Event\ServiceCreatedEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ServiceCreatedListenerTest extends TestCase
{
    public function testInvoke(): void
    {
        $feature = $this->createMock(FeatureInterface::class);
        $features = $this->createMock(FeatureRepositoryInterface::class);
        $service = $this->createMock(ServiceInterface::class);
        $node = $this->createMock(NodeInterface::class);
        $event = new ServiceCreatedEvent($service);

        $listener = new ServiceCreatedListener(
            $features,
            Dungap::SshDriver,
            Dungap::SshDriver
        );

        $node->method('getId')->willReturn(Uuid::v7());
        $service->method('getNode')->willReturn($node);
        $service->method('getPort')
            ->willReturn(443, 22);

        $features->method('findByFeature')
            ->willReturn(null);
        $features->method('create')
            ->willReturn($feature);

        $features->expects($this->exactly(2))
            ->method('save')
            ->with($feature);

        // service port 443
        $listener($event);

        // service port 22
        $listener($event);
    }
}
