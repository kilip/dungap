<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Node\Listener;

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\State\StateInterface;
use Dungap\Contracts\State\StateRepositoryInterface;
use Dungap\Node\Listener\NodeEntityListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @covers \Dungap\Node\Listener\NodeEntityListener
 * @covers \Dungap\Node\Entity\Node
 */
class NodeEntityListenerTest extends TestCase
{
    private MockObject|StateRepositoryInterface $states;
    private MockObject|StateInterface $state;
    private MockObject|NodeInterface $node;
    private NodeEntityListener $listener;

    protected function setUp(): void
    {
        $this->states = $this->createMock(StateRepositoryInterface::class);
        $this->state = $this->createMock(StateInterface::class);
        $this->node = $this->createMock(NodeInterface::class);
        $this->listener = new NodeEntityListener(
            $this->states
        );
        $this->states->method('findLatest')
            ->willReturn($this->state);
        $this->node->method('getId')
            ->willReturn(Uuid::v7());
    }

    public function testPostLoad(): void
    {
        $this->state->expects($this->exactly(2))
            ->method('getState')
            ->willReturn('online', '500');
        $this->node->expects($this->once())
            ->method('setOnline')
            ->with(true);

        $this->node->expects($this->once())
            ->method('isOnline')
            ->willReturn(true);

        $this->node->expects($this->once())
            ->method('setLatency')
            ->with(floatval(500));

        $this->listener->postLoad($this->node);
    }
}
