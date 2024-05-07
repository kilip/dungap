<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\State\Listener;

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\State\StateInterface;
use Dungap\Contracts\State\StateRepositoryInterface;
use Dungap\State\Event\StateUpdatedEvent;
use Dungap\State\Listener\StateUpdatedListener;
use Dungap\State\StateException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class StateUpdatedListenerTest extends TestCase
{
    private MockObject|StateRepositoryInterface $states;
    private MockObject|StateInterface $state;
    private MockObject|EventDispatcherInterface $dispatcher;

    private StateUpdatedEvent $event;
    private StateUpdatedListener $listener;

    public function setUp(): void
    {
        $this->states = $this->createMock(StateRepositoryInterface::class);
        $this->state = $this->createMock(StateInterface::class);
        $node = $this->createMock(NodeInterface::class);
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->listener = new StateUpdatedListener($this->states, $this->dispatcher);

        $node->method('getId')
            ->willReturn(Uuid::v7());

        $this->event = new StateUpdatedEvent(
            $node,
            'online',
            'online'
        );
    }

    public function testWithLastStateNull(): void
    {
        $this->states->expects($this->once())
            ->method('findLatest')
            ->willReturn(null);

        $this->states->expects($this->once())
            ->method('create')
            ->willReturn($this->state);

        $this->states->expects($this->once())
            ->method('save')
            ->with($this->state)
        ;

        $this->listener->__invoke($this->event);
    }

    public function testWithStateChanged(): void
    {
        $this->states->expects($this->once())
            ->method('findLatest')
            ->willReturn($this->state);

        $this->state->expects($this->once())
            ->method('getState')
            ->willReturn('offline');

        $this->states->expects($this->once())
            ->method('create')
            ->willReturn($this->state);

        $this->states->expects($this->once())
            ->method('save')
            ->with($this->state)
        ;

        $this->listener->__invoke($this->event);
    }

    public function testWithError(): void
    {
        $this->states->expects($this->once())
            ->method('findLatest')
            ->willReturn(null);

        $this->states->expects($this->once())
            ->method('create')
            ->willReturn($this->state);

        $this->states->expects($this->once())
            ->method('save')
            ->with($this->state)
            ->willThrowException(new \Exception('test'));

        $this->expectException(StateException::class);
        $this->listener->__invoke($this->event);
    }
}
