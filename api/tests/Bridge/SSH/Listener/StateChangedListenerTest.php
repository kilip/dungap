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
use Dungap\Bridge\SSH\Listener\StateChangedListener;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Contracts\State\StateInterface;
use Dungap\State\Event\StateChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class StateChangedListenerTest extends TestCase
{
    private MockObject|LoggerInterface $logger;
    private MockObject|NodeExporterInterface $exporter;
    private StateChangedEvent $event;
    private StateChangedListener $listener;

    protected function setUp(): void
    {
        $factory = $this->createMock(SshFactoryInterface::class);
        $service = $this->createMock(ServiceInterface::class);
        $node = $this->createMock(NodeInterface::class);
        $state = $this->createMock(StateInterface::class);
        $ssh = $this->createMock(SshInterface::class);
        $this->exporter = $this->createMock(NodeExporterInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->listener = new StateChangedListener(
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

        $this->event = new StateChangedEvent(
            $state,
            $service,
            $node,
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
