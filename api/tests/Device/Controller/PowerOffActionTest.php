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

use Dungap\Device\Command\PowerOffCommand;
use Dungap\Device\Entity\Device;
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
        $device = $this->createMock(Device::class);
        $action = new \Dungap\Device\Controller\PowerOffAction($messageBus);

        $messageBus->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(PowerOffCommand::class))
            ->willReturn(new Envelope(new \stdClass()))
        ;

        $device->expects($this->once())
            ->method('getId')
            ->willReturn(Uuid::v1());

        $response = $action($device);

        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }
}
