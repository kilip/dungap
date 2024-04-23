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

use Dungap\Device\Controller\PowerOnAction;
use Dungap\Device\Entity\Device;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class PowerOnActionTest extends TestCase
{
    public function testInvoke(): void
    {
        $device = $this->createMock(Device::class);
        $bus = $this->createMock(MessageBusInterface::class);
        $action = new PowerOnAction($bus);

        $bus->expects($this->once())
            ->method('dispatch')
            ->willReturn(new Envelope(new \stdClass()));

        $device->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(Uuid::v7()));

        $response = $action($device);

        $this->assertSame('', $response->getContent());
        $this->assertSame(204, $response->getStatusCode());
    }
}
