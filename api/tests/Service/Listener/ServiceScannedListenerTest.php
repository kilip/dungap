<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Service\Listener;

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Contracts\Service\ServiceReportInterface;
use Dungap\Contracts\Service\ServiceRepositoryInterface;
use Dungap\Service\Event\ServiceScannedEvent;
use Dungap\Service\Listener\ServiceScannedListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ServiceScannedListenerTest extends TestCase
{
    private MockObject|ServiceRepositoryInterface $services;
    private MockObject|ServiceInterface $service;

    private ServiceScannedEvent $event;
    private ServiceScannedListener $listener;

    protected function setUp(): void
    {
        $node = $this->createMock(NodeInterface::class);
        $report = $this->createMock(ServiceReportInterface::class);
        $this->services = $this->createMock(ServiceRepositoryInterface::class);
        $this->service = $this->createMock(ServiceInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->listener = new ServiceScannedListener(
            $this->services,
            $dispatcher
        );

        $this->event = new ServiceScannedEvent(
            $report
        );

        $report->method('getPort')
            ->willReturn(80);
        $report->method('isSuccessful')
            ->willReturn(true);
        $report->method('getNode')
            ->willReturn($node);

        $this->services->method('findByNodePort')
            ->with($node, 80)
            ->willReturn(null);

        $this->service->method('getId')
            ->willReturn(Uuid::v7());
        $this->service->method('getStateName')
            ->willReturn('node.service.80');
        $node->method('getId')
            ->willReturn(Uuid::v7());
    }

    public function testInvoke(): void
    {
        $this->services->expects($this->once())
            ->method('create')
            ->willReturn($this->service);

        $this->listener->__invoke($this->event);
    }
}
