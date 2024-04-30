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
use Dungap\Device\Controller\PowerOff;
use Dungap\Device\Entity\Device;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class PowerOffTest extends TestCase
{
    public function testInvoke(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);
        $controller = new PowerOff($bus);
        $device = $this->createMock(Device::class);
        $device->expects($this->once())
            ->method('getId')
            ->willReturn(Uuid::v1());

        $bus->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(PowerOffCommand::class))
            ->willReturn(new Envelope(new \stdClass()));

        $controller($device);
    }
}
