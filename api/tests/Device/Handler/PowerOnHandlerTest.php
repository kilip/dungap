<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Device\Handler;

use Dungap\Contracts\Device\DeviceRepositoryInterface;
use Dungap\Device\Command\PowerOnCommand;
use Dungap\Device\DeviceConstant;
use Dungap\Device\DeviceException;
use Dungap\Device\Entity\Device;
use Dungap\Device\Handler\PowerOnHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class PowerOnHandlerTest extends TestCase
{
    private MockObject|DeviceRepositoryInterface $deviceRepository;
    private MockObject|EventDispatcherInterface $dispatcher;
    private MockObject|Device $device;

    private PowerOnHandler $handler;

    protected function setUp(): void
    {
        $this->deviceRepository = $this->createMock(DeviceRepositoryInterface::class);
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->device = $this->createMock(Device::class);
        $this->handler = new PowerOnHandler(
            $this->deviceRepository, $this->dispatcher
        );
    }

    public function testInvoke(): void
    {
        $this->deviceRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo('some-id'))
            ->willReturn($this->device);

        $this->dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->device, DeviceConstant::EventDevicePowerOn)
        ;

        $command = new PowerOnCommand('some-id');
        $this->handler->__invoke($command);
    }

    public function testInvokeWithInvalidDevice(): void
    {
        $this->deviceRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo('some-id'))
            ->willReturn(null);

        $this->expectException(DeviceException::class);

        $command = new PowerOnCommand('some-id');
        $this->handler->__invoke($command);
    }
}
