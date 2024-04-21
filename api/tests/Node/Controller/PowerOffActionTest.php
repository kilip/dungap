<?php

namespace Dungap\Tests\Node\Controller;

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Node\Command\PowerOffCommand;
use Dungap\Node\Controller\PowerOffAction;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class PowerOffActionTest extends TestCase
{

    public function testInvoke(): void
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $node = $this->createMock(NodeInterface::class);
        $action = new PowerOffAction($messageBus);

        $messageBus->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(PowerOffCommand::class))
            ->willReturn(new Envelope(new \stdclass()))
        ;

        $node->expects($this->once())
            ->method('getId')
            ->willReturn(Uuid::v1());

        $response = $action($node);

        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }
}
