<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Service\Handler;

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Contracts\Service\ServiceReportInterface;
use Dungap\Contracts\Service\ServiceRepositoryInterface;
use Dungap\Contracts\Service\ServiceValidatorInterface;
use Dungap\Service\Command\ServiceCheckCommand;
use Dungap\Service\Handler\ServiceCheckHandler;
use Dungap\State\Event\StateUpdatedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ServiceCheckHandlerTest extends TestCase
{
    private MockObject|ServiceRepositoryInterface $services;
    private MockObject|ServiceInterface $service;
    private MockObject|ServiceValidatorInterface $validator;
    private MockObject|ServiceReportInterface $report;
    private MockObject|LoggerInterface $logger;
    private MockObject|EventDispatcherInterface $dispatcher;
    private MockObject|NodeInterface $node;

    private ServiceCheckCommand $command;
    private ServiceCheckHandler $handler;

    protected function setUp(): void
    {
        $this->services = $this->createMock(ServiceRepositoryInterface::class);
        $this->service = $this->createMock(ServiceInterface::class);
        $this->validator = $this->createMock(ServiceValidatorInterface::class);
        $this->report = $this->createMock(ServiceReportInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->node = $this->createMock(NodeInterface::class);
        $this->command = new ServiceCheckCommand();

        $this->handler = new ServiceCheckHandler(
            $this->services,
            $this->validator,
            $this->dispatcher,
            $this->logger
        );

        $this->services->method('findAll')
            ->willReturn([$this->service]);
        $this->service->method('getNode')
            ->willReturn($this->node);
        $this->service->method('getId')
            ->willReturn(Uuid::v7());
        $this->service->method('getPort')
            ->willReturn(80);
        $this->service->method('getStateName')
            ->willReturn('node.service.80');
        $this->node->method('getId')
            ->willReturn(Uuid::v7());
    }

    public function testInvoke(): void
    {
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->node)
            ->willReturn($this->report);
        $this->dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(StateUpdatedEvent::class));

        $this->handler->__invoke($this->command);
    }

    public function testWhenValidateError(): void
    {
        $this->validator->expects($this->once())
            ->method('validate')
            ->willThrowException(new \Exception('test'));

        $this->logger->expects($this->once())
            ->method('error');
        $this->handler->__invoke($this->command);
    }

    public function testWhenDispatchError(): void
    {
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->node)
            ->willReturn($this->report);
        $this->dispatcher->expects($this->once())
            ->method('dispatch')
            ->willThrowException(new \Exception('test'));

        $this->logger->expects($this->once())
            ->method('error');
        $this->handler->__invoke($this->command);
    }
}
