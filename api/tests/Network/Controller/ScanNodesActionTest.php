<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Network\Controller;

use Dungap\Network\Command\ScanNodesCommand;
use Dungap\Network\Controller\ScanNodesAction;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ScanNodesActionTest extends TestCase
{
    private MockObject|MessageBusInterface $messageBus;
    private ScanNodesAction $action;
    private MockObject|Request $request;

    protected function setUp(): void
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->request = $this->createMock(Request::class);
        $this->action = new ScanNodesAction($this->messageBus);
    }

    public function testInvoke(): void
    {
        $messageBus = $this->messageBus;
        $request = $this->request;
        $action = $this->action;

        $request->expects($this->once())
            ->method('toArray')
            ->willReturn(['target' => ['10.0.0.0/24']]);

        $messageBus->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(ScanNodesCommand::class))
            ->willReturn(new Envelope(new \stdClass()));

        $response = $action($request);

        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }
}
