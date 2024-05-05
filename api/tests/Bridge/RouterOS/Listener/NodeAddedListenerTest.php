<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\RouterOS\Listener;

use Dungap\Bridge\RouterOS\Listener\NodeAddedListener;
use Dungap\Contracts\Node\FeatureInterface;
use Dungap\Contracts\Node\FeatureRepositoryInterface;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Dungap;
use Dungap\Node\Event\NodeAddedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class NodeAddedListenerTest extends TestCase
{
    private MockObject|FeatureRepositoryInterface $features;
    private MockObject|FeatureInterface $feature;
    private NodeAddedEvent $event;
    private NodeAddedListener $listener;

    protected function setUp(): void
    {
        $this->features = $this->createMock(FeatureRepositoryInterface::class);
        $this->feature = $this->createMock(FeatureInterface::class);
        $node = $this->createMock(NodeInterface::class);
        $this->listener = new NodeAddedListener(
            $this->features,
            Dungap::RouterOsDriver
        );

        $this->event = new NodeAddedEvent($node);

        $this->features->method('findByFeature')
            ->willReturn(null);

        $this->features->method('create')
            ->willReturn($this->feature);

        $node->method('getId')
            ->willReturn(Uuid::v7());
    }

    public function testWithNonRouterOSDriver(): void
    {
        $listener = new NodeAddedListener(
            $this->features,
            Dungap::EtherWakeDriver
        );

        $this->features->expects($this->never())
            ->method('findByFeature');

        $listener($this->event);
    }

    public function testInvoke(): void
    {
        $this->features->expects($this->once())
            ->method('save')
            ->with($this->feature);

        $this->listener->__invoke($this->event);
    }
}
