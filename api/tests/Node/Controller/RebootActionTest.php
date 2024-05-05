<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Node\Controller;

use Dungap\Node\Command\RebootCommand;
use Dungap\Node\Controller\RebootAction;
use Dungap\Node\Entity\Node;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class RebootActionTest extends TestCase
{
    public function testInvoke(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);
        $action = new RebootAction($bus);
        $node = $this->createMock(Node::class);

        $node->method('getId')->willReturn(Uuid::v7());
        $bus->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(RebootCommand::class))
            ->willReturn(new Envelope(new \stdClass()));

        $response = $action($node);

        $this->assertSame(
            Response::HTTP_NO_CONTENT,
            $response->getStatusCode()
        );
    }
}
