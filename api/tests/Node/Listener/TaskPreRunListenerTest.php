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
use Dungap\Contracts\Node\NodeRepositoryInterface;
use Dungap\Node\Config\Config;
use Dungap\Node\Config\Host;
use Dungap\Node\Event\NodeAddedEvent;
use Dungap\Node\Listener\TaskPreRunListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TaskPreRunListenerTest extends TestCase
{
    private MockObject|Config $config;
    private MockObject|NodeRepositoryInterface $nodes;
    private MockObject|NodeInterface $node;
    private MockObject|EventDispatcherInterface $dispatcher;
    private MockObject|LoggerInterface $logger;

    private TaskPreRunListener $listener;
    private Host $host;

    public function setUp(): void
    {
        $this->config = $this->createMock(Config::class);
        $this->nodes = $this->createMock(NodeRepositoryInterface::class);
        $this->node = $this->createMock(NodeInterface::class);
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->host = new Host('zeus');
        $this->listener = new TaskPreRunListener(
            config: $this->config,
            nodes: $this->nodes,
            dispatcher: $this->dispatcher,
            logger: $this->logger
        );

        $this->config->method('getHosts')
            ->willReturn([$this->host]);
        $this->nodes->method('findByName')
            ->willReturn(null);
        $this->nodes->method('create')
            ->willReturn($this->node);
    }

    public function testInvoke(): void
    {
        $this->nodes->expects($this->once())
            ->method('save')
            ->with($this->node);

        $this->dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(NodeAddedEvent::class));

        $this->listener->__invoke();
    }

    public function testWhenProcessError(): void
    {
        $this->nodes->expects($this->once())
            ->method('save')
            ->willThrowException(new \Exception('test'));
        $this->logger->expects($this->once())
            ->method('error');
        $this->listener->__invoke();
    }

    public function testWhenDispatchEventError(): void
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch')
            ->willThrowException(new \Exception('test'));
        $this->logger->expects($this->once())
            ->method('error');
        $this->listener->__invoke();
    }
}
