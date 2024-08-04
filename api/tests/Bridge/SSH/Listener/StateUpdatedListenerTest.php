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

use Dungap\Bridge\SSH\Configuration;
use Dungap\Bridge\SSH\Contracts\NodeExporterInterface;
use Dungap\Bridge\SSH\Contracts\SshFactoryInterface;
use Dungap\Bridge\SSH\Contracts\SshInterface;
use Dungap\Bridge\SSH\Listener\StateUpdatedListener;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Contracts\State\StateInterface;
use Dungap\Dungap;
use Dungap\State\Event\StateUpdatedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

class StateUpdatedListenerTest extends TestCase
{
    private MockObject|LoggerInterface $logger;
    private MockObject|NodeExporterInterface $exporter;
    private StateUpdatedEvent $event;
    private StateUpdatedListener $listener;

    protected function setUp(): void
    {
        $factory = $this->createMock(SshFactoryInterface::class);
        $service = $this->createMock(ServiceInterface::class);
        $node = $this->createMock(NodeInterface::class);
        $state = $this->createMock(StateInterface::class);
        $ssh = $this->createMock(SshInterface::class);
        $this->exporter = $this->createMock(NodeExporterInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->listener = new StateUpdatedListener(
            $factory,
            [$this->exporter],
            $this->logger
        );

        $sshConfig = new Configuration(
            'localhost',
            'toni'
        );
        $factory->method('createSshClient')
            ->willReturn($ssh);
        $ssh->method('getConfig')
            ->willReturn($sshConfig);

        $service->method('getPort')
            ->willReturn($sshConfig->port);

        $ssh->expects($this->atLeastOnce())
            ->method('login')
            ->willReturn(true);

        $node->expects($this->atLeastOnce())
            ->method('getExporter')
            ->willReturn(Dungap::NodeExporterSSH);

        $service->method('getId')->willReturn(Uuid::v7());
        $node->method('getId')->willReturn(Uuid::v7());
        $this->event = new StateUpdatedEvent(
            entity: $service,
            name: 'service.zeus.22',
            state: 'online',
            related: $node,
        );
    }

    public function testInvoke(): void
    {
        $this->exporter->expects($this->once())
            ->method('process');

        $this->listener->__invoke($this->event);
    }

    public function testInvokeWithException(): void
    {
        $this->exporter->expects($this->once())
            ->method('process')
            ->willThrowException(new \Exception());

        $this->logger->expects($this->once())
            ->method('error');
        $this->listener->__invoke($this->event);
    }
}
