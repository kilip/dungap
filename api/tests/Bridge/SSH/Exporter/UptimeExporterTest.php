<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\SSH\Exporter;

use Dungap\Bridge\SSH\Contracts\SshInterface;
use Dungap\Bridge\SSH\Exporter\UptimeExporter;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Node\Entity\NodeStates;
use Dungap\State\Event\StateUpdatedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UptimeExporterTest extends TestCase
{
    private MockObject|EventDispatcherInterface $dispatcher;
    private MockObject|NodeInterface $node;
    private MockObject|SshInterface $ssh;
    private UptimeExporter $exporter;

    protected function setUp(): void
    {
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->node = $this->createMock(NodeInterface::class);
        $this->ssh = $this->createMock(SshInterface::class);
        $this->exporter = new UptimeExporter(
            $this->dispatcher
        );

        $this->node->method('getName')
            ->willReturn('zeus');

        $nodeStates = NodeStates::create($this->node);
        $this->node->method('getStates')
            ->willReturn($nodeStates);
        $this->node->method('getId')
            ->willReturn(Uuid::v7());
    }

    public function testProcess(): void
    {
        $this->ssh->expects($this->exactly(2))
            ->method('execute')
            ->willReturn('UTC', '2024-05-07 22:18:32')
        ;

        $this->dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(StateUpdatedEvent::class));

        $this->exporter->process($this->node, $this->ssh);
    }
}
