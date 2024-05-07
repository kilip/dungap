<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Node\Handler;

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Node\NodeRepositoryInterface;
use Dungap\Contracts\Node\OnlineCheckerInterface;
use Dungap\Node\Command\CheckOnlineNodesCommand;
use Dungap\Node\Entity\NodeStates;
use Dungap\Node\Handler\CheckOnlineNodesHandler;
use Dungap\Node\State\PingReport;
use Dungap\State\Event\StateUpdatedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class CheckOnlineNodesHandlerTest extends TestCase
{
    private MockObject|NodeRepositoryInterface $nodes;
    private MockObject|NodeInterface $node;

    private MockObject|OnlineCheckerInterface $onlineChecker;
    private MockObject|LoggerInterface $logger;
    private MockObject|EventDispatcherInterface $dispatcher;

    private NodeStates $nodeStates;
    private PingReport $report;
    private CheckOnlineNodesHandler $handler;

    protected function setUp(): void
    {
        $this->nodes = $this->createMock(NodeRepositoryInterface::class);
        $this->node = $this->createMock(NodeInterface::class);
        $this->onlineChecker = $this->createMock(OnlineCheckerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->handler = new CheckOnlineNodesHandler(
            $this->nodes,
            $this->dispatcher,
            $this->onlineChecker,
            $this->logger
        );

        $this->report = new PingReport(
            true,
            5
        );

        $this->nodes
            ->method('findAll')
            ->willReturn([$this->node]);

        $this->node->method('getId')
            ->willReturn(Uuid::v7());

        $this->node->method('getIp')
            ->willReturn('127.0.0.1');

        $this->node->method('getName')
            ->willReturn('zeus');
        $this->nodeStates = NodeStates::create($this->node);
        $this->node->method('getStates')->willReturn($this->nodeStates);
    }

    public function testInvoke(): void
    {
        $this->dispatcher->expects($this->exactly(1))
            ->method('dispatch')
            ->with($this->isInstanceOf(StateUpdatedEvent::class));
        $this->onlineChecker->expects($this->once())
            ->method('check')
            ->with($this->node)
            ->willReturn($this->report);

        $this->handler->__invoke(new CheckOnlineNodesCommand());
    }

    public function testWithError(): void
    {
        $this->onlineChecker->expects($this->once())
            ->method('check')
            ->willThrowException(new \Exception('test'));

        $this->logger->expects($this->once())
            ->method('error');

        $this->handler->__invoke(new CheckOnlineNodesCommand());
    }
}
