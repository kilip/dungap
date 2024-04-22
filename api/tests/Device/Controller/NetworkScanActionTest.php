<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Device\Controller;

use Dungap\Device\Command\NetworkScanCommand;
use Dungap\Device\Controller\NetworkScanAction;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class NetworkScanActionTest extends TestCase
{
    private MockObject|MessageBusInterface $messageBus;

    private NetworkScanAction $action;

    protected function setUp(): void
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->action = new \Dungap\Device\Controller\NetworkScanAction($this->messageBus);
    }

    public function testInvoke(): void
    {
        $messageBus = $this->messageBus;
        $command = new NetworkScanCommand(target: ['10.0.0.0/24']);
        $action = $this->action;

        $messageBus->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->willReturn(new Envelope(new \stdClass()));

        $response = $action($command);
        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }
}
